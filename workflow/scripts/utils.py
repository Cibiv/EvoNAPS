from collections import Counter
import numpy as np
import re
import math
from datetime import datetime
import pandas as pd

from classes import ConstantVariabels, Data, Results

def qprint(message, quiet=False):
    '''Prints a message if quiet is False.'''
    if not quiet:
        print(message)

def parse_number(line):
    return re.findall(r"[-+]?[.]?[\d]+(?:,\d\d\d)*[\.]?\d*(?:[eE][-+]?\d+)?", line)

def open_unique_file(file: str) -> dict: 
    '''
    Function that reads in the "phylib" file including only unique sequences created by IQ-Tree 2.
    Input: The name of the file to be read in. 
    Returns: a dictionary with the name of the sequence as key and the sequence as entry
    '''

    seq = {}
    with open (file, encoding="utf-8") as t: 
        phy_file= t.readlines()
    for i in range (1,len(phy_file)): 
        phy_file[i] = phy_file[i].strip('\n')
        phy_file[i] = phy_file[i].split(' ')     
        while '' in phy_file[i]: 
            phy_file[i].remove('')
        seq.setdefault(phy_file[i][0], phy_file[i][1])

    return seq

def get_freq_per_seq(line: str, states: list) -> dict: 
    '''
    Function that calculates the state frequencies in a given sequence.
    Input: a sequence (line), a list with the states to be calculated (with prefix "FREQ_") (states).
    Returns: A dictionary containing the frequencies.  
    '''

    count_states = Counter(line.upper())
    freqs = {}
    sum = 0
    for state in states: 
        freqs.setdefault(state, count_states[state[len('FREQ_'):]])
        sum += count_states[state[len('FREQ_'):]]
    for key in freqs.keys(): 
        freqs[key] = freqs[key]/sum

    freqs['FRAC_WILDCARDS_GAPS'] = 1-sum/len(line)

    return freqs

def caculate_selection_criteria(logL, k, no_col): 
    '''
    Function that calculates the selection criteria (BIC, AIC, AICC) for given input. 
    Input: log Likelihood (logL), number of free parameters (k), number of columns (no_col)
    as well as the global variabel branch_number depicting the number of branches in the tree.
    Returns: AIC, AICC, BIC
    '''
    AIC = -2*logL + 2*k
    AICc = AIC + 2*k*(k + 1) if (no_col - k - 1) == 0 else AIC + 2*k*((k + 1) / (no_col - k - 1))
    BIC = -2*logL + k*math.log(no_col)

    return AIC, AICc, BIC

def check_ci(char:str) -> int: 
    return 1 if char == '+' else 0 
    
def trans_datetime(timeStamp: str) -> datetime:
    '''Transforms the timestamp (input as string) into datetime format.'''
    return datetime.strptime(timeStamp, '%c')

def parse_constant_stats_from_iqtree(iqtree) -> tuple[str,str,str,datetime]:
    '''
    Function that parses the constant statistics from the IQ-Tree log file.
    Input: The log file of the IQ-Tree run.
    Returns: The IQ-Tree version, the alignment ID, the random number seed and the timestamp.
    '''

    iqtree_version, ali_id, random_number_seed, timestamp = None, None, None, None
    iqtree_version = iqtree[0].split(' ')[1]

    for i in range (len(iqtree)):
        if ali_id and random_number_seed and timestamp:
            return iqtree_version, ali_id, random_number_seed, timestamp

        if iqtree[i][:len('Input file name: ')] == 'Input file name: ':
            ali_id = iqtree[i][len('Input file name: '):-1].split('/')[-1]

        if iqtree[i][:len('Random seed number: ')] == 'Random seed number: ':
            random_number_seed = str(iqtree[i][len('Random seed number: '):-1])

        if iqtree[i][:len('Date and time: ')] == 'Date and time: ':
            timestamp = trans_datetime(iqtree[i][len('Date and time: '):-1])
    
    return iqtree_version, ali_id, random_number_seed, timestamp

