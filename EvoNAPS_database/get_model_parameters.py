
#! /usr/bin/env python3

"""
Python script to access EvoNAPS database. The script will take the input provided by the user and translate it into a query.
The results of the query will be written into a csv file.

Author: Franziska Reden 
Created: 28.03.2023
"""

import pandas as pd
import mysql.connector as mariadb
import sys
import time
import datetime

def checkInteger(input, string='variable'): 
    """Function to check if variables are integers."""
    
    if input is not None: 
        try:
            int(input)
        except ValueError: 
            raise ValueError('ERROR: '+input+' for '+string+' is invalid, must be integer.')

def checkFloat(input, string='variable'): 
    """Function to check if variables are floats."""

    if input is not None: 
        try: 
            float(input)
        except ValueError: 
            raise ValueError('ERROR: '+input+' for '+string+' is invalid, must be float.')

def CheckInput(matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source): 
    """
    Function to check the input provided by the user. This function is neccessary to mend any 
    discrepancies between the users's input the nomenclature in the EvoNAPS database and to, 
    therefore, avoid any syntax errors induced by the query.
    """

    # First, declare which matrices are allowed.
    allowed_models = ['JC','F81+F','K2P','HKY+F','TN+F','TNe','TPM2','TPM2u+F','TIM2+F','TIM2e','TPM3','TPM3u+F',\
        'TIM3+F','TIM3e','K3P','K3Pu+F','TIM+F','TIMe','TVM+F','TVMe','SYM','GTR+F']
    
    semi_allowed_models={'F81':'F81+F', 'GTR':'GTR+F', 'HKY':'HKY+F', 'K3PU':'K3Pu+F', 'TIM':'TIM+F', 'TIM2':'TIM2+F', \
            'TIM3':'TIM3+F', 'TN':'TN+F', 'TPM2U':'TPM2u+F', 'TPM3U':'TPM3u+F', 'TVM':'TVM+F'}
    for mod in allowed_models: 
        semi_allowed_models.setdefault(mod.upper(), mod)

    # Check if input matrix is valid (needs to match one of the 22 tested sub rate matrices).
    matrix = matrix.split(',')
    for mod in matrix: 
        if mod not in allowed_models: 
            if mod.upper() in semi_allowed_models.keys(): 
                matrix.remove(mod)
                matrix.append(semi_allowed_models[mod.upper()])
            else: 
                raise ValueError(mod+' is not a valid substitution rate matrix.')
    
    # Remove duplicates from list of models
    matrix = list(dict.fromkeys(matrix))

    # Check if rate model is valid. 
    # Declare allowed rates.
    allowed_rates = ['', 'I', 'I+G4', 'G4']
    for i in range (2, 11, 1): 
        allowed_rates.append('R'+str(i))
    semi_allowed_rates = {'E': '', 'G':'G4', 'G4+I':'I+G4', 'G+I':'I+G4', 'I+G':'I+G4', 
                          '+E': '', '+G':'G4', '+G4+I':'I+G4', '+G+I':'I+G4', '+I+G':'I+G4'}
    for ra in allowed_rates: 
        semi_allowed_rates.setdefault('+'+ra, ra)
    
    rate = rate.split(',')
    rate = [x.upper() for x in rate]
    for ra in rate: 
        if ra not in allowed_rates: 
            if ra in semi_allowed_rates.keys(): 
                rate.remove(ra)
                rate.append(semi_allowed_rates[ra])
            else: 
                raise ValueError('Invalid input for rate heterogenity: '+ra+'.')
            
    # Remove duplicates from list of rate models           
    rate = list(dict.fromkeys(rate))

    # Check if tables input is valid.
    tables = tables.split(',')
    tables = [x.upper() for x in tables]
    
    for i in range (len(tables)): 
        if tables[i] not in ['TREE', 'TEST']: 
            raise ValueError(tables[i]+' is an invalid input. Tables can be \'tree\' or \'test\' or both (seperated by a comma, e.g., \'test,tree\'.)')

    # Check selection criterion 
    if ic.upper() not in ['BIC', 'AIC', 'AICC', 'ABIC', 'CAIC']: 
        raise ValueError('The selection criterion '+ic+' is invalid.')   
    else: 
        ic=ic.upper()

    # Check sig level
    checkFloat(ic_sig, string='ic_sig')
    if ic_sig is None: 
        ic_sig = 0.05

    # Check if min max limits are integers
    checkInteger(seq_min, 'seq_min')
    checkInteger(seq_max, 'seq_max')
    checkInteger(col_min, 'col_min')
    checkInteger(col_max, 'col_max')

    if branch not in [True, False]: 
        raise ValueError('Invalid input for branch variable: '+branch+'. Must be either True or False.')
    
    if tree not in [True, False]: 
        raise ValueError('Invalid input for tree variable: '+tree+'. Must be either True or False.')

    if keep not in [True, False]: 
        raise ValueError('Invalid input for keep variable: '+keep+'. Must be either True or False.')

    # Check source input 
    if source is not None: 
        valid_source = {'PANDIT':'PANDIT', 'LANFEAR':'Lanfear', 'ORTHOMAM':'OrthoMaM'}
        source = source.split(',')
        for scr in source: 
            if scr.upper() in valid_source.keys(): 
                source.remove(scr)
                source.append(valid_source[scr.upper()])
            else: 
                raise ValueError(scr+' is an invalid input for source. Must be Pandit, Lanfear and/or OrthoMaM.')

    return matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source

