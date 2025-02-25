from os import path
import sys
import pandas as pd
import gzip

def qprint(message, quiet=False):
    if not quiet:
        print(message)

class ConstantVariabels:
    """
    ConstantVariables class holds model parameters that stay constant. 
    The parameters are saved in and read from a file (models.txt). 
    The class holds the following dictionaries: 
    - het_num_para: holding the number of parameters of rate heterogeneity models.
    - dna_num_para: holdin gthe number of parameters of DNA subtitution models.
    - aa_models: holding the state frequencies of AA models. 
    """

    def __init__(self, config_folder):
        self.het_num_para = {}
        self.dna_num_para = {}
        self.aa_models = {}

        # Read parameters from file
        file_name = path.join(config_folder, 'models.txt')
        self.__read_model_file__(file_name)

    def __read_model_file__(self, file_name):
        """
        Function to read in model parameters:
        Input: Name of file to be read.
        Output: 3 dictionaries:  
        """

        with open(file_name) as t:
            lines = [line.strip() for line in t]

        for i in range (len(lines)):
            if lines[i] == '# Number of parameters for rate heterogeneity models':
                i+=1
                while True:
                    if i >= len(lines) or lines[i] == '' or lines[0] == '#':
                        break
                    self.het_num_para[lines[i].split(',')[0]] = int(lines[i].split(',')[1])
                    i+=1
            
            if lines[i] == '# Number of parameters for DNA substitution models':
                i+=1
                while True:
                    if i >= len(lines) or lines[i] == '' or lines[0] == '#':
                        break
                    self.dna_num_para[lines[i].split(',')[0]] = int(lines[i].split(',')[1])
                    i+=1

            if lines[i] == '# aa_models':
                freqs = lines[i+1].split(',')[1:]
                i = i+2
                while True:
                    if i >= len(lines) or lines[i] == '' or lines[0] == '#':
                        break
                    
                    model = lines[i].split(',')[0]
                    self.aa_models[model] = {}
                    model_freqs = lines[i].split(',')[1:]

                    for j in range (len(freqs)):
                        self.aa_models[model][freqs[j]] = float(model_freqs[j])
                    i+=1