def check_duplicate_sequences(data:Data, results:Results, j:int, identical:int) -> int:
    '''
    Function that checks for duplicate sequences in the alignment.
    Input: The data object, the results object, the index of the log file and the number of identical sequences.
    Returns: The number of identical sequences.
    '''

    results.seq_para.IDENTICAL_TO = results.seq_para.IDENTICAL_TO.astype(str)
    while data.log[j][:len('Checking for duplicate sequences: done in ')] != 'Checking for duplicate sequences: done in ':
        if 'but kept for subsequent analysis' in data.log[j]:
            seq1 = data.log[j].split('NOTE: ')[1].split(' ')[0]
            seq2 = data.log[j].split('NOTE: ')[1].split(' is identical to ')[1].split(' ')[0]
            indices1 = results.seq_para[results.seq_para['SEQ_NAME'] == seq1].index.values[0]
            indices2 = results.seq_para[results.seq_para['SEQ_NAME'] == seq2].index.values[0]
            ident_seq1 = results.seq_para.at[indices1, 'IDENTICAL_TO']
            ident_seq2 = results.seq_para.at[indices2, 'IDENTICAL_TO']

            results.seq_para.at[indices2, 'IDENTICAL_TO'] = seq1 if ident_seq1 == 'nan' else seq1+','+ident_seq2
            results.seq_para.at[indices1, 'IDENTICAL_TO'] = seq2 if ident_seq1 == 'nan' else seq2+','+ident_seq1

            identical+=1
        j+=1
    
    return identical

def check_removed_sequences(data:Data, results:Results, j:int, identical:int, excluded:int) -> tuple[int,int]:
    '''
    Function that checks for sequences that were removed from the alignment.
    Input: The data object, the results object, the index of the log file, 
    the number of identical sequences and the number of excluded sequences.
    Returns: The number of identical sequences and the number of excluded sequences.
    '''

    while data.log[j][:len('Alignment was printed to ')]!='Alignment was printed to ':
        if 'is ignored but added at the' in data.log[j]: 
            seq1 = data.log[j].split('NOTE: ')[1].split(' ')[0]
            seq2 = data.log[j].split('(identical to ')[1].split(')')[0]
            indices1 = results.seq_para[results.seq_para['SEQ_NAME'] == seq1].index.values[0]
            indices2 = results.seq_para[results.seq_para['SEQ_NAME'] == seq2].index.values[0]
            ident_seq1 = results.seq_para.at[indices1, 'IDENTICAL_TO']
            ident_seq2 = results.seq_para.at[indices2, 'IDENTICAL_TO']
            results.seq_para.at[indices1, 'EXCLUDED'] = 1
            
            results.seq_para.at[indices2, 'IDENTICAL_TO'] = seq1 if ident_seq2 == 'nan' else seq1+','+ident_seq2
            results.seq_para.at[indices1, 'IDENTICAL_TO'] = seq2 if ident_seq1 == 'nan' else seq2+','+ident_seq1

            identical+=1
            excluded+=1
        j+=1

    return identical, excluded

def check_each_sequence(data:Data, results:Results, j:int) -> None:
    '''
    Function that checks each sequence in the alignment.
    Input: The data object, the results object and the index of the log file.
    Returns: None.
    '''

    while data.log[j][:len('****  TOTAL')] != '****  TOTAL':
        seq_line = list(filter(None, data.log[j].split(' ')))

        # Get name (label) of the sequence
        tmp_name = seq_line[1]
        # Get index in the seq_para DataFrame of found sequence label 
        indices = results.seq_para[results.seq_para['SEQ_NAME'] == tmp_name].index.values

        if len(indices) == 1:
            index = indices[0]
            results.seq_para.at[index, 'CHI2_P_VALUE'] = float(seq_line[4][:-2])
            results.seq_para.at[index, 'CHI2_PASSED'] = 1 if seq_line[3] == 'passed' else 0
            
            # Set tax id as found in the tax_file DataFrame (if available)
            if data.tax_file is not None: 
                row = data.tax_file[data.tax_file['SEQ_NAME'] == tmp_name].index.values
                # If there is a hit, add info to DataFrame
                if len(row) >= 1:
                    # Set taxon ID for sequence
                    if pd.isna(data.tax_file.at[row[0], 'TAX_ID']):
                        results.seq_para.at[index, 'TAX_ID'] = int(data.tax_file.at[row[0], 'TAX_ID'])
                        # If TAX_CHECK is non-empty, set it to corresponding number, or 3 otherwise.
                        if pd.isna(data.tax_file.at[row[0], 'TAX_CHECK']):
                            results.seq_para.at[index, 'TAX_CHECK'] = 3
                        else:
                            results.seq_para.at[index, 'TAX_CHECK'] = int(data.tax_file.at[row[0], 'TAX_CHECK'])
                    else: 
                        results.seq_para.at[index, 'TAX_ID'] = int(1)
                        results.seq_para.at[index, 'TAX_CHECK'] = int(0)
                    # If TAX_CHECK is non-empty, set it accordingly.
                    if not pd.isna(data.tax_file.at[row[0], 'ACC_NR']):
                        results.seq_para.at[index, 'TAX_CHECK'] = data.tax_file.at[row[0], 'ACC_NR']
            else: 
                results.seq_para.at[index, 'TAX_ID'] = int(1)
                results.seq_para.at[index, 'TAX_CHECK'] = int(0)

        elif len(indices) > 1: 
            print('Warning: Duplicate sequences with the same name: '+tmp_name+'.')
        j+=1

