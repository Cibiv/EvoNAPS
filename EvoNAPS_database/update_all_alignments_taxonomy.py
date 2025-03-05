import pathlib
from os import path
import sys
import mysql.connector as mysql
import pandas as pd
import json
from tqdm import tqdm
import os

from update_alignment_taxonomy import get_taxonomy, get_lca, taxonomic_hierarchy_per_sequence

class Data:

    def __init__(self, db_config, output, quiet = False):
        self.read_db_credentials(db_config)
        self.output = output
        self.quiet = quiet
        self.nodes = get_taxonomy(self.db_config)
        
    def read_db_credentials(self, file:pathlib.Path):
        '''Reads in credentials file and returns dictionary holding the credentials.'''

        credentials = {}
        if path.exists(file) == False:
            print(f'ERROR: Could not find db credentials file: {file}!')
            sys.exit(2)

        with open(file, 'r') as t:
            lines = [line.strip().split('=') for line in t]

        for i in range (len(lines)):
            credentials[lines[i][0]] = lines[i][1]

        for key in ['host', 'user', 'password', 'database']:
            if key not in credentials.keys():
                print(f'Credential {key} is missing form credential file {file}')
                sys.exit(2)

        self.db_config = {
            "host": credentials['host'],
            "user": credentials['user'],
            "password": credentials['password'],
            "database": credentials['database'], 
            "allow_local_infile": True 
        }

def get_all_seq(db_config, seq_type = 'dna'):

    mydb = mysql.connect(**db_config)

    mycursor = mydb.cursor()
    query = f"SELECT TAX_ID FROM {seq_type.lower()}_sequences GROUP BY TAX_ID;"

    mycursor.execute(query)
    myresult = mycursor.fetchall()

    unique_tax_ids = pd.DataFrame(myresult, columns = ['TAX_ID'])

    return unique_tax_ids['TAX_ID'].to_list()

def get_all_alis(db_config, seq_type = 'dna'):
    
    mydb = mysql.connect(**db_config)

    mycursor = mydb.cursor()
    query = f"SELECT ALI_ID, TAX_ID, TAX_CHECK FROM {seq_type.lower()}_sequences;"

    mycursor.execute(query)
    myresult = mycursor.fetchall()

    ali_ids = pd.DataFrame(myresult, columns = ['ALI_ID', 'TAX_ID', 'TAX_CHECK'])

    # Reindex taxonomy table to allow for faster lookups.
    return ali_ids

def update_tables(data:Data):

    # Read in taxonomy table from JSON file.
    file = "taxonomy_table.json"
    with open(file, 'r') as f:
        taxonomy_table = json.load(f)

    tax_rank_dict = taxonomy_table[0]
    new_row = taxonomy_table[1]
    
    # Initilize the dictionary to store the taxonomic hierarchy per sequence.
    taxonomy_per_seq = {}
    queries = []

    current_foler = os.getcwd()
    for seq_type in ['dna', 'aa']:
        # declare output_file name and create inport query
        output_file_name = f'{data.output}_{seq_type}_alignments_taxonomy.tsv'

        query = f"LOAD DATA LOCAL INFILE '{os.path.join(current_foler, output_file_name)}' \
IGNORE INTO TABLE {seq_type}_alignments_taxonomy FIELDS TERMINATED BY '\\t' OPTIONALLY ENCLOSED BY \
'\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES ("
        for key in new_row.keys():
            if key in ["ALI_ID", "TAX_RESOLVED", "LCA_TAX_ID", "LCA_RANK_NR", "LCA_RANK_NAME"]:	
                query += f"{key}, "
            else:
                query += f"@{key}, "
        query = query[:-2] + ") SET "
        for key in new_row.keys():
            if key not in ["ALI_ID", "TAX_RESOLVED", "LCA_TAX_ID", "LCA_RANK_NR", "LCA_RANK_NAME"]:
                query += f"{key} = NULLIF(@{key}, ''), "
        query = query[:-2] + ";"
        queries.append(query)

        if os.path.exists(output_file_name):
            continue

        # Write header of output file
        with open(f'{data.output}_{seq_type}_alignments_taxonomy.tsv', 'w') as w:
            line = ''
            for col in new_row.keys():
                line += f'{col}\t'
            line = f'{line[:-1]}\n'
            w.write(line)

        results_df = pd.DataFrame(columns = new_row.keys())

        # Fetch all data from the EvoNAPS database. 
        # Namely, the name fo each alignments and its tax_ids
        # And the all unique tax_ids in the EvoNAPS database.
        alis = get_all_alis(data.db_config, seq_type)
        seqs = get_all_seq(data.db_config, seq_type)
        
        # Get the taxoomic hierachy for each tax ID in the EvoNAPS database
        for seq in seqs:
            taxonomy_per_seq[seq] = taxonomic_hierarchy_per_sequence(seq, data.nodes)

        tmp = 0
        # For each alignment, get the lca.
        for ali in tqdm(alis['ALI_ID'].unique()):
        #for ali in alis['ALI_ID'].unique():
            tmp += 1
            sub_dict = {}
            sub_df = alis[alis['ALI_ID'] == ali]
            row_to_be_imported = new_row.copy()
            row_to_be_imported['ALI_ID'] = ali

            # Check if any taxon ID is unresolved, set TAX_RESOLVED for alignment accordingly.
            row_to_be_imported['TAX_RESOLVED'] = 0 if (sub_df['TAX_CHECK'] == 0).any() else 1
            sub_df = sub_df[sub_df['TAX_CHECK'] != 0]
            
            if len(sub_df) == 0 or (sub_df['TAX_ID'] == 1).any():
                row_to_be_imported['LCA_TAX_ID'] = 1
                row_to_be_imported['LCA_RANK_NR'] = 0
                row_to_be_imported['LCA_RANK_NAME'] = 'root'
            else:
                for idx in sub_df.index:
                    seq = sub_df.at[idx, 'TAX_ID']
                    sub_dict[seq] = taxonomy_per_seq[seq]
                row_to_be_imported = get_lca(row_to_be_imported, sub_dict, tax_rank_dict)
            
            results_df.loc[len(results_df)] = row_to_be_imported

            if tmp % 1000 == 0:
                results_df.to_csv(output_file_name, mode='a', sep='\t', header=False, index=False)
                results_df = pd.DataFrame(columns = new_row.keys())

        results_df.to_csv(output_file_name, mode='a', sep='\t', header=False, index=False)
    
    return queries

def update_alignment_taxonomy_tables(db_config, output, quiet):

    data = Data(db_config, output, quiet=quiet)
    queries = update_tables(data)
    return queries

def main():
    output = 'alignments_taxonomy'
    db_config = 'EvoNAPS_credentials.cnf'
    nodes = '/home/frareden/.ncbi_tax/nodes.tsv'

    data = Data(db_config, nodes, output)
    update_tables(data)

if __name__ == "__main__":
    main()