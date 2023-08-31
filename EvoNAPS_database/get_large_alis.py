#! /usr/bin/env python3

import pandas as pd
import mariadb
import sys

def fetch_sixmodels(user, password, host, database='EvoNAPS', file_name='six_models_bic.csv'): 

    columns = ['ALI_ID', 'FROM_DATABASE', 'SEQUENCES', 'COLUMNS']

    # Connect to the Database
    mydb = mariadb.connector.connect(
    host=host,
    user=user,
    password=password, 
    database=database
    )

    mycursor = mydb.cursor()
    query = 'SELECT ALI_ID, FROM_DATABASE, SEQUENCES, COLUMNS FROM dna_alignments \
    WHERE SEQUENCES>199 AND SEQUENCES<401 \
    ORDER BY COLUMNS ASC;'

    mycursor.execute(query)
    myresults = mycursor.fetchall()

    df_1=pd.DataFrame(myresults, columns=columns)

    query = 'SELECT ALI_ID, FROM_DATABASE, SEQUENCES, COLUMNS FROM dna_alignments \
    WHERE SEQUENCES=116 AND FROM_DATABASE=\'OrthoMaM\' \
    ORDER BY COLUMNS ASC;'

    mycursor.execute(query)
    myresults = mycursor.fetchall()

    df_2 = pd.DataFrame(myresults, columns = columns)

    # Add the results of the query (new_df) to the query_df DataFrame
    final = pd.concat([df_1, df_2])

    final.to_csv(file_name, sep=',', index=False, header=True)
    print('Results were written into file '+file_name+'.')

def main(): 

    file_name='large_alis.csv'

    db_name = 'EvoNAPS'
    user = None
    password = None
    host = None

    for i in range (len(sys.argv)): 
        if sys.argv[i] in [ '--output', '-o']: 
            file_name = sys.argv[i+1] 
        if sys.argv[i] in ['--user', '-u']:
            user = sys.argv[i+1]
        if sys.argv[i] in ['--host']:
            host = sys.argv[i+1]
        if sys.argv[i] in ['--password', '-p']:
            password = sys.argv[i+1]
        if sys.argv[i] in ['--database', '--db']: 
            db_name = sys.ragv[i+1]

    if not user or not password or not host: 
        print('Missing input: name of the user [-u], password [-p] or name of the host server [--host]!')
        sys.exit(2)

    fetch_sixmodels(user, password, host, database=db_name, file_name=file_name)

if __name__ == '__main__': 
    main()