def parse_ali_parameters_log(data:Data, results:Results) -> tuple[int, int]:
    '''
    Function that parses the alignment parameters from the log file.
    Input: The data object and the results object.
    Returns: The number of identical sequences and the number of excluded sequences.
    '''

    identical = 0
    excluded = 0

    # Update seq_para DataFrame with features regarding the character composition of each sequence.
    # Also update seq_para with the taxonomy IDs (if available)
    # Get sub-df of the taxonomy file, which only contains the sequences of the relevent alignment (ALI_ID)
    if data.tax_file and 'ALI_ID' in data.tax_file.columns: 
        data.tax_file = data.tax_file[data.tax_file['ALI_ID'] == results.constant_stats['ALI_ID'].replace('.fasta', '')]

    for i in range (len(data.log)): 
        # Get metadata:
        if data.log[i][:len('Alignment has')]=='Alignment has':
            numbers = parse_number(data.log[i])
            results.ali_para['TAXA'] = int(numbers[0])
            results.ali_para['SITES'] = int(numbers[1])
            results.number_columns = int(numbers[1])
            results.ali_para['DISTINCT_PATTERNS'] = int(numbers[2])

            numbers = parse_number(data.log[i+1])
            results.ali_para['PARSIMONY_INFORMATIVE_SITES'] = int(numbers[0])
            results.ali_para['SINGLETON_SITES'] = int(numbers[1])
            results.ali_para['CONSTANT_SITES'] = int(numbers[2])

        # Parse through log file until encountering sequence features 
        if data.log[i][:len('Analyzing sequences: done in ')] == 'Analyzing sequences: done in ': 
            check_each_sequence(data, results, i+1)

        if data.log[i][:len('****  TOTAL')] == '****  TOTAL':
            numbers = parse_number(data.log[i])
            results.ali_para['FAILED_CHI2'] = int(numbers[1])
            results.ali_para['FRAC_WILDCARDS_GAPS'] = float(float(numbers[0])/100)

            # Check for duplicate sequences that are being kept
            identical = check_duplicate_sequences(data, results, i+1, identical)

        # Check for sequences that were   
        if data.log[i][:len('Identifying sites to remove: ')] == 'Identifying sites to remove: ': 
            identical, excluded = check_removed_sequences(data, results, i+1, identical, excluded)

    return identical, excluded

def calculate_new_selection_criteria(logL:float, k:int, no_col:int) -> tuple[float,float]:
    '''
    Function that calculates the selection criteria (CAIC, ABIC) for given input.
    Input: log Likelihood (logL), number of free parameters (k), number of columns (no_col)
    Returns: CAIC, ABIC
    ''' 

    CAIC = -2*logL + (math.log(no_col)+1)*k
    ABIC = -2*logL + (math.log((no_col+2)/24))*k

    return CAIC, ABIC

