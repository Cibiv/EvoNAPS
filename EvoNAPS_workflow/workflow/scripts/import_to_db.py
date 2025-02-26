import argparse
import pathlib
from os import path
import sys
import mysql.connector as mysql
import pandas as pd
import logging

from update_alignment_taxonomy import get_alignment_taxonomy

class Data:
        
    def __init__(self, prefix, credentials, import_commands,
                 info = None, pythia = None, 
                 output = None, quiet = False):

        self.prefix = prefix
        self.output = output
        self.quiet = quiet
        self.pythia = pythia

        self.read_db_credentials(credentials)
        self.read_import_commands(import_commands)
        self.info = self.read_info_file(info) if info else {}
        self.check_files()
        self.check_ali_type()

        self.cleanup_ali = f"DELETE FROM {self.seq_type.lower()}_alignments WHERE ALI_ID='{self.ali_id}'"

    def read_import_commands(self, file:pathlib.Path):

        self.import_commands = {}
        if path.exists(file) == False:
            print(f'ERROR: Could not find import file: {file}!')
            sys.exit(2)

        with open(file, 'r') as t:
            lines = t.readlines()

        for i in range (len(lines)):
            if lines[i][:2] == '--':
                table_name = lines[i][2:].strip()
                self.import_commands[table_name] = ''
                j = i+1
                while j < len(lines) and lines[j] != '\n':
                    self.import_commands[table_name] += lines[j].replace('\n', ' ')
                    j+=1

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

    def read_info_file(self, file) -> dict:

        info = {}
        if path.exists(file) == False:
            print(f'ERROR: Could not find declared info file: {file}!')
            sys.exit(2)

        with open(file, 'r') as t:
            lines = [line.strip().split('=') for line in t if not line.startswith("#") and line.strip() != '']

        for i in range (len(lines)):
            info[lines[i][0]] = lines[i][1]

        return info
    
    def check_ali_type(self):

        ali_para = pd.read_csv(self.file_dict['alignments'], sep='\t', encoding='utf-8')

        self.seq_type = ali_para.at[0, 'SEQ_TYPE']
        self.ali_id = self.info['ALI_ID'] if 'ALI_ID' in self.info.keys() else ali_para.at[0, 'ALI_ID'].replace('.fasta', '')

    def check_files(self):
        
        # Order is important! Will dictate in which order to insert into the databse
        # Alignment file needs to be first!
        self.file_dict = {'alignments': f'{self.prefix}_ali_parameters.tsv', \
            'sequences': f'{self.prefix}_seq_parameters.tsv', \
            'modelparameters': f'{self.prefix}_model_parameters.tsv', \
            'trees': f'{self.prefix}_tree_parameters.tsv', \
            'branches': f'{self.prefix}_branch_parameters.tsv'
            }

        for _, item in self.file_dict.items():
            if path.exists(item) == False:
                print(f'ERROR: Could not find file {item}!')
                sys.exit(2)

def qprint(line, quiet = False):
    if quiet is False:
        print(line)

def run_query(data:Data, query, params, cleanup = True):

    # Configure logging
    logging.basicConfig(filename=data.output, level=logging.INFO, 
                        format="%(asctime)s - %(levelname)s - %(message)s")
    
    logging.info(query)

    # MySQL connection
    try:
        conn = mysql.connect(**data.db_config)
        cursor = conn.cursor()

        # Enable local infile if needed
        cursor.execute("SET GLOBAL local_infile = 1;")

        # Execute the query
        cursor.execute(query, params)
        
        # Fetch warnings
        cursor.execute("SHOW WARNINGS;")
        warnings = cursor.fetchall()
        
        # Log and print warnings
        if warnings:
            for warning in warnings:
                log_msg = f"{warning}"
                logging.warning(log_msg)
                qprint(log_msg, quiet=data.quiet)

        # Commit changes
        conn.commit()

    except mysql.Error as err:
        log_msg = f"{err}"
        logging.error(log_msg)
        print(log_msg)
        if cleanup is True:
            run_query(data, data.cleanup_ali, cleanup = False)   
        sys.exit(2)

    finally:
        # Check if cursor and connection exist before closing
        if 'cursor' in locals() and cursor:
            cursor.close()
        if 'conn' in locals() and conn:
            conn.close()

def import_data(data:Data):

    for key, file in data.file_dict.items():
        table = f'{data.seq_type.lower()}_{key}'
        query = data.import_commands[table].replace("'FILE_NAME'", "%s").replace("'custom_ali_id'", f"%s")
        params = [file, data.ali_id]
        
        if key == 'alignments':
            query = query.replace("'SOURCE'", f"%s")
            if 'FROM_DATABASE' in data.info.keys():
                source = data.info['FROM_DATABASE']
            else:
                source = 'misc' 
            params.append(source)

        qprint(f'Inserting file {file} into table {table}....', quiet=data.quiet)
        run_query(data, query, tuple(params))