class Results:

    def __init__(self, prefix, out_prefix, config_folder, quiet = False, type = 'DNA'):

        self.prefix = prefix
        self.out_prefix = out_prefix
        self.config = config_folder
        self.quiet = quiet
        self.type = type
        self.number_columns = None

        # the dataframs fo the filled...
        self.seq_para = None
        self.model_para = None
        self.tree_para = None
        self.branch_para = None
        self.file_name_dict = None

        # Sequence stats
        self.freq_stats = None
        self.freq_stats_unique = None
        self.name_dic = None
        self.name_dic_unique = None

        # Constant variables such as the name of the alignment, IQTree version...
        self.constant_stats = None

        self.constant_stats_out = None
        self.branch_number_out = None
        
        self.constant_stats_keep = None
        self.branch_number_keep = None

    def initialize_df(self):

        self.branch_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', \
                'TREE_TYPE', 'BRANCH_INDEX', 'BRANCH_TYPE', 'BL', 'SPLIT_SIZE', \
                    '1_MIN_PATH', '1_MAX_PATH', '1_MEAN_PATH', '1_MEDIAN_PATH', \
                        '2_MIN_PATH', '2_MAX_PATH', '2_MEAN_PATH', '2_MEDIAN_PATH'])
    
        if self.type == 'DNA':

            self.seq_para = pd.DataFrame(columns = ['ALI_ID', 'SEQ_INDEX', 'SEQ_NAME', 'TAX_ID', 'TAX_CHECK', 'ACC_NR','FRAC_WILDCARDS_GAPS', \
                                                    'CHI2_P_VALUE', 'CHI2_PASSED', 'EXCLUDED', 'IDENTICAL_TO', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'SEQ'])

            self.ali_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'SEQ_TYPE',\
                                                    'TAXA', 'SITES', 'PARSIMONY_INFORMATIVE_SITES', \
                'SINGLETON_SITES', 'CONSTANT_SITES', 'FRAC_WILDCARDS_GAPS', 'DISTINCT_PATTERNS', 'FAILED_CHI2', 'IDENTICAL_SEQ', 'EXCLUDED_SEQ', \
                    'DIST_MIN', 'DIST_MAX', 'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR'])

            self.model_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'KEEP_IDENT', 'ORIGINAL_ALI', 'MODEL', \
                'BASE_MODEL', 'FREQ_TYPE', 'RHAS_MODEL', 'NUM_RATE_CAT', \
                'LOGL', 'AIC', 'AIC_WEIGHT', 'CONFIDENCE_AIC', 'AICC', 'AICC_WEIGHT', 'CONFIDENCE_AICC',  'BIC', 'BIC_WEIGHT', 'CONFIDENCE_BIC', \
                    'CAIC', 'CAIC_WEIGHT', 'CONFIDENCE_CAIC', 'ABIC', 'ABIC_WEIGHT', 'CONFIDENCE_ABIC', \
                        'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', 'TREE_LENGTH', \
                            'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'RATE_AC', 'RATE_AG', 'RATE_AT', 'RATE_CG', 'RATE_CT', 'RATE_GT', \
                                'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                    'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                        'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                            'PROP_CAT_9', 'REL_RATE_CAT_9', 'PROP_CAT_10', 'REL_RATE_CAT_10'])

            self.tree_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'TREE_TYPE', 'CHOICE_CRITERIUM', 'KEEP_IDENT', 'ORIGINAL_ALI', \
                'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'RHAS_MODEL', 'NUM_RATE_CAT', \
                    'LOGL', 'UNCONSTRAINED_LOGL', 'AIC', 'AICC', 'BIC', 'CAIC', 'ABIC', 'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', \
                        'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_C', 'FREQ_G', 'FREQ_T', 'RATE_AC', 'RATE_AG', 'RATE_AT', 'RATE_CG', 'RATE_CT', 'RATE_GT', \
                            'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                    'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                        'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                            'PROP_CAT_9', 'REL_RATE_CAT_9', 'PROP_CAT_10', 'REL_RATE_CAT_10', 'TREE_LENGTH', 'SUM_IBL', 'TREE_DIAMETER', 'DIST_MIN', 'DIST_MAX', \
                                            'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR', \
                                                'BL_MIN', 'BL_MAX', 'BL_MEAN', 'BL_MEDIAN', 'BL_VAR', \
                                                    'IBL_MIN', 'IBL_MAX', 'IBL_MEAN', 'IBL_MEDIAN', 'IBL_VAR', \
                                                        'EBL_MIN', 'EBL_MAX', 'EBL_MEAN', 'EBL_MEDIAN', 'EBL_VAR', \
                                                            'POT_FF_7', 'POT_FF_8', 'POT_FF_9', 'POT_FF_10', 'NEWICK_STRING'])

        elif self.type == 'AA': 

            self.seq_para = pd.DataFrame(columns = ['ALI_ID', 'SEQ_INDEX', 'SEQ_NAME', 'TAX_ID', 'TAX_CHECK', 'ACC_NR','FRAC_WILDCARDS_GAPS', 'CHI2_P_VALUE', \
                'CHI2_PASSED', 'EXCLUDED', 'IDENTICAL_TO', 'FREQ_A', 'FREQ_R','FREQ_N',\
                'FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F',\
                    'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', 'SEQ'])

            self.ali_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'SEQ_TYPE', \
                                                    'TAXA', 'SITES', 'PARSIMONY_INFORMATIVE_SITES', \
                'SINGLETON_SITES', 'CONSTANT_SITES', 'FRAC_WILDCARDS_GAPS', 'DISTINCT_PATTERNS', 'FAILED_CHI2', 'IDENTICAL_SEQ', 'EXCLUDED_SEQ', \
                    'DIST_MIN', 'DIST_MAX', 'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR'])

            self.model_para = pd.DataFrame(columns=['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'KEEP_IDENT', 'ORIGINAL_ALI', \
                'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'RHAS_MODEL', 'NUM_RATE_CAT',  \
                'LOGL', 'AIC', 'AIC_WEIGHT', 'CONFIDENCE_AIC', 'AICC', 'AICC_WEIGHT', 'CONFIDENCE_AICC',  'BIC', 'BIC_WEIGHT', 'CONFIDENCE_BIC', \
                    'CAIC', 'CAIC_WEIGHT', 'CONFIDENCE_CAIC', 'ABIC', 'ABIC_WEIGHT', 'CONFIDENCE_ABIC', \
                        'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', 'TREE_LENGTH', \
                        'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_R','FREQ_N','FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F', \
                            'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', \
                                'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                    'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                        'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                            'PROP_CAT_9', 'REL_RATE_CAT_9', 'PROP_CAT_10', 'REL_RATE_CAT_10'])

            self.tree_para = pd.DataFrame(columns = ['ALI_ID', 'IQTREE_VERSION', 'RANDOM_SEED', 'TIME_STAMP', 'TREE_TYPE', 'CHOICE_CRITERIUM', 'KEEP_IDENT', 'ORIGINAL_ALI', \
                'MODEL', 'BASE_MODEL', 'FREQ_TYPE', 'RHAS_MODEL', 'NUM_RATE_CAT', \
                    'LOGL', 'UNCONSTRAINED_LOGL', 'AIC', 'AICC', 'BIC', 'CAIC', 'ABIC', 'NUM_FREE_PARAMETERS', 'NUM_MODEL_PARAMETERS', 'NUM_BRANCHES', \
                        'PROP_INVAR', 'ALPHA', 'FREQ_A', 'FREQ_R','FREQ_N','FREQ_D','FREQ_C','FREQ_Q','FREQ_E','FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K','FREQ_M','FREQ_F', \
                            'FREQ_P','FREQ_S','FREQ_T','FREQ_W', 'FREQ_Y', 'FREQ_V', \
                                'PROP_CAT_1', 'REL_RATE_CAT_1', 'PROP_CAT_2', 'REL_RATE_CAT_2', \
                                    'PROP_CAT_3', 'REL_RATE_CAT_3', 'PROP_CAT_4', 'REL_RATE_CAT_4', 'PROP_CAT_5', 'REL_RATE_CAT_5', \
                                        'PROP_CAT_6', 'REL_RATE_CAT_6', 'PROP_CAT_7', 'REL_RATE_CAT_7', 'PROP_CAT_8', 'REL_RATE_CAT_8', \
                                            'PROP_CAT_9', 'REL_RATE_CAT_9', 'PROP_CAT_10', 'REL_RATE_CAT_10', 'TREE_LENGTH', 'SUM_IBL', 'TREE_DIAMETER', 'DIST_MIN', 'DIST_MAX', \
                                                'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR', 'BL_MIN', 'BL_MAX', 'BL_MEAN', 'BL_MEDIAN', 'BL_VAR', \
                                                    'IBL_MIN', 'IBL_MAX', 'IBL_MEAN', 'IBL_MEDIAN', 'IBL_VAR', \
                                                        'EBL_MIN', 'EBL_MAX', 'EBL_MEAN', 'EBL_MEDIAN', 'EBL_VAR', \
                                                            'POT_FF_7', 'POT_FF_8', 'POT_FF_9', 'POT_FF_10', 'NEWICK_STRING'])

        self.file_name_dict = {'seq_para': f'{self.out_prefix}_seq_parameters.tsv', \
                               'ali_para': f'{self.out_prefix}_ali_parameters.tsv', \
                               'tree_para': f'{self.out_prefix}_tree_parameters.tsv', \
                                'branch_para': f'{self.out_prefix}_branch_parameters.tsv', \
                                'model_para': f'{self.out_prefix}_model_parameters.tsv'}
        
        self.seq_para.to_csv(self.file_name_dict['seq_para'], encoding='utf-8', index = False, sep = '\t')
        self.ali_para.to_csv(self.file_name_dict['ali_para'], encoding='utf-8', index = False, sep = '\t')
        self.tree_para.to_csv(self.file_name_dict['tree_para'], encoding='utf-8', index = False, sep = '\t')
        self.branch_para.to_csv(self.file_name_dict['branch_para'], encoding='utf-8', index = False, sep = '\t')
        self.model_para.to_csv(self.file_name_dict['model_para'], encoding='utf-8', index = False, sep = '\t')

    def write_to_df(self, df, name): 
        df.to_csv(self.file_name_dict[name], encoding='utf-8', mode = 'a', sep  ='\t', index = False, header = False)
        qprint('...parameters were written into file '+self.file_name_dict[name], self.quiet)

    def load_dfs(self):

        self.seq_para = pd.read_csv(self.file_name_dict['seq_para'], sep='\t')
        self.ali_para = pd.read_csv(self.file_name_dict['ali_para'], sep='\t')
        self.tree_para = pd.read_csv(self.file_name_dict['tree_para'], sep='\t')
        self.branch_para = pd.read_csv(self.file_name_dict['branch_para'], sep='\t')
        self.model_para = pd.read_csv(self.file_name_dict['model_para'], sep='\t')

    def update_dfs(self):
        
        row = self.tree_para[(self.tree_para['ORIGINAL_ALI'] == 1) & (self.tree_para['TREE_TYPE'] == 'ml')].iloc[0]
        column_names = ['DIST_MIN', 'DIST_MAX', 'DIST_MEAN', 'DIST_MEDIAN', 'DIST_VAR']
        for name in column_names:
            self.ali_para[name] = row[name]

        self.write_to_df(self.ali_para, 'ali_para')

        seq_para = pd.read_csv(self.file_name_dict['seq_para'], sep = '\t', encoding='utf-8')
        seq_para.fillna({'IDENTICAL_TO': ''}, inplace=True)
        seq_para.to_csv(self.file_name_dict['seq_para'], encoding='utf-8', sep  ='\t', index = False)

    def round_dfs(self):

        self.load_dfs()

        if self.type == 'dna':
            rounding_guide = pd.read_csv(path.join(self.config, 'rounded_columns_dna.csv'), dtype={'COLUMN_TYPE': int})
        else:
            rounding_guide = pd.read_csv(path.join(self.config, 'rounded_columns_aa.csv'), dtype={'COLUMN_TYPE': int})

        sub_df = rounding_guide[rounding_guide['TABLE_NAME'] == 'ali_para']
        rounding_dict = dict(zip(sub_df['COLUMN_NAME'], sub_df['COLUMN_TYPE']))
        
        self.ali_para = self.ali_para.round(rounding_dict)
        self.ali_para.to_csv(self.file_name_dict['ali_para'], encoding='utf-8', sep  ='\t', index = False)

        sub_df = rounding_guide[rounding_guide['TABLE_NAME'] == 'branch_para']
        rounding_dict = dict(zip(sub_df['COLUMN_NAME'], sub_df['COLUMN_TYPE']))
        self.branch_para = self.branch_para.round(rounding_dict)
        self.branch_para.to_csv(self.file_name_dict['branch_para'], encoding='utf-8', sep  ='\t', index = False)

        sub_df = rounding_guide[rounding_guide['TABLE_NAME'] == 'model_para']
        rounding_dict = dict(zip(sub_df['COLUMN_NAME'], sub_df['COLUMN_TYPE']))
        self.model_para = self.model_para.round(rounding_dict)
        self.model_para.to_csv(self.file_name_dict['model_para'], encoding='utf-8', sep  ='\t', index = False)

        sub_df = rounding_guide[rounding_guide['TABLE_NAME'] == 'seq_para']
        rounding_dict = dict(zip(sub_df['COLUMN_NAME'], sub_df['COLUMN_TYPE']))
        self.seq_para = self.seq_para.round(rounding_dict)
        self.seq_para.to_csv(self.file_name_dict['seq_para'], encoding='utf-8', sep  ='\t', index = False)

        sub_df = rounding_guide[rounding_guide['TABLE_NAME'] == 'tree_para']
        rounding_dict = dict(zip(sub_df['COLUMN_NAME'], sub_df['COLUMN_TYPE']))
        self.tree_para = self.tree_para.round(rounding_dict)
        self.tree_para.to_csv(self.file_name_dict['tree_para'], encoding='utf-8', sep  ='\t', index = False)

        return

class Data: 

    def __init__(self, prefix, quiet = False, ali_file = None, tax_file = None):

        self.prefix = prefix

        self.initial = False
        self.unique = False
        self.unique_ctrl = True
        self.quiet = quiet
        
        # If specified...
        self.ali_file = ali_file
        self.tax_file = tax_file

        # Files containing all the data...
        self.iqtree = None
        self.log = None
        self.mldist = None
        self.check = None

        self.initial_iqtree = None

        # Files containing all the data if --keep-ident was run on alignment
        self.iqtree_keep = None
        self.log_keep = None
        self.mldist_keep = None
        self.check_keep = None

    def check_files(self):
        '''Check if all relevent files with the given prefix can be found. Should one of the files be missing, the program is being exited.'''

        ctrl = 0

        if path.exists(self.ali_file) == False:
            print(f'ERROR: Could not find input file {self.ali_file}!')
            ctrl = 1
        qprint(f'...file {self.ali_file} has been detected', quiet = self.quiet)

        for file in ['.iqtree', '.log', '.mldist', '.model.gz', '.treefile']:

            if path.exists(self.prefix+file) == False:
                print(f'ERROR: Could not find input file {self.prefix+file}!')
                ctrl = 1
            qprint(f'...file {self.prefix+file} has been detected', quiet = self.quiet)

        if path.exists(f'{self.prefix}-initialtree.iqtree') == True and path.exists(self.prefix+'-initialtree.treefile') == True: 
            qprint(f'...file {self.prefix}-initialtree.iqtree has been detected', quiet = self.quiet)
            qprint(f'...file {self.prefix}-initialtree.treefile has been detected', quiet = self.quiet)
            self.initial = True

        if path.exists(f'{self.prefix}.uniqueseq.phy') == True:
            qprint(f'...file {self.prefix}.uniqueseq.phy has been detected', quiet = self.quiet)
            self.unique = True

            for file in ['-keep_ident.iqtree', '-keep_ident.log', '-keep_ident.mldist', '-keep_ident.model.gz', '-keep_ident.treefile']: 

                if path.exists(self.prefix+file) == False:
                    print(f'WARNING: Could not find file {self.prefix+file}.') 
                    self.unique_ctrl = False
                qprint(f'...file {self.prefix+file} has been detected', quiet = self.quiet)
        
        if self.tax_file and path.exists(self.tax_file) == False:
            print(f'WARNING: Could not find specified taxonomy file {self.tax_file}.')
            ctrl = 1
        elif self.tax_file:
            qprint(f'...file {self.tax_file} has been detected', quiet = self.quiet)

        if ctrl != 0:
            sys.exit(2)

    def open_files(self):

        with open(self.prefix+'.iqtree', encoding='utf-8') as t:
            self.iqtree = t.readlines()

        with open(self.prefix+'.log', encoding="utf-8") as t:
            self.log = t.readlines()

        self.mldist = pd.read_csv(self.prefix+'.mldist', encoding='utf-8', sep=' ', \
                                  skipinitialspace = True, skiprows = 1, header = None)
        self.mldist.pop(self.mldist.columns[-1])

        with gzip.open(self.prefix+'.model.gz') as t:
            self.check = [x.decode('utf8').strip() for x in t.readlines()]

        if self.initial is True:
            with open(self.prefix+'-initialtree.iqtree', encoding="utf-8") as t:
                self.initial_iqtree = t.readlines()

        if self.unique is True and self.unique_ctrl is True:
            with open(self.prefix+'-keep_ident.iqtree', encoding="utf-8") as t:
                self.iqtree_keep = t.readlines()

            with open(self.prefix+'-keep_ident.log', encoding="utf-8") as t:
                self.log_keep = t.readlines()

            self.mldist_keep = pd.read_csv(self.prefix+'-keep_ident.mldist', encoding="utf-8", sep=' ', \
                                           skipinitialspace = True, skiprows = 1, header = None)
            self.mldist_keep.pop(self.mldist_keep.columns[-1])

            with gzip.open(self.prefix+'-keep_ident.model.gz') as t:
                self.check_keep = [x.decode('utf8').strip() for x in t.readlines()]
        
        if self.tax_file:
            self.tax_file = pd.read_csv(self.tax_file, encoding='utf-8', usecols=[0,1,2,3], \
                                    comment="#", header=None, names=['SEQ_NAME', 'TAX_ID', 'TAX_CHECK', 'ACC_NR'])

    def check_ali_type(self):
        '''
        Function that checks if the underlying alignment that has been run through the workflow is a DNA or protein alignment.
        It will update the variable "type".
        '''
        
        type = None
        
        # Check the log file to get the sequence type of the alignment
        for i in range (len(self.log)):
            # Check if sequqence type was decalred in command (--seqtype)
            if self.log[i][:len('Command: ')] == 'Command: ':
                if '--seqtype' in self.log[i]:
                    if self.log[i].split('--seqtype ')[1][:len('AA')] == 'AA':
                        type = 'AA'
                    elif self.log[i].split('--seqtype ')[1][:len('DNA')] == 'DNA':
                        type = 'DNA'

                # Check if iqtree was run with a set tree topology
                if 'initialtree' in self.log[i]:
                    self.initial = True  
                # Return, if sequence type was found
                if type:
                    return type
            
            # If seqtype was not declared, check what IQ-Tree2 assumes is contained in the alignment.
            if self.log[i][:len('Alignment most likely contains ')] == 'Alignment most likely contains ':
                if 'DNA' in self.log[i]:
                    type = 'DNA'
                elif 'protein' in self.og[i]:
                    type = 'AA'
        
        qprint(f'Alignment type is {type}', quiet=self.quiet)
        
        return type
        