def parse_model_from_string(model_name: str, seq_type:str, constant:ConstantVariabels) -> dict:
    '''
    Function that parses the model name from the string.
    Input: The model name as string, the sequence type (DNA or AA) and the constant variables.
    Returns: A dictionary containing the model name, the frequency type, the base model, 
    the rate heterogeneity and the number of rate categories.
    '''

    base_model = model_name.split('+')[0]
    rate_het = 'uniform'

    freq = 'uniform' if seq_type == 'DNA' else 'model'

    if '+F+' in model_name:
        rate_het = model_name[len(base_model)+2:]
        freq = 'empirical'
    elif '+F' in model_name:
        rate_het = 'uniform'
        freq = 'empirical'
    elif '+' in model_name:
        rate_het = model_name[len(base_model):]

    freq = 'optimized' if '+Fo' in model_name or '+FO' in model_name else freq
    base_model += '+F' if '+F' in model_name else ''

    # Check if we know the number of model parameters...
    model_num_para = ''
    if rate_het in constant.het_num_para:
        model_num_para = constant.het_num_para[rate_het]
        if seq_type == 'DNA':
            if base_model in constant.dna_num_para.keys():
                model_num_para += constant.dna_num_para.get(base_model, 0)
            else: 
                model_num_para = ''
        elif seq_type == 'AA' and '+F' in model_name:
            model_num_para += 19

    number_rate = 0
    if rate_het != 'uniform':
        if '+G' in rate_het:
            number_rate = 4
        elif '+R' in rate_het:
            number_rate = int(rate_het.split('+R')[-1])

    return {'base_model': base_model, 'freq': freq, 'rate_het': rate_het, 'model_num_para': model_num_para, 'number_rate': number_rate}

def parse_model_performance(info_for_model:list, seq_type:str, freqs:dict, constant:ConstantVariabels) -> dict:
    '''
    Function that parses the model performance from the model information.
    Input: The model information as list, the sequence type (DNA or AA), the frequency dictionary and the constant variables.
    Returns: A dictionary containing the model, the frequency type, the base model, the rate heterogeneity,
    the log likelihood, the AIC, the confidence in the AIC, the AIC weight, the AICC, the confidence in the AICC,
    the AICC weight, the BIC, the confidence in the BIC, the BIC weight, the number of rate categories and the number of model parameters.
    ''' 

    model = info_for_model[0]
    info_from_str = parse_model_from_string(model, seq_type, constant)

    temp_dic = {'MODEL': model, 'FREQ_TYPE': info_from_str['freq'], 'BASE_MODEL': info_from_str['base_model'], \
                'RHAS_MODEL': info_from_str['rate_het'], \
        'LOGL': float(info_for_model[1]), \
            'AIC': float(info_for_model[2]), 'CONFIDENCE_AIC': check_ci(info_for_model[3]), \
                'AIC_WEIGHT': float(info_for_model[4]), \
            'AICC': float(info_for_model[5]), 'CONFIDENCE_AICC': check_ci(info_for_model[6]), \
                'AICC_WEIGHT': float(info_for_model[7]), \
                'BIC': float(info_for_model[8]), 'CONFIDENCE_BIC': check_ci(info_for_model[9]), \
                    'BIC_WEIGHT': float(info_for_model[10]), \
                    'NUM_RATE_CAT': info_from_str['number_rate'], \
                        'NUM_MODEL_PARAMETERS': info_from_str['model_num_para']}
    
    if info_from_str['freq'] == 'equal': 
        temp_dic.update({'FREQ_A': 0.25, 'FREQ_C': 0.25, 'FREQ_G': 0.25, 'FREQ_T': 0.25})
    elif info_from_str['freq'] == 'model': 
        temp_dic.update(constant.aa_models[info_from_str['base_model']])
    else: 
        temp_dic.update(freqs)

    return temp_dic

