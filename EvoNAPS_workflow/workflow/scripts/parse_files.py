import numpy as np
import pandas as pd
from Bio import SeqIO

from classes import ConstantVariabels, Data, Results
import utils
from parse_tree import parse_tree


def check_state_freqs(data:Data, results:Results): 
    '''
    Function that calculates the state frequencies in the original alignment file or, should it exist,
    based on the unique.phy file created by IQ-Tree2.
    Furthermore, it reads in all sequences from the original alignment and stores it in the seq_para DataFrame.
    '''

    states = []
    for key in results.seq_para.columns: 
        if key[:len('FREQ_')] == 'FREQ_' : 
            states.append(key)

    # Create dictionaries to store infromation regarding the names and index of the sequences
    results.name_dic = {}
    results.name_dic_unique = {}

    # Read in sequences with SeqIO from the Biopython library.
    if '.phy' in data.ali_file:
        parsed_seq = SeqIO.parse(data.ali_file, 'phylip')
    elif any(susbstring in data.ali_file for susbstring in ['.fasta', '.fa', '.faa']): 
        parsed_seq = SeqIO.parse(data.ali_file, 'fasta')

    # For each sequence, calculate the frequencies. Update seq_para DataFrame with results. 
    # Set name_dic dctionary with the name of the sequence as key and the index as dictionary entry.
    index = 1
    for seq_record in parsed_seq:
        freqs = utils.get_freq_per_seq(str(seq_record.seq).upper(), states)
        freqs.update({'SEQ_INDEX': str(index), 'SEQ_NAME': str(seq_record.id).strip('\n'), 'SEQ': str(seq_record.seq)})
        #results.seq_para = results.seq_para.append(freqs, ignore_index = True)
        results.seq_para.loc[len(results.seq_para)] = freqs
        results.name_dic.setdefault(str(seq_record.id).strip('\n'), str(index))
        index += 1

    # Should a unique sequence file exist, calculate state frequencies based on the sequences in the file. 
    # Otherwise, calculate state frequencies based on original alignment file.
    # The results are stored in the dictionary freq_stats and freq_stats_unique in Results.
    all_seq = ''
    for x in range (len(results.seq_para['SEQ'])): 
        all_seq += results.seq_para['SEQ'][x].upper()
    results.freq_stats = utils.get_freq_per_seq(all_seq, states)

    if data.unique is True: 
        columns = ['SEQ']+states
        seq_para_unique = pd.DataFrame(columns=columns)
        index = 1
        phy_file = utils.open_unique_file(data.prefix+'.uniqueseq.phy')
        for seq in phy_file.keys():
            freqs = utils.get_freq_per_seq(phy_file[seq].upper(), states)
            freqs.update({'SEQ': phy_file[seq].upper()})
            results.name_dic_unique.setdefault(seq, str(index))
            #seq_para_unique = seq_para_unique.append(freqs, ignore_index = True)
            seq_para_unique.loc[len(seq_para_unique)] = freqs
            index += 1

        all_seq = ''
        for x in range (len(seq_para_unique['SEQ'])): 
            all_seq += seq_para_unique['SEQ'][x].upper()

        results.freq_stats_unique = utils.get_freq_per_seq(all_seq, states)
    
    else: 
        results.name_dic_unique = results.name_dic
        results.freq_stats_unique = results.freq_stats        

    results.seq_para['EXCLUDED'] = 0

    results.freq_stats.pop('FRAC_WILDCARDS_GAPS', None)
    results.freq_stats_unique.pop('FRAC_WILDCARDS_GAPS', None)

def parse_ali_parameters(data:Data, results:Results):
    '''
    Function that gathers all information to be stored  
    '''    

    # Helper dictionary that will store "constants" such as the alignment ID (ALI_ID) or timestamp.
    results.constant_stats = {}

    # Read through iqtree file. Check each line and parse out relevent information. 
    results.constant_stats['IQTREE_VERSION'], results.constant_stats['ALI_ID'], results.constant_stats['RANDOM_SEED'], \
        results.constant_stats['TIME_STAMP'] = utils.parse_constant_stats_from_iqtree(data.iqtree)

    # Update ali_para DataFrame
    results.ali_para.loc[0] = {'SEQ_TYPE': results.type, 'ALI_ID': results.constant_stats['ALI_ID'], \
                               'RANDOM_SEED': results.constant_stats['RANDOM_SEED'], \
                                'TIME_STAMP': results.constant_stats['TIME_STAMP'], \
                                'IQTREE_VERSION': results.constant_stats['IQTREE_VERSION']}

    # Read through log file. Check each line and parse out relevent information. 
    identical, excluded = utils.parse_ali_parameters_log(data, results)
    
    results.ali_para['IDENTICAL_SEQ'] = identical
    results.ali_para['EXCLUDED_SEQ'] = excluded
    results.seq_para['ALI_ID'] = results.constant_stats['ALI_ID']

    # Finally, write the results into the corresponding files. 
    #results.write_to_df(results.ali_para, 'ali_para')
    results.write_to_df(results.seq_para, 'seq_para')