def fetchParameters(matrix, rate: str='E', seq_min: int = None, seq_max: int = None, col_min: int = None, col_max: int = None, \
    tables: str='TEST,TREE', ic: str='BIC', ic_sig: float=0.05, user='frareden', password='Franzi987', \
        tree:bool=False, branch:bool=False, keep:bool=True, source: str=None) -> pd.DataFrame: 
    """
    Description
    --------
    Constructs a query for the EvoNAPS database using the input provided by the user. 

    Returns a DataFrame including all relevent parameters that can be used as input for seqeuence simulations. 

    Parameter(s)
    --------
    matrix : str
        Name of the substitution rate matrix or matrices for which to filter. 
        Valid matrices are: JC, F81, K2P, HKY, TN, TNe, TPM2, TPM2u, TIM2, TIM2e, TPM3, TPM3u, TIM3, TIM3e, K3P, K3Pu, TIM, TIMe, TVM, TVMe, SYM, GTR  
    rate : str, optional
        Name of the model of rate heterogenity for which to filter. Ddefault is 'E'.
        Valid models of rate heterogenity: E, I, I+G4, G4, R2, R3, R4, R5, R6, R7, R8, R9, R10

    Other Parameter(s)
    --------
    user : str
        States the user name to be used to access the EvoNAPS database 
    password : str
        States the user specific password neccessary to access the database
    tables : str
        Declare in which tables to search, namely dna_modelparameters (test) and/or dna_trees (tree) (default is 'tree,test')
        Input is restricted to: 'tree', 'test'
    tree : bool
        Flag that states whether to return the tree in Newickstring format. For models of the dna_modelparameters table it is the initial tree 
        (a parsimony or fast ML tree built by IQTree2 at the beginning of model evaluation). For models of the dna_trees table, this is the ML tree.  
        Options are 'True' or 'False' (default is 'False')
    branch : bool
        Should the user search in the dna_trees table, they can decide to also output the branch lengths of the ML tree in a seperate file. 
        Options are 'True' or 'False' (default is 'False')
    seq_min : int, otional
        Option to restrict search to alignments with at least n sequences (or taxa) (default is None)
    seq_max : int, optional
        Option to restrict search to alignments with at most n sequences (or taxa) (default is None)
    col_min : int, optional
        Option to restrict search to alignments with at least n columns (or sites) (default is None)
    col_max : int, optional
        Option to restrict search to alignments with at most n columns (or sites) (default is None)
    ic : str, optional 
        If you search in the dna_modelparameters table, you can restrict your search to models that fit the alignment 
        significantly well according to the a selection criterion, namely BIC, AIC, AICc, CAIC or ABIC (Default is 'BIC'). 
        If the option is set to 'None', then all model parameter settings regardless of how well the model fits the alignment
        will be returned. However, it is highly recommened to restrict the model search to models that fit the alignment well. 
        Options: 'BIC', 'AIC', 'AICc', 'CAIC', 'ABIC', 'None'
    ic_sig : float, optional
        Should you restrict your search with the 'ic' option, you can set a significance level. In this case, only parameters 
        of models with a weight above the chosen limit will be returned. Default is 0.05. 
    keep: bool
        If you also want to search the database for results obtained by running IQ-Tree 2 with the '--keep-ident' flag, set this 
        parameter to True. Default is False (in this case, only inference results conducted on the (potentially) reduced alignments 
        are shown)
    source: str, optional
        Restrict the search to alignments from a specific source. Options: PANIDT, OrthoMaM, Lanfear 
        Default is 'None' (no search resistrictions).

    Returns
    --------
    pramaters_df : pd.DataFrame
        A pandas dataframe inlcuding the results of the query or queries. 
        The column names are: 
        ['TABLE', 'ALI_KEY', 'MODELTEST_KEY', 'TREE_KEY','ALI_ID', 'KEEP_IDENT', 
        'MODEL','BASE_MODEL','MODEL_RATE_HETEROGENEITY', 
        'LOGL',ic,'WEIGHTED_'+ic, 
        'STAT_FREQ_A','STAT_FREQ_C','STAT_FREQ_G','STAT_FREQ_T',
        'RATE_AC','RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR', 
        'REL_RATE_CAT_1','PROP_CAT_1','REL_RATE_CAT_2','PROP_CAT_2','REL_RATE_CAT_3','PROP_CAT_3',
        'REL_RATE_CAT_4','PROP_CAT_4','REL_RATE_CAT_5','PROP_CAT_5','REL_RATE_CAT_6','PROP_CAT_6',
        'REL_RATE_CAT_7','PROP_CAT_7','REL_RATE_CAT_8','PROP_CAT_8','REL_RATE_CAT_9','PROP_CAT_9',
        'REL_RATE_CAT_10','PROP_CAT_10']
        Optionally: 'NEWICK_STRING' in last column.
    branch_df : pd.DataFrame | None
        A pandas DataFrame holding the branch parameters of the ML tree or None if the option 'branch' is set to False.
        The column names are: 
        ['ALI_KEY', 'TREE_KEY', 'BRANCH_KEY', 'ALI_ID', 'TREE_TYPE', 'BRANCH_INDEX', 'BRANCH_TYPE', 'SEQ_NAME', 'BL', 'SPLIT_SIZE']
        in the parameters_df DataFrame.
    hit_table : pd.DataFrame
        The hit table includes the number of hits for each model and table.

    Example
    --------
    In the example below we filter for the parameters of the models GTR+F+G4 and 
    further restrict the search to alignmnets with at least 50 sequences and to the dna_modelparameters table. 
    We further filter for models that fit the alignment significantly well according to the BIC value with a 
    limit of 0.1. 

    >>> fetchParameters('GTR', rate_het='G4', tables='Test', seq_min=50, ic='BIC', ic_sig=0.1)
    """

    # Check the input. This step is neccessary to avoid any syntax errors in the query. 
    matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source \
         = CheckInput(matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source)
    
    # Declare list of models to be evaluated (combination of rate matrices and models of rate heterogeneiity)
    models=[]
    for ma in matrix: 
        for ra in rate: 
            if ra != '': 
                models.append(ma+'+'+ra)
            else: 
                models.append(ma)

    # Transform list of models into a single string (to be integrated into query)
    string_models='('
    for i in range (len(models)): 
        if i < len(models)-1:
            string_models+='\''+models[i]+'\','
        else: 
            string_models+='\''+models[i]+'\')'

    # Create string for source: 
    if source is not None: 
        source_string='('
        for i in range (len(source)): 
            if i < len(source)-1:
                source_string+='\''+source[i]+'\','
            else: 
                source_string+='\''+source[i]+'\')'

    # Declare the column names of the DataFrame to be filled with the query results.
    query_df = pd.DataFrame(columns = ['TABLE', 'ALI_KEY', 'MODELTEST_KEY', 'TREE_KEY','ALI_ID', 'KEEP_IDENT', \
        'MODEL','BASE_MODEL','MODEL_RATE_HETEROGENEITY', \
            'LOGL',ic,'WEIGHTED_'+ic, 
                'STAT_FREQ_A','STAT_FREQ_C','STAT_FREQ_G','STAT_FREQ_T',\
                    'RATE_AC','RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR', \
                        'REL_RATE_CAT_1','PROP_CAT_1','REL_RATE_CAT_2','PROP_CAT_2','REL_RATE_CAT_3','PROP_CAT_3',\
                            'REL_RATE_CAT_4','PROP_CAT_4','REL_RATE_CAT_5','PROP_CAT_5','REL_RATE_CAT_6','PROP_CAT_6',\
                                'REL_RATE_CAT_7','PROP_CAT_7','REL_RATE_CAT_8','PROP_CAT_8','REL_RATE_CAT_9','PROP_CAT_9',\
                                    'REL_RATE_CAT_10','PROP_CAT_10'])

    branch_df = None

    if tree is True:
        query_df.insert(len(query_df.columns), 'NEWICK_STRING', [])

    hit_table = pd.DataFrame(columns = ['TABLE', 'MODEL', 'HITS'])

    #Connect to the Database
    mydb = mariadb.connect(
    host="crick",
    user=user,
    password=password, 
    database="fra_db")

    # Create cursor
    mycursor = mydb.cursor()

    # Check if search will be conducted in dna_modelparameters table
    if 'TEST' in tables: 

        # Create query...
        query = "SELECT \
a.ALI_KEY, b.MODELTEST_KEY, c.TREE_KEY, a.ALI_ID, \
b.KEEP_IDENT, b.MODEL, b.BASE_MODEL, b.MODEL_RATE_HETEROGENEITY, \
b.LOGL, b."+ic+", b.WEIGHTED_"+ic+", \
b.STAT_FREQ_A, b.STAT_FREQ_C, b.STAT_FREQ_G, b.STAT_FREQ_T, \
b.RATE_AC, b.RATE_AG, b.RATE_AT, b.RATE_CG, b.RATE_CT, b.RATE_GT, \
b.ALPHA, b.PROP_INVAR, \
b.REL_RATE_CAT_1, b.PROP_CAT_1, b.REL_RATE_CAT_2, b.PROP_CAT_2, \
b.REL_RATE_CAT_3, b.PROP_CAT_3, b.REL_RATE_CAT_4, b.PROP_CAT_4, \
b.REL_RATE_CAT_5, b.PROP_CAT_5, b.REL_RATE_CAT_6, b.PROP_CAT_6, \
b.REL_RATE_CAT_7, b.PROP_CAT_7, b.REL_RATE_CAT_8, b.PROP_CAT_8, \
b.REL_RATE_CAT_9, b.PROP_CAT_9, b.REL_RATE_CAT_10, b.PROP_CAT_10 "
        if tree is True: 
            query += ", c.NEWICK_STRING "
        query += "FROM dna_modelparameters b \
INNER JOIN dna_alignments a USING (ALI_ID) "
        query += "INNER JOIN dna_trees c USING (ALI_ID,TIME_STAMP) "
        query += "WHERE b.MODEL IN "+string_models+" "  
        if ic is not None: 
            query += "AND b.WEIGHTED_"+ic+">"+str(ic_sig)+" "
        if source is not None: 
            query += "AND a.FROM_DATABASE IN "+source_string+" "
        if keep is False: 
            query += "AND b.KEEP_IDENT=0 "
        if seq_min is not None:
            query += "AND a.SEQUENCES >= "+seq_min+" "
        if seq_max is not None:
            query += "AND a.SEQUENCES <= "+seq_max+" "
        if col_min is not None: 
            query += "AND a.COLUMNS >= "+col_min+" "
        if col_max is not None: 
            query += "AND a.COLUMNS <= "+col_max+""
        query += " AND c.TREE_TYPE = \'initial\';" 
        
        # Execute query and fetch results 
        mycursor.execute(query)
        myresult = mycursor.fetchall()

        # Results will be written into DataFrame and combined with potential preexisting results
        test_df = pd.DataFrame(myresult, columns = [key for key in query_df.drop(columns='TABLE').columns])
        test_df.insert(0,'TABLE','dna_modelparameters')
        query_df = pd.concat([query_df, test_df], axis=0)

        # Overview of results are written into hit_table
        hits_test = test_df['MODEL'].value_counts().to_frame().reset_index().rename(columns={'index':'MODEL', 'MODEL':'HITS'})
        for mo in models: 
            if mo not in hits_test['MODEL'].to_list(): 
                hits_test = hits_test.append({'MODEL':mo, 'HITS':0}, ignore_index=True)
        hits_test.insert(0,'TABLE','dna_modelparameters')
        hit_table = pd.concat([hit_table, hits_test], axis=0)

    # Check if search will be conducted in dna_trees table
    if 'TREE' in tables: 

        # Create query...
        query = "SELECT \
a.ALI_KEY, b.TREE_KEY, a.ALI_ID, \
b.KEEP_IDENT, b.MODEL, b.BASE_MODEL, b.MODEL_RATE_HETEROGENEITY, \
b.LOGL, \
b.STAT_FREQ_A, b.STAT_FREQ_C, b.STAT_FREQ_G, b.STAT_FREQ_T, \
b.RATE_AC, b.RATE_AG, b.RATE_AT, b.RATE_CG, b.RATE_CT, b.RATE_GT, \
b.ALPHA, b.PROP_INVAR, \
b.REL_RATE_CAT_1, b.PROP_CAT_1, b.REL_RATE_CAT_2, b.PROP_CAT_2, \
b.REL_RATE_CAT_3, b.PROP_CAT_3, b.REL_RATE_CAT_4, b.PROP_CAT_4, \
b.REL_RATE_CAT_5, b.PROP_CAT_5, b.REL_RATE_CAT_6, b.PROP_CAT_6, \
b.REL_RATE_CAT_7, b.PROP_CAT_7, b.REL_RATE_CAT_8, b.PROP_CAT_8, \
b.REL_RATE_CAT_9, b.PROP_CAT_9, b.REL_RATE_CAT_10, b.PROP_CAT_10 "
        if tree is True: 
            query += ", b.NEWICK_STRING "
        query += "FROM dna_trees b \
INNER JOIN dna_alignments a USING (ALI_ID) "
        query += "WHERE b.MODEL IN "+string_models+" " 
        if source is not None: 
            query += "AND a.FROM_DATABASE IN "+source_string+" "
        if keep is False: 
            query += "AND b.KEEP_IDENT=0 "
        if seq_min is not None:
            query += "AND a.SEQUENCES >= "+seq_min+" "
        if seq_max is not None:
            query += "AND a.SEQUENCES <= "+seq_max+" "
        if col_min is not None: 
            query += "AND a.COLUMNS >= "+col_min+" "
        if col_max is not None: 
            query += "AND a.COLUMNS <= "+col_max+""
        query += " AND b.TREE_TYPE = \'ml\';" 
        
        # Execute query and fetch the results
        mycursor.execute(query)
        myresult = mycursor.fetchall()

        # Results will be written into DataFrame and combined with potential preexisting results
        tree_df = pd.DataFrame(myresult, columns = [key for key in query_df.drop(columns=['TABLE', 'MODELTEST_KEY', ic, 'WEIGHTED_'+ic]).columns])
        tree_df.insert(0,'TABLE','dna_trees')
        query_df = pd.concat([query_df, tree_df], axis=0)

        # Overview of results are written into hit_table
        hits_tree = tree_df['MODEL'].value_counts().to_frame().reset_index().rename(columns={'index':'MODEL', 'MODEL':'HITS'})
        for mo in models: 
            if mo not in hits_tree['MODEL'].to_list(): 
                hits_tree = hits_tree.append({'MODEL':mo, 'HITS':0}, ignore_index=True)
        hits_tree.insert(0,'TABLE','dna_trees')
        hit_table = pd.concat([hit_table, hits_tree], axis=0)

        # If branch option is true, also filter for BLs.
        if branch is True: 

            branch_df = pd.DataFrame(columns = ['ALI_KEY', 'TREE_KEY', 'BRANCH_KEY', 'ALI_ID', 'TREE_TYPE', 'BRANCH_INDEX', 'BRANCH_TYPE', \
                'SEQ_NAME', 'BL', 'SPLIT_SIZE'])

            # Create query...
            query = "SELECT a.ALI_KEY, d.TREE_KEY, b.BRANCH_KEY, a.ALI_ID, b.TREE_TYPE, b.BRANCH_INDEX, b.BRANCH_TYPE, \
c.SEQ_NAME, b.BL, b.SPLIT_SIZE FROM dna_branches b \
INNER JOIN dna_alignments a USING (ALI_ID) \
INNER JOIN dna_trees d ON (b.ALI_ID=d.ALI_ID) AND (b.TREE_TYPE=d.TREE_TYPE) AND (b.TIME_STAMP=d.TIME_STAMP) \
LEFT JOIN dna_sequences c ON (b.ALI_ID=c.ALI_ID) AND (b.BRANCH_INDEX=c.SEQ_INDEX) \
WHERE b.TREE_TYPE = \'ml\' AND d.MODEL IN "+string_models+""

            # Restrict the search if number of minumum or maximum sequences and sites in the alignment have been specified.
            if seq_min is not None:
                query += " AND a.SEQUENCES > "+seq_min+""
            if seq_max is not None:
                query += " AND a.SEQUENCES <= "+seq_max+""
            if col_min is not None: 
                query += " AND a.COLUMNS >= "+col_min+""
            if col_max is not None: 
                query += " AND a.COLUMNS <= "+col_max+""
            query += ";"

            # Execute query and fetch results
            mycursor.execute(query)
            myresult = mycursor.fetchall()

            # Write results into a dataframe with column names as declared in the branch_df dataframe.
            new_branch_df = pd.DataFrame(myresult, columns = [key for key in branch_df.columns])
            branch_df = pd.concat([branch_df, new_branch_df], ignore_index=True)
    
    # Return results
    return query_df, branch_df, hit_table