def parse_model_parameters_iqtree(params:list, model_para, constant_stats, constant:ConstantVariabels) -> None:
    '''
    Function that parses the model parameters from the IQ-Tree log file.
    Input: The parameters, a model parameters DataFrame to temporarily store the results, 
    a set of constant statistics, the constant variables object.
    Returns: None.
    ''' 
    
    # Unpack prameters, results
    iqtree = params['iqtree']

    constant_stats['IQTREE_VERSION'], constant_stats['ALI_ID'], constant_stats['RANDOM_SEED'], \
        constant_stats['TIME_STAMP'] = parse_constant_stats_from_iqtree(iqtree)

    # Read through iqtree file. Check each line and parse out relevent information. 
    for i in range (len(iqtree)): 
        if iqtree[i][:len('List of models sorted by ')] == 'List of models sorted by ': 
            j=i+3
            while iqtree[j][:len('AIC, ')] != 'AIC, ': 
                if iqtree[j][:len('WARNING: ')] != 'WARNING: ': 
                    info_for_model = list(filter(None, iqtree[j].strip('\n').split(' ')))
                    if not info_for_model:
                        break
                    
                    # get all relecant information from line
                    temp_dic = parse_model_performance(info_for_model, params['data_type'], params['freq_stats'], constant)

                    # Update dictionart with constants       
                    temp_dic.update({'KEEP_IDENT': params['keep']})    
                    temp_dic.update({'ORIGINAL_ALI': params['original']}) 
                    
                    # Add new line (info of model) to model_para DataFrame
                    model_para.loc[len(model_para)] = temp_dic           
                j+=1

def parse_model_parameters_check(check, model_para:pd.DataFrame, number_columns:int) -> int:
    '''
    Function that parses the model parameters from the IQ-Tree checkpoint file.
    Input: The check file, a model parameters DataFrame to temporarily store the results and the number of columns.
    Returns: The number of branches in the tree.
    ''' 

    # Read through model.gz (checkpoint) file. 
    # Check each line and parse out relevent information to be stored in model_para DataFrame.
    for i in range (len(check)):
        
        # Get name of model and correposning index in results dataframe
        pot_model = check[i].split(':')[0]
        indices = model_para[model_para['MODEL'] == pot_model].index.values

        freq = 'optimized' if any(string in pot_model for string in ['+FO', '+Fo']) else 'not_opt'
            
        if len(indices) == 1: 
            numbers = parse_number(check[i][len(pot_model):])
            free_parameters = float(numbers[1])
            model_para.at[indices[0], 'NUM_FREE_PARAMETERS'] = free_parameters
            
            if pot_model == 'JC' or pot_model == 'WAG': 
                branch_number = int(numbers[1])

            model_para.at[indices[0], 'TREE_LENGTH'] = float(numbers[2])

            model_para.at[indices[0], 'CAIC'], model_para.at[indices[0], 'ABIC'] = \
                calculate_new_selection_criteria(float(model_para.at[indices[0], 'LOGL']), float(free_parameters), \
                                                 float(number_columns))

            if 'Rate parameters:' in check[i]: 
                for rate in ['A-C: ', 'A-G: ', 'A-T: ', 'C-G: ', 'C-T: ', 'G-T: ']: 
                    model_para.at[indices[0], 'RATE_'+rate[0]+rate[2]] = \
                        float(check[i].split(rate)[1].split(' ')[0].strip('\n'))

            if 'Base frequencies:' in check[i] and freq == 'optimized': 
                for base in ['A: ', 'C: ', 'G: ', 'T: ']: 
                    model_para.at[indices[0], 'FREQ_'+base[0]] = \
                        float(check[i].split('Base frequencies: ')[1].split(base)[1].split(' ').strip('\n'))
            
            if 'Proportion of invariable sites: ' in check[i]: 
                model_para.at[indices[0], 'PROP_INVAR'] = \
                    float(check[i].split('Proportion of invariable sites: ')[1].split(' ')[0].strip('\n'))

            if 'Site proportion and rates: ' in check[i]: 
                rate_cat = check[i].split('Site proportion and rates: ')[1].split(')')
                if 'Gamma shape alpha:' in check[i]: 
                    model_para.at[indices[0], 'ALPHA'] = \
                        float(check[i].split('Gamma shape alpha: ')[1].split(' ')[0].strip('\n'))
                    for k in range (len(rate_cat)): 
                        numbers = parse_number(rate_cat[k])
                        if len(numbers) > 1:
                            model_para.at[indices[0], 'PROP_CAT_'+str(k+1)] = float(numbers[1])
                            model_para.at[indices[0], 'REL_RATE_CAT_'+str(k+1)] = float(numbers[0])
                else: 
                    for k in range (len(rate_cat)): 
                        numbers = parse_number(rate_cat[k])
                        if len(numbers) > 1:
                            model_para.at[indices[0], 'PROP_CAT_'+str(k+1)] = float(numbers[0])
                            model_para.at[indices[0], 'REL_RATE_CAT_'+str(k+1)] = float(numbers[1])

        if len(indices) > 1: 
            print('WARNING! Ambigious index for model '+str(pot_model))

    return branch_number