def parse_model_parameters(data:Data, results:Results, constant:ConstantVariabels, keep_ident_boolean = 0) -> pd.DataFrame:
    '''
    Function that parses out all parameters of models that have been tested in an iqtree run (default setting or
    with keep_ident option).
    
    Parameters
    ----------
    data : classes.Data
    results : classes.Results
    constants : classes.ConstantVariabels
    keep_ident_boolean : int
        Integer [0,1] used as bool. Is either 0 if tree is parsed from iqtree file that resulted from
        default IQ-Tree2 run or 1 if option 'keep-ident' was enabled.
    
    Returns
    ---------
    model_para : pd.DataFrame
        A dataframe holding all the parameters as parsed from the iqtree and check file.
    '''

    # Set up local model_para DataFrame and constant_stats dictionary
    model_para = pd.DataFrame(columns = results.model_para.columns)
    constant_stats = {}
    params = {'keep': keep_ident_boolean, 'data_type': results.type}

    # Set up local variables depending on keep_ident_boolean
    if keep_ident_boolean == 0: 
        original_ali = 0 if data.unique is True else 1
        params.update({'iqtree': data.iqtree, 'freq_stats': results.freq_stats_unique, \
                  'original': original_ali})
        check = data.check

    elif keep_ident_boolean == 1:
        params.update({'iqtree': data.iqtree, 'freq_stats': results.freq_stats_unique, 
                  'original': 1})
        check = data.check_keep
    
    utils.parse_model_parameters_iqtree(params, model_para, constant_stats, constant)
    branch_number = utils.parse_model_parameters_check(check, model_para, results.number_columns)
    utils.parse_model_parameters_calculate_ics(model_para)
    
    # Update DataFrames with "constants" (such as ALI_ID).
    model_para['ALI_ID'] = constant_stats['ALI_ID']
    model_para['RANDOM_SEED'] = constant_stats['RANDOM_SEED']
    model_para['TIME_STAMP'] = constant_stats['TIME_STAMP']
    model_para['IQTREE_VERSION'] = constant_stats['IQTREE_VERSION']
    
    model_para['NUM_BRANCHES'] = branch_number

    if keep_ident_boolean == 0: 
        results.constant_stats_out = constant_stats
        results.branch_number_out = branch_number

    elif keep_ident_boolean == 1: 
        results.constant_stats_keep = constant_stats
        results.branch_number_keep = branch_number

    return model_para

def parse_all_model_parameters(data:Data, results:Results, constant:ConstantVariabels) -> None:
    '''
    Function that parses out all relevent information from the iqtree file regarding the tested models. 
    Results are stored in Results object and written into correspinding outut file.
    '''

    keep_lst = [0] if data.unique is False else [0,1]
    for keep in keep_lst:
        model_para = parse_model_parameters(data, results, constant, keep_ident_boolean=keep)

        model_para = model_para.reindex(columns=results.model_para.columns)
        results.model_para = model_para if results.model_para.empty else pd.concat([results.model_para, model_para], ignore_index=True)
    
    results.write_to_df(results.model_para, 'model_para')

