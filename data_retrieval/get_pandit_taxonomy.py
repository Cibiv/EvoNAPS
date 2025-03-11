
#! /usr/bin/env python3

import os
import pandas as pd
import numpy as np
import sys
from Bio import Entrez

def get_acc_nr(pandit: list, prefix: str) -> None: 
    '''
    Function to get the UniProt and/or GenBank accession number for each sequence 
    of each alignment in the PANDIT database. The acceccion numbers are written 
    into file pandit_accession_numbers.tsv with columns ALI_ID, SEQ_NAME, UniProt, 
    and GenBank.
    
    Input: 
    ------
    - pandit: list, PANDIT database (text, list of lines)
    - prefix: str, name of the output folder 

    Returns:
    ------ 
    - None: writes parsed accession numbers into prefix/pandit_accession_numbers.tsv file.
    '''
    
    with open(os.path.join(prefix, 'pandit_accession_numbers.tsv'), 'w') as w:
        w.write('ALI_ID\tSEQ_NAME\tUniProt\tGenBank')

        for i in range (len(pandit)): 
            if pandit[i][:3] == 'FAM': 
                ali_id = pandit[i].split('FAM  ')[1]
                j = i+1
                while j<len(pandit) and pandit[j][:3] != 'FAM': 
                    if pandit[j][:3] == 'LNK': 
                        data = pandit[j].split('LNK  ')[1].split(':')
                        w.write('\n'+ali_id+'\t'+data[0]+'\t'+data[1]+'\t'+data[2])
                    j+=1
                i=j  

def getEntries(list): 
    '''
    Function that accesses the NCBI protein database to get the entries (in text format)
    for each UniProt accession number given in list. 
    - Input: a list containing UniProt accession numbers
    - Returns: The resulting entries in text format
    '''

    Entrez.email = "franziska.reden@univie.ac.at"
    handle = Entrez.efetch(db="protein", id=list, rettype="gb", retmode="text")
    result = handle.readlines()
    handle.close()

    return result

def filterResults(text: str, results_dict: dict, problem: list, double: dict):
    '''
    Function to parse out all taxonomy IDs from the resulting text file as donwloaded 
    from the NCBI protein database.
    
    INPUT
    ----
    - text: text file (str) containing the downloaded NCBI entry of a list of UniProt IDs
    - results_dict: a dictionary containing previous results. Key is the accession number, 
    entry is the parsed taxonomy ID. 
    - problem: a list containing subsets of results text, where either no 
    accession number or no taxonomy ID was found.
    - double: a dictionary containing all accession numbers for which multiple taxonomy IDs 
    exist. Key is the accession number, entry is a list of taxonomy IDs. 
    Per default: first taxonomy ID encountered in text is added to the results_dict. The 
    remaining taxonomy IDs will be ignored.

    Returns
    ------
    - results_dict
    - problem
    - double
    '''

    # Iterate over lines found in text file 
    for i in range (len(text)):
        # If key word 'LOCUS' has been found, search for accession number and taxonomy ID in the upcoming lines. 
        if 'LOCUS       ' in text[i]: 
            j = i+1
            # Declare variables that will store the accession number and taxonomy ID.
            acc = None 
            tax = None

            # While in one LOCUS block, search for accesion number and taxonomy ID.
            while j<len(text) and 'LOCUS       ' not in text[j]: 
                # Search for key word 'ACCESSION' and store result in variable acc.
                if 'ACCESSION' in text[j]: 
                    acc = text[j].strip('\n').split(' ')[-1].replace(' ', '')
                # Search for key word 'db_xref="taxon' and store result in variable tax.
                if 'db_xref="taxon:' in text[j] and tax is None: 
                    tax = text[j].split('taxon:')[1].strip('\n').strip('"')
                # If variable tax is already filled, store accession number and taxonomy ID in double dict. 
                elif 'db_xref="taxon:' in text[j]: 
                    if acc not in double.keys(): 
                        double[acc] = [tax, text[j].split('taxon:')[1].strip('\n').strip('"')]
                    else: 
                        double.setdefault(acc, []).append(text[j].split('taxon:')[1].strip('\n').strip('"'))
                j+=1

            # If variable acc and tax are both filled, store results in results_dict
            if acc and tax: 
                results_dict[acc] = tax
            # Otherwise, append subset of text to the problem list.
            else:
                problem.append(text[i:j])
                print('problem!')
            i = j-1
    
    return results_dict, problem, double