def parse_model_parameters_calculate_ics(model_para) -> None:
    '''
    Function that calculates information criteria scores.
    Input: A model parameters DataFrame that temporarily stores results.
    Returns: None.
    '''

    #Calculate the wheighted CAIC and ABIC
    min_CAIC = min(model_para['CAIC'])
    min_ABIC = min(model_para['ABIC'])

    for i in range (len(model_para['ALI_ID'])): 
        model_para.at[i, 'CAIC_WEIGHT'] = np.exp(-0.5*(model_para['CAIC'][i]-min_CAIC))
        model_para.at[i, 'ABIC_WEIGHT'] = np.exp(-0.5*(model_para['ABIC'][i]-min_ABIC))

    sum_w_CAIC = sum(model_para['CAIC_WEIGHT'])
    sum_w_ABIC = sum(model_para['ABIC_WEIGHT'])

    for i in range (len(model_para['ALI_ID'])): 
        model_para.at[i, 'CAIC_WEIGHT'] = model_para['CAIC_WEIGHT'][i]/sum_w_CAIC
        if model_para.at[i, 'CAIC_WEIGHT'] > 0.05: 
            model_para.at[i, 'CONFIDENCE_CAIC'] = 1
        else: 
            model_para.at[i, 'CONFIDENCE_CAIC'] = 0
        model_para.at[i, 'ABIC_WEIGHT'] = model_para['ABIC_WEIGHT'][i]/sum_w_ABIC
        if model_para.at[i, 'ABIC_WEIGHT'] > 0.05: 
            model_para.at[i, 'CONFIDENCE_ABIC'] = 1
        else: 
            model_para.at[i, 'CONFIDENCE_ABIC'] = 0

