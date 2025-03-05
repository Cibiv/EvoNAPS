import argparse
import pathlib
import os
import sys
import mysql.connector as mysql
import pandas as pd
import logging
import numpy as np
from tqdm import tqdm

from update_alignment_taxonomy import get_taxonomy, taxonomic_hierarchy_per_sequence
from update_all_alignments_taxonomy import update_alignment_taxonomy_tables

class Data:

    def __init__(self, db_credentials:pathlib.Path, output:str, quiet = False):
        
        self.read_db_credentials(db_credentials)
        self.log_file = output+'.log'
        self.output = output
        self.quiet = quiet
        self.folder = os.getcwd()

    def merge_taxon_ids(self, ncbi:pathlib.Path):

        qprint(f'Reading in new database...', quiet=self.quiet)
        nodes = pd.read_csv(ncbi, sep='\t')
        nodes = nodes.rename(columns={'tax_id':'TAX_ID', 'parent_tax_id': 'PARENT_TAX_ID_new', 'rank': 'TAX_RANK_new', 'name_txt': 'TAX_NAME_new'})

        qprint(f'Retrieving current database from EvoNAPS...', quiet=self.quiet)
        taxonomy_tbl = get_taxonomy(self.db_config)
        taxonomy_tbl = taxonomy_tbl.reset_index()
        taxonomy_tbl = taxonomy_tbl.rename(columns={'PARENT_TAX_ID': 'PARENT_TAX_ID_old', 'TAX_NAME': 'TAX_NAME_old', 'TAX_RANK':'TAX_RANK_old'})

        qprint(f'Merging old and new database...', quiet=self.quiet)
        self.merged_tax = taxonomy_tbl.merge(nodes, how='outer', on='TAX_ID')

    def read_db_credentials(self, file:pathlib.Path):
        '''Reads in credentials file and returns dictionary holding the credentials.'''

        credentials = {}
        if os.path.exists(file) == False:
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
            "allow_local_infile": True,
            "use_pure": True
        }

    def _compare_row(self, row):
        if int(row['PARENT_TAX_ID_old']) != int(row['PARENT_TAX_ID_new']):
            #print(f'{int(row['PARENT_TAX_ID_old'])}, {int(row['PARENT_TAX_ID_new'])}')
            return False
        if row['TAX_NAME_old'] != row['TAX_NAME_new']:
            #print(f'{row['TAX_NAME_old']}, {row['TAX_NAME_new']}')
            return False
        if row['TAX_RANK_old'] != row['TAX_RANK_new']:
            #print(f'{row['TAX_RANK_old']}, {row['TAX_RANK_new']}')
            return False
        return True

    def compare(self):

        qprint(f'Comparing old and new database...', quiet=self.quiet)
        self.merged_tax['TO_DO'] = 'nothing'

        for row in tqdm(self.merged_tax.itertuples(index=True,name='Pandas'), disable=self.quiet):
            if np.isnan(row.PARENT_TAX_ID_old):
                self.merged_tax.at[row.Index, 'TO_DO'] = 'insert'

            elif not np.isnan(row.PARENT_TAX_ID_new) and self._compare_row(self.merged_tax.loc[row.Index]) is False:
                self.merged_tax.at[row.Index, 'TO_DO'] = 'update'

            elif np.isnan(row.PARENT_TAX_ID_new):
                self.merged_tax.at[row.Index, 'TO_DO'] = 'delete'

def qprint(comment:str, quiet = False) -> None:
    if quiet is False:
        print(comment)
    return

def run_query(data:Data, query, params):

    info = {}
    
    # Configure logging
    logging.basicConfig(filename=data.log_file, level=logging.INFO, 
                        format="%(asctime)s - %(levelname)s - %(message)s")
    
    logging.info(f'Executing query: {query} with parameters: {params}')

    # MySQL connection
    try:
        conn = mysql.connect(**data.db_config)
        conn.autocommit = True
        cursor = conn.cursor(buffered=True)

        logging.info("Successfully connected to the database!")

        # Enable local infile if needed
        #cursor.execute("SET GLOBAL local_infile = 1;")
        logging.info("Successfully set local_infile query!")

        # Execute the query
        if params:
            cursor.execute(query, params)
        else:
            cursor.execute(query)

        logging.info("Successfully executed query!")

        # Log affected rows
        info['affected'] = cursor.rowcount

        # Fetch number of matched rows and warnings
        cursor.execute("SHOW WARNINGS;")
        warnings = cursor.fetchall()
        info['warning'] = len(warnings)

        # Log and print warnings
        if warnings:
            for warning in warnings:
                log_msg = f"{warning}"
                logging.warning(log_msg)
                qprint(log_msg, quiet=data.quiet)

        logging.info(f"Affected Rows: {info['affected']}, \
Number of Warnings: {info['warning']}")

        # Commit changes
        #conn.commit()

    except mysql.Error as err:
        log_msg = f"{err}"
        logging.error(log_msg)
        print(log_msg)
        sys.exit(2)

    finally:
        # Check if cursor and connection exist before closing
        if 'cursor' in locals() and cursor:
            cursor.close()
        if 'conn' in locals() and conn:
            conn.close()