def main(): 
    '''
    Python3 script to parse out the taxonomy IDs (according to the NCBI database) 
    of all the sequences of the alignments found in the PANDIT database. 
    Returns a DataFrame containing the name of the alignment, the name of the
    sequence and the parsed out taxonomy ID from the GenBank protein database.

    OPTIONS
    -------
    --file: str
        Name and path to the file containing the PANDIT database (as downloaded from 
        https://www.ebi.ac.uk/research/goldman/software/pandit/). Default is: 'Pandit17.0'
    --prefix: str 
        Name of the output file. Default is the current directory. Results will be 
        written into declared folder.

    Results
    -------
    - pandit_taxonomy.tsv: tab separated DataFrame.
        Containing the name of the alignment (ALI_ID), the name of the sequence (SEQ_NAME), the 
        GenBank accession number (UniProt), and the parsed out taxonomy ID (TAX_ID).
        DataFrame is written into 'pandit_taxonomy.tsv' file.
    - 

    USAGE
    -------
    >>> python3 get_pandit_taxonomy.py --file [PATh/TO/DB_FILE/Pandit17.0] --prefix [PATH/TO/OUTPUT_FILE]
    '''

    file='Pandit17.0'
    prefix = ''

    # Read in command line
    for i in range (len(sys.argv)): 
        if sys.argv[i] == '--file': 
            file=sys.argv[i+1]    
        if sys.argv[i] == '--prefix':
            prefix=sys.argv[i+1]
        if sys.argv[i]=='--help': 
            print(main.__doc__)

    print('###Python3 script to parse out the taxonomy IDs (according to the NCBI database) \nof all the sequences of the alignments found in the PANDIT database.###\n')
    
    # Check if file to PANDIT database exists.
    if os.path.exists(file) is False: 
        print('ERROR! File was not found '+file+'. Type --help for help.')
        sys.exit(2)

    if os.path.isfile(os.path.join(prefix, 'pandit_accession_numbers.tsv')) is False: 
        # Open and read in PANDIT database
        print('Read in Pandit database...')
        with open (file) as t: 
            pandit = t.readlines()
            for i in range (len(pandit)): 
                pandit[i] = pandit[i].replace('\n', '')

        # Get list of accessuion numbers: 
        print('Get accession numbers...')
        get_acc_nr(pandit, prefix)

    # Read in DataFrame containing the acceccion numbers of all sequences of the PANDIT alignments
    accession_nr_list = pd.read_csv(os.path.join(prefix, 'pandit_accession_numbers.tsv'), sep='\t')

    # Get unique accession numbers
    unique_ids = accession_nr_list['UniProt'].unique()
    print('Number of unique accession numbers: ', len(unique_ids), '\n')

    # Declare dictionaries and lists that will contain all the parsed results
    res_dic = {}
    problem = []
    double = {}

    # For each subset of 2000 UniProt accession numbers, get the database entry (in text format) 
    # from the NCBI protein database. 
    print('Get NCBI taxonomy IDs for each UniProt accession number (this will take some time)...')
    for limit in range (0, len(unique_ids)-(len(unique_ids)%2000), 2000):

        print('Fetsching taxonomy IDs for UniProt accession nrs: '+str(limit+1)+'-'+str(limit+1999+1)+' ('+str(round((limit+1999)/len(unique_ids)*100, 2))+'%)')
        # Get the NCBI entries of 2000 UniProt IDs
        result = getEntries(unique_ids[limit:limit+1999])

        # Parse out the taxonomy ID for each UniProt ID.
        res_dic, problem, double = filterResults(result, res_dic, problem, double)

    # Write results into output files.
    with open (os.path.join(prefix, 'pandit_taxonomy_ids.tsv'), 'w') as w: 
        w.write('ACC_NR\tTAX_ID\n')
        for key in res_dic.keys(): 
            w.write(key+'\t'+res_dic[key]+'\n')

    with open (os.path.join(prefix, 'pandit_double.tsv'), 'w') as w: 
        w.write('ACC_NR\tTAX_ID\n')
        for key in double.keys(): 
            w.write(key+'\t')
            for entry in double[key]: 
                w.write(entry+',')
            w.write('\n')

    if len(problem)>0: 
        with open (os.path.join(prefix, 'pandit_problem.tsv'), 'w') as w: 
            for prob in problem: 
                w.write(prob+'\n')

    res_dic = pd.read_csv(os.path.join(prefix, 'pandit_taxonomy_ids.tsv'), sep='\t')
    missing = set(res_dic['ACC_NR'].to_list()).symmetric_difference(unique_ids)

    with open (os.path.join(prefix, 'pandit_missing.txt'), 'w') as w: 
        for entry in missing: 
            w.write(entry+'\n')

    res_dic = res_dic.rename(columns = {"ACC_NR":"UniProt"})
    accession_nr_list = accession_nr_list.merge(res_dic, on='UniProt', how='left')
    accession_nr_list['TAX_ID']=accession_nr_list['TAX_ID'].replace({np.NaN:1})
    accession_nr_list['TAX_ID']=accession_nr_list['TAX_ID'].astype(int)

    accession_nr_list.to_csv(os.path.join(prefix, 'pandit_taxonomy.tsv'), sep = '\t', index = False)

    print('\nResults were written into'+str(os.path.join(prefix, 'pandit_taxonomy.tsv'))+' file.')

if __name__=='__main__': 
    main()