def parse_tree_parameters_iqtree(params, tree_stats:dict, constants:ConstantVariabels) -> bool:
    '''
    Function that parses the tree parameters from the IQ-Tree log file.
    Input: The parameters, a tree parameters dictionary to store the results and the constant variables object.
    Returns: True if the tree is rooted, False otherwise.
    '''

    iqtree = params['iqtree']

    for i in range (len(iqtree)):
        if iqtree[i][:len('Best-fit model according to ')] == 'Best-fit model according to ':
            tree_stats['CHOICE_CRITERIUM'] = iqtree[i].split('according to ')[1].split(':')[0]
        if iqtree[i][:len('Model of substitution: ')] == 'Model of substitution: ':
            best_model = iqtree[i][len('Model of substitution: '):-1]

            info_from_str = parse_model_from_string(best_model, params['data_type'], constants) 
            tree_stats['MODEL'] = best_model
            tree_stats['RHAS_MODEL'] = info_from_str['rate_het']
            tree_stats['NUM_RATE_CAT'] = info_from_str['number_rate']
            tree_stats['BASE_MODEL'] = info_from_str['base_model']
            tree_stats['FREQ_TYPE'] = info_from_str['freq']
            tree_stats['NUM_MODEL_PARAMETERS'] = info_from_str['model_num_para']

            if info_from_str['freq'] == 'equal': 
                tree_stats.update({'FREQ_A': 0.25, 'FREQ_C': 0.25, 'FREQ_G': 0.25, 'FREQ_T': 0.25})
            elif info_from_str['freq'] == 'model': 
                tree_stats.update(constants.aa_models[info_from_str['base_model']])
            else: 
                tree_stats.update(params['freqs'])

        if iqtree[i][:len('Rate parameter R:')] == 'Rate parameter R:': 
            j = i+2
            while '-' in iqtree[j]: 
                rate = [iqtree[j].split('-')[0][-1], iqtree[j].split('-')[1][0]] 
                tree_stats['RATE_'+rate[0]+rate[1]] = \
                    float(parse_number(iqtree[j])[0])
                j += 1   

        if iqtree[i][:len('State frequencies: ')] == 'State frequencies: ' and info_from_str['freq'] == 'optimized': 
            j = i+2
            while 'pi(' in iqtree[j]: 
                tree_stats['FREQ_'+iqtree[j].split('pi(')[1].split(')')[0]] = \
                    float(iqtree[j].split(' = ')[1].strip('\n'))

        if iqtree[i][:len('Gamma shape alpha: ')] == 'Gamma shape alpha: ': 
            tree_stats['ALPHA']  = \
                float(parse_number(iqtree[i])[0])

        if iqtree[i][:len(' Category  Relat')] == ' Category  Relat':
            for j in range (1, 11, 1):
                if iqtree[i+j][:len('  ')] == '  ': 
                    numbers = parse_number(iqtree[i+j])
                    if int(numbers[0]) == 0: 
                        tree_stats['PROP_INVAR'] = float(numbers[2])
                    else: 
                        tree_stats['REL_RATE_CAT_'+str(numbers[0])+''] = float(numbers[1])
                        tree_stats['PROP_CAT_'+str(numbers[0])+''] =  float(numbers[2])
                else: 
                    break

        if iqtree[i][:len('Total tree length (sum of branch lengths): ')] == 'Total tree length (sum of branch lengths): ': 
            tree_stats['TREE_LENGTH'] = float(iqtree[i][len('Total tree length (sum of branch lengths): '):-1])
        
        if iqtree[i][:len('Log-likelihood of ')]=='Log-likelihood of ':
            tree_stats['LOGL'] = float(parse_number(iqtree[i])[0])
            tree_stats['UNCONSTRAINED_LOGL'] = float(parse_number(iqtree[i+1])[0])
            tree_stats['NUM_FREE_PARAMETERS'] = int(parse_number(iqtree[i+2])[0])
            tree_stats['AIC'] = float(parse_number(iqtree[i+3])[0])
            tree_stats['AICC'] = float(parse_number(iqtree[i+4])[0])
            tree_stats['BIC'] = float(parse_number(iqtree[i+5])[0]) 
            tree_stats['CAIC'], tree_stats['ABIC'] = \
                calculate_new_selection_criteria(float(tree_stats['LOGL']), float(tree_stats['NUM_FREE_PARAMETERS']), \
                                                 float(params['column_number']))

        if iqtree[i][:len('Tree in newick format:')] == 'Tree in newick format:': 

            newick_string = iqtree[i+2].strip('\n')
            root = False if newick_string[-2:] == ');' else True 
            tree_stats['NEWICK_STRING'] = newick_string

        if iqtree[i][:len('Sum of internal branch lengths: ')] == 'Sum of internal branch lengths: ': 
            tree_stats['SUM_IBL'] = float(iqtree[i].split(': ')[1].split(' ')[0].strip('\n'))

    return root
    