def main(): 
    """
    Python script to access EvoNAPS database. The script will take the users input and translate it into a query.
    The data will be written into a csv file. 

    USAGE:
    --------
    >>> get_model_parameters.py -m [model of interst] -r [rate of interest] ...

    REQUIRED INPUT:
    --------
    --matrix or -m: str
        Declare model of interest. If you want to search for more than one model, seperate the models with a comma, e.g. JC,GTR.
        Accepted models are: JC, F81, K2P, HKY, TN, TNe, TPM2, TPM2u, TIM2, TIM2e, TPM3, TPM3u, TIM3, TIM3e, K3P, K3Pu, TIM, TIMe, TVM, TVMe, SYM, GTR 
    --rate or -r: str
        Declare model(s) of rate heterogenity. If there is more than one model seperate them with a comma, e.g. G4,I (default is 'E')
        Accepted models of rate heterogenity are: E, I, I+G4, G4, R2, R3, R4, R5, R6, R7, R8, R9, R10

    OPTIONAL INPUT 
    --------
    --table: str
        Declare in which table(s) of the database you want to search (dna_trees (tree) and/or dna_modelparameters (test).
        If no input is provided, a search in both tables 'test,tree' is performed (default is 'tree,test')
        Possible options are: 'tree', 'test', 'test,tree'
    --ic : str
        If you search in the dna_modelparameters table, you can restrict your search to models that fit the alignment 
        significantly well according to the different selection criteria, namely BIC, AIC, AICc, CAIC and ABIC (Default is 'BIC'). 
        If the option is set to 'None', then all model parameter regardless of the model performance will be returned. 
        However, it is highly recommened to restrict the model search to models that fit the alignment well. 
        Options: 'BIC', 'AIC', 'AICc', 'CAIC', 'ABIC', 'None'
    --ic_sig : float
        Should you restrict your search with the 'ic' option, you can set a significance level. In this case, only parameters 
        of models with a weight above the chosen limit will be returned. Default is 0.05. 
    --tree or -t : 
        Enable this option, if you wish to also return the phylogenetic trees in a Newick string format. If you search in the dna_trees table, 
        it will return the ML tree and if you search the dna_modelparameters table, it will return the 'initial' (fastML or parsimony) tree. 
    --branch or -b: 
        Enable this option, if you wish to output branch lengths of the ML tree in a seperate file (only applies to search in dna_trees table).   
    --seq_min : int
        Use this option if you want to restrict your search to alignments with a minimum number of sequences (or taxa) (default is None)
    --seq_max : int
        Use this option if you want to restrict your search to alignments with a maximum number of sequences (or taxa) (default is None)
    --col_min : int
        Use this option if you want to restrict your search to alignments with a minium number of columns (or sites) in your alignment (default is None)
    --col_max : int
        Use this option if you want to restrict your search to alignments with a maximum number of columns (or sites) in your alignment (default is None)
    --output or -o : str
        Declare the prefix of the output csv file that will be generated (default is 'evonaps_query')
    --keep : 
        If you also want to search the database for results obtained by running IQ-Tree 2 with the '--keep-ident' flag, set this 
        parameter to True. Default is True (in this case, only inference results conducted on the (potentially) reduced alignments 
        are shown)
    --source : str
        Restrict the search to alignments from a specific source. Options: PANIDT, OrthoMaM, Lanfear 
        Default is 'None' (no search resistrictions).


    Example
    --------
    In the example below we filter for the parameters of the models GTR+F, GTR+F+I, GTR+F+G4 and restrict 
    the search to alignmnets with at least 50 sequences (or taxa) and to the dna_modelparameters table. 
    We further filter for models that fit the alignment significantly well according to BIC with a 
    limit of 0.1 for the weighted BIC values. Furthermore, the tree used for model evalutation (initial tree)
    will be returned in Newickstring (-t option). 

    >>> get_model_parameters.py -m GTR -r E,I,G4 --table test --seq_min 50 --ic BIC --ic_sig 0.1 -t
    """

    # Default settings
    matrix = 'GTR'
    rate = 'E'
    seq_min = None
    seq_max = None
    col_min = None
    col_max = None
    tables = 'tree,test'
    ic = 'BIC'
    ic_sig = 0.05
    prefix = 'evonaps_query'
    tree = False
    branch = False
    keep = True
    source = None

    # List of valid system arguments
    valid_arguments=['--help', '-h', '--matrix', '-m', '--rate', '-r', '--seq_min', '--seq_max', '--col_min','--col_max', '--table', \
        '--ic', '--output', '-o', '--ic_sig', '--tree', '-t', '--branch', '-b', '--source', '--keep']
    
    # Read in the input provided by the user
    for i in range (len(sys.argv)): 
        if sys.argv[i] == '--help' or sys.argv[i] == '-h': 
            print(main.__doc__)
            sys.exit(5)

        if sys.argv[i] in ['--matrix','-m']: 
            matrix = sys.argv[i+1]
        if sys.argv[i] in ['--rate', '-r']: 
            rate = sys.argv[i+1]
        if sys.argv[i] == '--seq_min': 
            seq_min = sys.argv[i+1]
        if sys.argv[i] == '--seq_max': 
            seq_max = sys.argv[i+1]
        if sys.argv[i] == '--col_min': 
            col_min = sys.argv[i+1]
        if sys.argv[i] == '--col_max': 
            col_max = sys.argv[i+1]
        if sys.argv[i] == '--table': 
            tables = sys.argv[i+1]
        if sys.argv[i] == '--ic': 
            ic = sys.argv[i+1]
        if sys.argv[i] in ['--output','-o']: 
            prefix = sys.argv[i+1]
        if sys.argv[i] == '--ic_sig': 
            ic_sig = sys.argv[i+1]
        if sys.argv[i] in ['--tree', '-t']: 
            tree = True
        if sys.argv[i] in ['--branch', '-b']: 
            branch = True
        if sys.argv[i] in ['--keep']: 
            keep = False       
        if sys.argv[i] in ['--source']: 
            source =  sys.argv[i+1]  
        if sys.argv[i][0] == '-' and sys.argv[i] not in valid_arguments: 
            print('Unknown argument: '+sys.argv[i]+'. Type '+sys.argv[0]+' --help for help.')
            sys.exit(2)

    
    # Print user input into terminal
    print('***Script to parse model parameters from the EvoNAPS database***\n')
    print('Input substitution rate matrix or matrices: ', matrix)
    print('Input model(s) of rate heterogeneity: ', rate)
    print('Search in tables:', tables)
    print('\nRunning query (this might take some time)...')
    
    # Get start time of query
    start=time.time()

    # Create query
    query_df, branch_df, hit_table = fetchParameters(matrix, rate=rate, seq_min=seq_min, seq_max=seq_max, col_min=col_min, col_max=col_max, \
        tables=tables, ic=ic, ic_sig=ic_sig, tree=tree, branch=branch, keep=keep, source=source)
    # Check results
    if query_df is None: 
        sys.exit(5)
    
    # Get end time of query
    end=time.time()
    print('Query took '+str(round((end-start),5))+' seconds.')
    print('\nResults....')
    print(hit_table)

    # Write results into csv file
    query_df.to_csv(prefix+'.csv', index=False, sep=',')
    print('\nQuery results were exported into file '+prefix+'.csv')
    if branch_df is not None: 
        branch_df.to_csv(prefix+'_branches.csv', index=False, sep=',')
        print('Branches were exported into file '+prefix+'_branches.csv')

    # Check the input of the user (actually done within the fetchParameters function) 
    # However, in order to write the actual used input into the log file, the input is checked again.
    matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source \
         = CheckInput(matrix, rate, seq_min, seq_max, col_min, col_max, tables, ic, ic_sig, tree, branch, keep, source)
    
    # Write a log file
    with open (prefix+'.log', 'w') as w: 
        w.write('***Script to parse model parameters from the EvoNAPS database***\n')
        w.write('Author: Franziska Reden\nCreated: March 2023\n')
        w.write('Timestamp: '+str(datetime.datetime.now())+'\n\n')
        w.write('Input substitution rate matrix or matrices: '+str(matrix)+'\n')
        w.write('Input model(s) of rate heterogeneity: '+str(rate)+'\n')
        w.write('Search in tables:'+str(tables)+'\n')
        w.write('Minimum number of sequences in alignment seq_min: '+str(seq_min)+'\n')
        w.write('Maximum number of sequences in alignment seq_max: '+str(seq_max)+'\n')
        w.write('Minimum number of sites in alignment col_min: '+str(col_min)+'\n')
        w.write('Maximum number of sites in alignment col_max: '+str(col_max)+'\n')
        w.write('Retrun Newickstring of tree: '+str(tree)+'\n')
        w.write('Retrun branch lengths of tree: '+str(branch)+'\n\n')
        w.write('Query results were exported into file '+prefix+'.csv\n')
        if branch is True: 
            w.write('Branch lengths were exported into file '+prefix+'_branches.csv\n')
        w.write('\nOverview of esults: \n')

    # Write hit table into log file
    hit_table.to_csv(prefix+'.log', index=False, sep=' ', mode='a')
    print('Log file: '+prefix+'.log')

if __name__ == '__main__': 
    main()