def read_merged_file(merged):

    with open(merged) as t:
        lines = t.readlines()

    for i in range (len(lines)):
        lines[i] = lines[i].split('|')
        for j in range (len(lines[i])):
            lines[i][j] = lines[i][j].strip()
    
    merged_dict = {}
    for i in range (len(lines)):
        merged_dict[int(lines[i][0])] = int(lines[i][1])

    return merged_dict

def check_sequences(data:Data, tax_id, seq_type = 'DNA'):
    
    mydb = mysql.connect(**data.db_config)

    mycursor = mydb.cursor()
    query = f"SELECT ALI_ID FROM {seq_type.lower()}_sequences WHERE TAX_ID={tax_id};"

    mycursor.execute(query)
    myresult = mycursor.fetchall()

    df = pd.DataFrame(myresult, columns = ['ALI_ID'])

    # Reindex taxonomy table to allow for faster lookups.
    return list(df['ALI_ID'])

def insert_new(data:Data, file_name):

    qprint(f'Inserting new entries into the taxonomy database...', quiet=data.quiet)
    query = f"LOAD DATA LOCAL INFILE '{file_name}' IGNORE INTO TABLE taxonomy FIELDS \
TERMINATED BY '\\t' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES (\
TAX_ID, PARENT_TAX_ID, TAX_NAME, TAX_RANK);"
    run_query(data, query, None)

def update_tax(data:Data):

    qprint(f'Updating existing enrties in the taxonomy table....', quiet=data.quiet)
    sub_df = data.merged_tax[data.merged_tax['TO_DO']=='update']
    for row in tqdm(sub_df.itertuples(index=True,name='Pandas'), disable=data.quiet):
        query = f'UPDATE taxonomy SET PARENT_TAX_ID=%s, TAX_NAME=%s, TAX_RANK=%s WHERE TAX_ID=%s;'
        params = (row.PARENT_TAX_ID_new, row.TAX_NAME_new, row.TAX_RANK_new, row.TAX_ID)
        run_query(data, query, params)

def merge_and_check(data:Data):

    qprint(f'Merging old tax_ids in sequences tables...', quiet=data.quiet)
    affected_tax_ids = []
    sub_df = data.merged_tax[data.merged_tax['TO_DO']=='delete']
    for row in tqdm(sub_df.itertuples(index=True,name='Pandas'), disable=data.quiet):
        old_id = row.TAX_ID

        # Check if the tax_id is present in a sequence table
        for seq_type in ['dna', 'aa']:
            seq_df = check_sequences(data, row.TAX_ID, seq_type=seq_type)
            if len(seq_df) > 0:
                # If can be merged, update sequence table
                if old_id in data.merged_dict.keys():
                    query = f'UPDATE {seq_type}_sequences SET TAX_ID=%s WHERE TAX_ID=%s;'
                    params = (data.merged_dict[old_id], old_id)
                    run_query(data, query, params)
                else:
                    if old_id not in affected_tax_ids:
                        affected_tax_ids.append(old_id)

    # Return tax_ids, that need to be further updated
    return affected_tax_ids

def find_tax_id(data:Data, tax_ids:list) -> None:

    qprint(f'Update sequences tables for non-mergable tax_ids...', quiet=data.quiet)
    taxonomy_tbl = get_taxonomy(data.db_config)
    sub_tax = data.merged_tax[data.merged_tax['TO_DO'] != 'delete']
    for tax_id in tax_ids:
        # Get taxonomic hierachy
        hierachy = taxonomic_hierarchy_per_sequence(tax_id, taxonomy_tbl)
        check = False
        index = len(hierachy['TAX_ID'])-1
        # Check hierachy to find a tax_id, which is still in the database
        while index > 0:
            if hierachy['TAX_ID'][index] in sub_tax['TAX_ID'].to_list():
                for seq_type in ['dna', 'aa']:
                    query = f"UPDATE {seq_type.lower()}_sequences SET TAX_ID=%s WHERE TAX_ID=%s;"
                    params = (int(hierachy['TAX_ID'][index]), int(tax_id))
                    run_query(data, query, params)
                    check = True
                break
            index -= 1

        # If none is found, set TAX_ID to 1 and TAX_CHECK to 0.
        if check is False:
            for seq_type in ['dna', 'aa']:
                query = f"UPDATE {seq_type.lower()}_sequences SET TAX_ID=1, TAX_CHECK=0 WHERE TAX_ID=%s;"
                params = (tax_id)
                print(f'WARNING: {tax_id} could not be resolved. Sequence tax_id will be set to 1!')
                run_query(data, query, params)

