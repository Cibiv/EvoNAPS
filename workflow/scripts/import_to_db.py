import argparse
import pathlib
from os import path
import sys
import mysql.connector as mysql
import pandas as pd
import logging

from update_alignment_taxonomy import get_alignment_taxonomy

class Data:
    '''Class to hold all necessary information for the import process.'''
        
    def __init__(self, prefix, credentials, import_commands, tables,
                 info = None, pythia = None, 
                 output = None, quiet = False):

        self.prefix = prefix
        output = prefix if output is None else output
        self.log_file = f'{output}_import.log'
        self.target_file = f'{output}_summary.txt'
        self.quiet = quiet
        self.pythia = pythia
        self.tables = tables

        self.read_db_credentials(credentials)
        self.read_import_commands(import_commands)
        self.info = self.read_info_file(info) if info else {}
        self.check_files()
        self.check_ali_type()

        self.cleanup_ali = f"DELETE FROM {self.seq_type.lower()}_alignments WHERE ALI_ID='{self.ali_id}'"

    def read_import_commands(self, file:pathlib.Path):
        '''Reads in the import commands file and stores the commands in a dictionary.'''

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
        '''Reads in the info file and returns a dictionary holding the information.'''

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
        '''Reads in the alignment results file and determines the type of alignment (DNA or AA).'''	

        ali_para = pd.read_csv(self.file_dict['alignments'], sep='\t', encoding='utf-8')

        self.seq_type = ali_para.at[0, 'SEQ_TYPE']
        self.ali_id = self.info['ALI_ID'] if 'ALI_ID' in self.info.keys() else ali_para.at[0, 'ALI_ID'].replace('.fasta', '')

    def check_files(self):
        '''Check if all files are present and store the absolute path of each file.'''
        
        # Order is important! Will dictate in which order to insert into the databse
        # Alignment file needs to be first!
        self.file_dict = {'alignments': f'{self.prefix}_ali_parameters.tsv', \
            'sequences': f'{self.prefix}_seq_parameters.tsv', \
            'modelparameters': f'{self.prefix}_model_parameters.tsv', \
            'trees': f'{self.prefix}_tree_parameters.tsv', \
            'branches': f'{self.prefix}_branch_parameters.tsv'
            }

        for key, item in self.file_dict.items():
            if path.exists(item) == False:
                print(f'ERROR: Could not find file {item}!')
                sys.exit(2)
            else:
                self.file_dict[key] = path.abspath(item)

def qprint(line, quiet = False):
    '''Function to print a line if quiet mode is not activated.'''
    if quiet is False:
        print(line)

def run_query(data:Data, query, params, cleanup = False) -> int:
    '''Function to run a query on the EvoNAPS database.'''

    duplicate_tmp = 0

    # Configure logging
    logging.basicConfig(filename=data.log_file, level=logging.INFO, 
                        format="%(asctime)s - %(levelname)s - %(message)s")
    
    logging.info(query)

    # MySQL connection
    try:
        conn = mysql.connect(**data.db_config)
        cursor = conn.cursor()

        # Check if local_infile is enabled
        cursor.execute("SHOW VARIABLES LIKE 'local_infile';")
        results = cursor.fetchall()
        if results[0][1] == 'OFF':
            try:
                cursor.execute("SET GLOBAL local_infile = 1;")
                conn.commit()
            except mysql.Error as err:
                log_msg = f"{err}"
                logging.error(log_msg)
                print(log_msg)
                sys.exit(2)

        # Execute the query
        if params:
            cursor.execute(query, params)
        else:
            cursor.execute(query)
        
        # Fetch warnings
        cursor.execute("SHOW WARNINGS;")
        warnings = cursor.fetchall()
        
        # Log and print warnings
        if warnings:
            for warning in warnings:
                log_msg = f"{warning}"
                logging.warning(log_msg)
                qprint(log_msg, quiet=data.quiet)
                if log_msg[:len("('Warning', 1062, \"Duplicate entry")] == "('Warning', 1062, \"Duplicate entry":
                    duplicate_tmp = 1

        # Commit changes
        conn.commit()

    except mysql.Error as err:
        log_msg = f"{err}"
        logging.error(log_msg)
        print(log_msg)
        if cleanup is True:
            _ = run_query(data, data.cleanup_ali, cleanup = False)
            with open(data.target_file, 'a') as w:
                w.write(f'WARNING: Alignment with ALI_ID {data.ali_id} was removed from database!')   
        sys.exit(2)

    finally:
        # Check if cursor and connection exist before closing
        if 'cursor' in locals() and cursor:
            cursor.close()
        if 'conn' in locals() and conn:
            conn.close()

    return duplicate_tmp

def import_data(data:Data) -> int:
    '''Function to import the new alignment into the EvoNAPS database.'''

    print(data.ali_id)

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

        message = f'Inserting file {file} into table {table}....'
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
                w.write(f'{message}\n')
        
        # Run query
        if key == 'alignments':
            check_tmp = run_query(data, query, tuple(params), cleanup=False)
        else:
            check_tmp = run_query(data, query, tuple(params), cleanup=True)

        # Check if there was a duplicate warning.
        # Stop inserts if there was and return.
        if check_tmp == 1 and key == 'alignments':
            message = f'Alignment with ID {data.ali_id} already exists in the database! \
You can manually set an alignment ID using the info file: \
simply set ALI_ID=unique_name in the info file. The info file can be provided to the script using \
the --info option. Use --help for more information.'
            qprint(message, data.quiet)
            with open(data.target_file, 'a') as w:
                w.write(f'{message}\n')
            return 1
        
    return 0