def parse_tree_parameters(data:Data, results:Results, constants:ConstantVariabels, tree_type:str = 'ml', keep_ident_boolean:int = 0):
    '''
    Function that parses out all relevent information for a tree. Can be initial tree, ml tree, from the 
    default run or the keep-ident run. Results will be parsed from the iqtree file (for ml trees) or 
    the log file (for initial trees). The results are stored in the Results object.

    Parameters
    ----------
    data : classes.Data
    results : classes.Results
    constants : classes.ConstantVariabels
    tree_type : str    
        Declares, which tree to parse, either 'initial' or 'ml' Default is 'ml'.
    keep_ident_boolean : int
        Integer [0,1] used as bool. Is either 0 if tree is parsed from iqtree file that resulted from
        default IQ-Tree2 run or 1 if option 'keep-ident' was enabled.

    Returns
    ---------
    tree_stats : dict
        A dictionary holding all the parameters as parsed from the iqtree or log file.
    root : bool
        Bool that states wheither the tree is rooted or unrooted.
    file_name : str
        Name of the treefile, which holds the Newick string of the tree of interest
    '''

    root = False
    tree_stats = {'KEEP_IDENT': keep_ident_boolean}
    constant_stats = {}
    params = {'data_type': results.type, 'column_number': results.number_columns, \
              'keep': keep_ident_boolean, 'prefix': results.out_prefix}

    # Set already known variables (tree type)
    tree_stats['TREE_TYPE'] = tree_type

    # Prepare parameters for 2 keep_ident cases....
    if keep_ident_boolean == 0:
        constant_stats = results.constant_stats_out 
        tree_stats.update({'NUM_BRANCHES': results.branch_number_out, 'ORIGINAL_ALI': 0 if data.unique is True else 1})
        params.update({'iqtree':data.iqtree, 'log':data.log, \
                       'freqs':results.freq_stats_unique, 'name_dict':results.name_dic_unique})
        mldist = data.mldist

    elif keep_ident_boolean == 1: 
        constant_stats = results.constant_stats_keep
        tree_stats.update({'NUM_BRANCHES': results.branch_number_keep, 'ORIGINAL_ALI': 1})
        params.update({'iqtree':data.iqtree_keep, 'log':data.log_keep, \
                       'freqs':results.freq_stats, 'name_dict':results.name_dic})
        mldist = data.mldist_keep

    # Set constant parameters (already parsed out)
    tree_stats.update({'IQTREE_VERSION':constant_stats['IQTREE_VERSION'], 'ALI_ID': constant_stats['ALI_ID'], \
                       'RANDOM_SEED': constant_stats['RANDOM_SEED'], 'TIME_STAMP': constant_stats['TIME_STAMP']})

    if tree_type == 'initial':
        root = utils.parse_initial_tree_log(params, tree_stats, constants)
        file_name = utils.write_newick_file(tree_stats['NEWICK_STRING'], params, results.quiet)
        return tree_stats, root, file_name
    
    # Parse through IQTree file....
    root = utils.parse_tree_parameters_iqtree(params, tree_stats, constants)    

    # If it is the ML tree, also get stats regrading pairwise distances...      
    tree_stats['DIST_MAX'] = 0
    tree_stats['DIST_MIN'] = float('+inf')
    all_distances=[]

    for i in range (1, len(mldist.columns)): 
        for j in range (i, len(mldist)):
            all_distances.append(mldist[i][j])
            if mldist[i][j] > tree_stats['DIST_MAX']: 
                tree_stats['DIST_MAX'] = mldist[i][j]
            if mldist[i][j] < tree_stats['DIST_MIN']: 
                tree_stats['DIST_MIN'] = mldist[i][j]

    tree_stats['DIST_MEAN'] = np.mean(all_distances)
    tree_stats['DIST_MEDIAN'] = np.median(all_distances)
    tree_stats['DIST_VAR'] = np.var(all_distances)

    if keep_ident_boolean == 0:
        file_name = f'{results.prefix}.treefile'
    else:
        file_name = f'{results.prefix}-keep_ident.treefile'

    return tree_stats, root, file_name

def parse_all_tree_parameters(data:Data, results:Results, constants:ConstantVariabels) -> None:
    '''
    Function to parse out all tree and branch parameters from the files created by IQ-Tree2.
    Results are stored in the Results object and also written to corresponding output files.
    ''' 

    # Get parameters for each tree
    keep_lst = [0] if data.unique is False else [0,1]
    for keep in keep_lst:
        for tree_type in ['initial', 'ml']:
            tree_stats, root, file_name = parse_tree_parameters(data, results, constants, tree_type = tree_type, keep_ident_boolean=keep)
            name_dict = results.name_dic_unique if keep == 0 else results.name_dic
            branch_stats, branch_df = parse_tree(file_name, name_dict, rooted=root)

            branch_df = branch_df.drop('SPLIT', axis=1)
            tree_stats.update(branch_stats)

            # Update branch df with constant stats
            branch_df['ALI_ID'] = tree_stats['ALI_ID']
            branch_df['IQTREE_VERSION'] = tree_stats['IQTREE_VERSION']
            branch_df['RANDOM_SEED'] = tree_stats['RANDOM_SEED']
            branch_df['TIME_STAMP'] = tree_stats['TIME_STAMP']
            branch_df['TREE_TYPE'] = tree_type
                      
            # Update results dataframe with found parameters
            results.tree_para.loc[len(results.tree_para)] = tree_stats

            branch_df = branch_df.reindex(columns=results.branch_para.columns)
            results.branch_para = branch_df if results.branch_para.empty else pd.concat([results.branch_para, branch_df], ignore_index=True)
    
    # Write out parameters into results file...
    results.write_to_df(results.tree_para, 'tree_para')
    results.write_to_df(results.branch_para, 'branch_para')