def delete_tax(data:Data) -> None:

    qprint(f'Truncate alignment_taxonomy tables...', quiet=data.quiet)
    for seq_type in ['dna', 'aa']:
        query = f"TRUNCATE TABLE {seq_type.lower()}_alignments_taxonomy;"
        run_query(data, query, None)

    qprint(f'Delete deprecated tax_ids from the taxonomy table...', quiet=data.quiet)
    sub_df = data.merged_tax[data.merged_tax['TO_DO']=='delete']
    for row in tqdm(sub_df.itertuples(index=True,name='Pandas'), disable=data.quiet):
        old_id = row.TAX_ID
        query = f'DELETE FROM taxonomy where TAX_ID=%s;'
        params = (int(old_id),)
        run_query(data, query, params)

def update_evonpas_taxonomy(data):

    # Comnpare current taxonomy to new one
    # Check how many entries need to be changed
    data.compare()
    print(f'Unchanged: {len(data.merged_tax[data.merged_tax['TO_DO']=='nothing'])}')
    print(f'To be inserted: {len(data.merged_tax[data.merged_tax['TO_DO']=='insert'])}')
    print(f'To be updated: {len(data.merged_tax[data.merged_tax['TO_DO']=='update'])}')
    print(f'To be deleted: {len(data.merged_tax[data.merged_tax['TO_DO']=='delete'])}')
    
    # Write rows to be inserted in a new file
    new_data = data.merged_tax[data.merged_tax['TO_DO']=='insert']
    if not new_data.empty:
        insert_file = f'{data.output}_insert.tsv'
        with open(insert_file, 'w') as w:
            w.write('TAX_ID\tPARENT_TAX_ID\tTAX_NAME\tTAX_RANK\n')
            for idx in new_data.index:
                w.write(f'{new_data.at[idx, 'TAX_ID']}\t')
                w.write(f'{new_data.at[idx, 'PARENT_TAX_ID_new']}\t')
                w.write(f'{new_data.at[idx, 'TAX_NAME_new']}\t')
                w.write(f'{new_data.at[idx, 'TAX_RANK_new']}\n')

        # Insert file into the EvoNAPS database
        insert_new(data, os.path.join(data.folder, insert_file))
    
    # Update taxonomy table in the EvoNAPS database
    update_tax(data)
    # Update the sequences tables in the EvoNAPS database
    affected_tax_ids = merge_and_check(data)
    if len(affected_tax_ids) > 0:
        find_tax_id(data, affected_tax_ids)
    # Finally, delete entries
    # Also, alignments_taxonomy tables will be truncated!
    delete_tax(data)

def update_alignment_taxonomy(data:Data, db_credentials):

    queries = update_alignment_taxonomy_tables(db_credentials, data.output, data.quiet)
    for query in queries:
        run_query(data, query, None)

def main():

    current_dir = os.path.dirname(os.path.abspath(__file__))

    parser = argparse.ArgumentParser(description='**Script to update the EvoNAPS taxonomy table.**')
    
    parser.add_argument('-n', '--ncbi',
                        type = str, 
                        action ='store',
                        required = True,
                        help='Path to and name of the new and up-to date NCBI taxonomy database.')

    parser.add_argument('-m', '--merge',
                        type = str, 
                        action = 'store',
                        required = True,
                        help ='Path to and name of the file containing the merged taxon IDs.')
    
    parser.add_argument('-db', '--db_credentials',
                        type = str,
                        action='store',
                        required = True,
                        default = f'{current_dir}/EvoNAPS_credentials.cnf',
                        help='Option to declare file that contains the credentials for the EvoNAPS database.\
                            Per default script will look for file with name \'EvoNAPS_credentials.cnf\' in the same directory as \
                            this Python script.')
    
    parser.add_argument('-o', '--output',
                        type=str,
                        action='store',
                        default = None,
                        help='Option to declare the prefix of the output log file.')
    
    parser.add_argument('-q', '--quiet',
                        action='store_true',
                        help='Quiet mode will print minimal information.')
    
    parser.add_argument('-a', '--alignment_only',
                        action='store_true',
                        help='Enable this option if you wish to only update the alignments_taxonomy tables.')
    
    args = parser.parse_args()

    if args.output is None:
        args.output = 'update_taxonomy'

    # Read in all the data
    data = Data(args.db_credentials, args.output, quiet = args.quiet)
    
    if not args.alignment_only:
        # Update the EvoNAPS taxonomy table
        data.merged_dict = read_merged_file(args.merge)
        data.merge_taxon_ids(args.ncbi)
        update_evonpas_taxonomy(data)

    update_alignment_taxonomy(data, args.db_credentials)
    return 0

if __name__ == "__main__":
    main()