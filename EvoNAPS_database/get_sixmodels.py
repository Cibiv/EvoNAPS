#! /usr/bin/env python3

import pandas as pd
import mysql.connector
import sys

def fetch_sixmodels(user='frareden', password='Franzi987', file_name='six_models_bic.csv'): 

    columns = ['ALI_ID', 'EXCLUDED_SEQ', 'KEEP_IDENT', 'MODEL', 'BIC', 'ALPHA']

    # Connect to the Database
    mydb = mysql.connector.connect(
    host="crick",
    user=user,
    password=password, 
    database="fra_db"
    )

    mycursor = mydb.cursor()
    query = 'SELECT a.ALI_ID, a.EXCLUDED_SEQ, b.KEEP_IDENT, b.MODEL, CAST(b.BIC AS DECIMAL(20,4)) as BIC, CAST(b.ALPHA AS DECIMAL(20,6)) as ALPHA \
    FROM dna_alignments a \
    LEFT JOIN dna_modelparameters b USING (ALI_ID) \
    WHERE b.BASE_MODEL NOT IN \
    (\'K3P\', \'K3Pu+F\', \'SYM\', \'TIM+F\', \'TIM2+F\', \'TIM2e\', \'TIM3+F\', \'TIM3e\', \'TIMe\', \'TNe\', \'TPM2\', \'TPM2u+F\', \'TPM3\', \'TPM3u+F\', \'TVM+F\', \'TVMe\', \'TVM+F\') \
    AND b.MODEL_RATE_HETEROGENEITY NOT IN \
    (\'+R2\', \'+R3\', \'+R4\', \'+R5\', \'+R6\', \'+R7\', \'+R8\', \'+R9\', \'+R10\', \'+I\', \'+I+G4\');'

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

    for i in range (len(sys.argv)): 
        if sys.argv[i] in [ '--output', '-o']: 
            file_name = sys.argv[i+1] 

    fetch_sixmodels('frareden', 'Franzi987', file_name=file_name)

if __name__ == '__main__': 
    main()