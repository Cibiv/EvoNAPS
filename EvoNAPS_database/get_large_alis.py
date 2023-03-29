#! /usr/bin/env python3

import pandas as pd
import mysql.connector
import sys

def fetch_sixmodels(user='frareden', password='Franzi987', file_name='six_models_bic.csv'): 

    columns = ['ALI_ID', 'FROM_DATABASE', 'SEQUENCES', 'COLUMNS']

    # Connect to the Database
    mydb = mysql.connector.connect(
    host="crick",
    user=user,
    password=password, 
    database="fra_db"
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

    for i in range (len(sys.argv)): 
        if sys.argv[i] in [ '--output', '-o']: 
            file_name = sys.argv[i+1] 

    fetch_sixmodels('frareden', 'Franzi987', file_name=file_name)

if __name__ == '__main__': 
    main()