def update_data(data:Data):

    if data.pythia:
        query = f"UPDATE {data.seq_type.lower()}_alignments SET PYTHIA_SCORE=%s where ALI_ID=%s;"
        params = (data.pythia, data.ali_id)
        qprint(f'Updating {data.seq_type.lower()}_alignments with Pythia score...', quiet=data.quiet)
        run_query(data, query, params, cleanup=True)

    # Update alignments table with data stored in the info table
    if all(key in data.info.keys() for key in ["STUDY_ID", "STUDY_URL", "CITATION"]):
        query = f"INSERT IGNORE INTO studies (STUDY_ID, STUDY_URL, YEAR, CITATION) VALUES (%s, %s, %s, %s);"
        params = (data.info['STUDY_ID'], data.info['STUDY_URL'], data.info['YEAR'], data.info['CITATION'])
        qprint(f'Inserting study {data.info['STUDY_ID']} into table studies....', quiet=data.quiet)
        run_query(data, query, params)

    if 'STUDY_ID' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET STUDY_ID=%s where ALI_ID=%s;"
        params = (data.info['STUDY_ID'], data.ali_id)
        qprint(f'Updating {data.seq_type.lower()}_alignments with study ID {data.info['STUDY_ID']}...', quiet=data.quiet)
        run_query(data, query, params)

    if 'DATA_URL' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET DATA_URL=%s where ALI_ID=%s;"
        params = (data.info['DATA_URL'], data.ali_id)
        qprint(f'Updating {data.seq_type.lower()}_alignments with data URL {data.info['DATA_URL']}...', quiet=data.quiet)
        run_query(data, query, params)

    if 'DESCRIPTION' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET DESCRIPTION=%s where ALI_ID=%s;"
        params = (data.info['DESCRIPTION'], data.ali_id)
        qprint(f'Updating {data.seq_type.lower()}_alignments with DESCRIPTION...', quiet=data.quiet)
        run_query(data, query, params)

    insert_query, params = get_alignment_taxonomy(data.ali_id, data.seq_type, data.db_config)
    qprint(f'Updating {data.seq_type.lower()}_alignments_taxonomy...', quiet=data.quiet)
    print(insert_query)
    run_query(data, insert_query, params, cleanup=False)

def main():

    current_dir = path.dirname(path.abspath(__file__))

    parser = argparse.ArgumentParser(description='**Script to import files into EvoNAPS database.**')
    
    parser.add_argument('-p', '--prefix',
                        type=str, action='store',
                        required = True,
                        help='Mandatory argument. Declares the path to and prefix the \
                            files to be imported into the EvoNAPS database.')
    
    parser.add_argument('-db', '--db_credentials',
                        type=pathlib.Path,
                        action='store',
                        default=f'{current_dir}/EvoNAPS_credentials.cnf',
                        help='Option to declare file that contains the credentials for the EvoNAPS database.\
                            Per default script will look for file with name \'EvoNAPS_credentials.cnf\' in the same directory as \
                            this Python script.')
    
    parser.add_argument('-c', '--import_commands',
                        type=pathlib.Path,
                        action='store',
                        default=f'{current_dir}/EvoNAPS_import_statements.sql',
                        help='Option to declare file that contains the import commands. \
                            Per default script will look for file with name \
                            \'EvoNAPS_import_statements.sql\' in the same directory as \
                            this Python script.')
    
    parser.add_argument('-o', '--output',
                        type=str, action='store',
                        help='Option to declare the prefix of the output log file. Default will be prefix from --prefix.')
    
    parser.add_argument('-i', '--info',
                        type=pathlib.Path,
                        action='store',
                        help='Option to declare a file that contains additional information regarding the alignment \
                            that is to be imported into the EvoNAPS database.')
    
    parser.add_argument('-q', '--quiet',
                        action='store_true',
                        help='Quiet mode will print minimal information.')
    
    parser.add_argument('-py', '--pythia',
                        type=str,
                        action='store',
                        default=None,
                        help='Option to provide the Pythia difficulty score for the alignment.')
    
    args = parser.parse_args()

    qprint(parser.description, quiet = args.quiet)

    if not args.output:
        args.output = f'{args.prefix}_importlog.txt'

    data = Data(args.prefix, args.db_credentials, args.import_commands,
                info = args.info, pythia=args.pythia, 
                output = args.output, quiet = args.quiet)
    
    import_data(data)
    update_data(data)
    
    return 0

if __name__ == '__main__':
    main()