def parse_initial_tree_log(params, tree_stats, constants:ConstantVariabels) -> bool:
    '''
    Function that parses the initial tree from the IQ-Tree log file.
    Input: The parameters, a tree parameters dictionary to store the results and the constant variables object.
    Returns: True if the tree is rooted, False otherwise.
    '''

    log = params['log']

    # Parse through log file to find all infos....
    for i in range (len(log)):
        # Find line with model that was used:
        if log[i][:len('Perform fast likelihood tree search using ')] == 'Perform fast likelihood tree search using ':
            start_model = log[i].split('Perform fast likelihood tree search using ')[1].split(' ')[0]

            if 'GTR' in start_model and 'GTR+F' not in start_model: 
                start_model = start_model.replace('GTR', 'GTR+F')
            if '+G' in start_model and '+G4' not in start_model: 
                start_model = start_model.replace('+G', '+G4')

            info_from_str = parse_model_from_string(start_model, params['data_type'], constants)
            
            tree_stats['MODEL'] = start_model
            tree_stats['RHAS_MODEL'] = info_from_str['rate_het']
            tree_stats['NUM_RATE_CAT'] = info_from_str['number_rate'] 
            tree_stats['BASE_MODEL'] = info_from_str['base_model']
            tree_stats['FREQ_TYPE'] = info_from_str['freq']
            tree_stats['NUM_MODEL_PARAMETERS'] = info_from_str['model_num_para']

            if info_from_str['freq'] == 'equal': 
                tree_stats.update({'FREQ_A': 0.25, 'FREQ_C': 0.25, 'FREQ_G': 0.25, 'FREQ_T': 0.25})
            elif info_from_str['freq'] == 'model' and params['data_type'] == 'AA': 
                tree_stats.update(constants.aa_models[info_from_str['base_model']])
            else: 
                tree_stats.update(params['freqs'])

            j = i+1
            gamma=False
            while log[j][:len('ModelFinder will test up to')] !=  'ModelFinder will test up to': 

                if log[j][:len('Optimal log-likelihood: ')] == 'Optimal log-likelihood: ': 
                    tree_stats['LOGL'] = float(log[j].split('Optimal log-likelihood: ')[1].split(' ')[0])

                if log[j][:len('Rate parameters:  ')] == 'Rate parameters:  ': 
                    for rate in ['A-C: ', 'A-G: ', 'A-T: ', 'C-G: ', 'C-T: ', 'G-T: ']: 
                        tree_stats['RATE_'+rate[0]+rate[2]] = float(log[j].split(rate)[1].split(' ')[0].strip('\n'))

                if log[j][:len('Proportion of invariable sites: ')] == 'Proportion of invariable sites: ': 
                    tree_stats['PROP_INVAR'] = float(log[j].split('Proportion of invariable sites: ')[1].split(' ')[0].strip('\n'))

                if log[j][:len('Gamma shape alpha: ')] == 'Gamma shape alpha: ': 
                    tree_stats['ALPHA'] = float(log[j].split('Gamma shape alpha: ')[1].split(' ')[0].strip('\n'))
                    gamma=True

                if log[j][:len('Site proportion and rates: ')] == 'Site proportion and rates: ':
                    rate_cat = log[j].split('Site proportion and rates: ')[1].split(')')
                    if gamma is True: 
                        for k in range (len(rate_cat)): 
                            numbers = parse_number(rate_cat[k])
                            if len(numbers) > 1:
                                tree_stats['REL_RATE_CAT_'+str(k+1)] = numbers[0]
                                tree_stats['PROP_CAT_'+str(k+1)] = numbers[1]         
                    else:               
                        for k in range (len(rate_cat)): 
                            numbers = parse_number(rate_cat[k])
                            if len(numbers) > 1:
                                tree_stats['REL_RATE_CAT_'+str(k+1)] = numbers[1]
                                tree_stats['PROP_CAT_'+str(k+1)] = numbers[0]
                j+=1

            # Calculate selection cirteria values for model
            try:
                model_num_para = int(tree_stats['NUM_MODEL_PARAMETERS'])
            except ValueError:
                model_num_para = 0
            tree_stats['NUM_FREE_PARAMETERS'] = tree_stats['NUM_BRANCHES'] + model_num_para

            tree_stats['AIC'], tree_stats['AICC'], tree_stats['BIC'] = \
                caculate_selection_criteria(tree_stats['LOGL'], tree_stats['NUM_FREE_PARAMETERS'], params['column_number'],)
            tree_stats['CAIC'], tree_stats['ABIC'] = \
            calculate_new_selection_criteria(float(tree_stats['LOGL']), float(tree_stats['NUM_FREE_PARAMETERS']), \
                                             float(params['column_number']))

        if log[i][:len('initTree: ')] == 'initTree: ': 

            newick_string = log[i].split('initTree: ')[1].strip('\n')

            for key, item in params['name_dict'].items(): 
                newick_string = newick_string.replace(','+str(int(item)-1)+':', ','+key+':')
                newick_string = newick_string.replace('('+str(int(item)-1)+':', '('+key+':')

            if newick_string[-2:] == ');': 
                root =  False
            else: 
                root = True

            tree_stats['NEWICK_STRING'] = newick_string

    return root

def write_newick_file(newick, params, quiet = False) -> str:
    '''
    Function that writes the parsed initial tree into a newick file.
    Input: The newick string, the parameters and a boolean to suppress output.
    Returns: The file name of the newick file.
    '''

    file_name = f'{params['prefix']}-parsed_initialtree.treefile' if params['keep'] == 0 \
        else f'{params['prefix']}-keep_ident_parsed_initialtree.treefile'
        
    with open (file_name, 'w', encoding='utf-8') as w:
        w.write(newick)

    qprint(f'...parsed initial tree was written into file {file_name}.', quiet=quiet)

    return file_name
