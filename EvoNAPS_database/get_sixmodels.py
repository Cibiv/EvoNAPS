#! /usr/bin/env python3

import pandas as pd
import mariadb
import sys

def fetch_sixmodels(user, password, host, database='EvoNAPS', file_name='six_models_bic.csv'): 

    columns = ['ALI_ID', 'EXCLUDED_SEQ', 'KEEP_IDENT', 'MODEL', 'BIC', 'ALPHA']

    # Connect to the Database
    mydb = mariadb.connect(
    host=host,
    user=user,
    password=password, 
    database=database
    )

    mycursor = mydb.cursor()
    query = 'SELECT a.ALI_ID, a.EXCLUDED_SEQ, b.KEEP_IDENT, b.MODEL, b.BASE_MODEL, b.MODEL_RATE_HETEROGENEITY, CAST(b.BIC AS DECIMAL(20,4)) as BIC, CAST(b.ALPHA AS DECIMAL(20,6)) as ALPHA \
    FROM dna_alignments a \
    LEFT JOIN dna_modelparameters b USING (ALI_ID) \
    WHERE b.BASE_MODEL IN \
    (\'JC\', \'F81+F\', \'K2P\', \'TN\', \'HKY+F\', \'GTR+F\') \
    AND b.MODEL_RATE_HETEROGENEITY IN \
    (\'+G4\', \'uniform\');'

    mycursor.execute(query)
    myresults = mycursor.fetchall()

    myresults=pd.DataFrame(myresults, columns=columns)

    for i in range (len(myresults['ALI_ID'])): 
        if myresults['EXCLUDED_SEQ'][i]>0: 
            if myresults['KEEP_IDENT'][i]==0: 
                myresults['KEEP_IDENT'][i]='out'
            else: 
                myresults['KEEP_IDENT'][i]='keep'
        else: 
            myresults['KEEP_IDENT'][i]=None

    myresults.drop(columns='EXCLUDED_SEQ')

    myresults.to_csv(file_name, sep=',', index=False, header=True)
    print('Results were written into file '+file_name+'.')

def main(): 

    file_name='six_models_bic.csv'
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