def update_data(data:Data) -> None:
    '''Function to update the EvoNAPS database with additional information for the target alignment.'''

    # Update alignments table with Pythia score, delete alignment if not possible.
    if data.pythia:
        query = f"UPDATE {data.seq_type.lower()}_alignments SET PYTHIA_SCORE=%s where ALI_ID=%s;"
        params = (data.pythia, data.ali_id)
        message = f'Updating {data.seq_type.lower()}_alignments with Pythia score...'
        
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
            w.write(f'{message}\n') 
        _ = run_query(data, query, params, cleanup=True)

    # Update alignments table with data stored in the info table
    if all(key in data.info.keys() for key in ["STUDY_ID", "STUDY_URL", "CITATION", "YEAR"]):
        query = f"INSERT IGNORE INTO studies (STUDY_ID, STUDY_URL, YEAR, CITATION) VALUES (%s, %s, %s, %s);"
        params = (data.info['STUDY_ID'], data.info['STUDY_URL'], data.info['YEAR'], data.info['CITATION'])

        message = f'Inserting study {data.info['STUDY_ID']} into table studies....'
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
            w.write(f'{message}\n')
        _ = run_query(data, query, params)

    if 'STUDY_ID' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET STUDY_ID=%s where ALI_ID=%s;"
        params = (data.info['STUDY_ID'], data.ali_id)
        
        message = f'Updating {data.seq_type.lower()}_alignments with study ID {data.info['STUDY_ID']}...' 
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
            w.write(f'{message}\n') 

        _ = run_query(data, query, params)

    if 'DATA_URL' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET DATA_URL=%s where ALI_ID=%s;"
        params = (data.info['DATA_URL'], data.ali_id)

        message = f'Updating {data.seq_type.lower()}_alignments with data URL {data.info['DATA_URL']}...'
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
            w.write(f'{message}\n') 

        _ = run_query(data, query, params)

    if 'DESCRIPTION' in data.info.keys():
        query = f"UPDATE {data.seq_type.lower()}_alignments SET DESCRIPTION=%s where ALI_ID=%s;"
        params = (data.info['DESCRIPTION'], data.ali_id)
        
        message = f'Updating {data.seq_type.lower()}_alignments with DESCRIPTION...'
        qprint(message, quiet=data.quiet)
        with open(data.target_file, 'a') as w:
            w.write(f'{message}\n') 

        _ = run_query(data, query, params)

    insert_query, params = get_alignment_taxonomy(data.ali_id, data.seq_type, data.db_config, data.tables)

    # Calculate the LCA of the alignment and update the corresponding alignments_taxonomy table.
    message = f'Updating {data.seq_type.lower()}_alignments_taxonomy...'
    qprint(message, quiet=data.quiet)
    with open(data.target_file, 'a') as w:
        w.write(f'{message}\n') 

    _ = run_query(data, insert_query, params, cleanup=False)

def main():

    parser = argparse.ArgumentParser(description='**Script to import files into EvoNAPS database.**')
    
    parser.add_argument('-p', '--prefix',
                        type=str, action='store',
                        required = True,
                        help='Mandatory argument. Declares the path to and prefix the \
                            files to be imported into the EvoNAPS database.')
    
    parser.add_argument('-db', '--db_credentials',
                        type=pathlib.Path,
                        action='store',
                        default=f'../../config/EvoNAPS_credentials.cnf',
                        help='Option to declare file that contains the credentials for the EvoNAPS database.\
                            Per default script will look for file with name \'EvoNAPS_credentials.cnf\' in the \
                            folder ../../config.')
    
    parser.add_argument('-t', '--tables',
                        type=pathlib.Path,
                        action='store',
                        default=f'../../config/taxonomy_table.json',
                        help='Option to declare the path to and name of the file containing the column \
                            names of the alignments_taxonomy tables.\
                            Per default script will look for file with name \'taxonomy_table.json\' in the \
                            folder ../../config.')
    
    parser.add_argument('-c', '--import_commands',
                        type=pathlib.Path,
                        action='store',
                        default=f'../../config/EvoNAPS_import_statements.sql',
                        help='Option to declare file that contains the import commands. \
                            Per default script will look for file with name \
                            \'EvoNAPS_import_statements.sql\' in the \
                            folder ../../config.')
    
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
        args.output = f'{args.prefix}'

    # Initilize data object (holds all neccessary information.)
    data = Data(args.prefix, args.db_credentials, args.import_commands, args.tables,
                info = args.info, pythia = args.pythia, 
                output = args.output, quiet = args.quiet)
    
    # Import date
    dup_check = import_data(data)
    # If not successful (duplicate entry), return. 
    if dup_check == 1:
        return 0
    # Otherwise, further update entry
    update_data(data)
    
    return 0

if __name__ == '__main__':
    main()
