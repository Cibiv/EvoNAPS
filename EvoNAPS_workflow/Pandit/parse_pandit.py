#! /usr/bin/env python3

import pandas as pd
import sys
import os.path

def FindAli(pandit, j, dic_ali, prefix): 

    name_amino={}
    name_dna={}
    
    for m in range (j, len(pandit)): 

        if pandit[m][:len('FAM')]=='FAM': 
            dic_ali['FAM'].append(pandit[m][len('FAM  '):-1])

        elif pandit[m][:len('DES')]=='DES': 
            dic_ali['DES'].append(pandit[m][len('DES  '):-1])

        elif pandit[m][:len('ANO')]=='ANO': 
            dic_ali['ANO'].append(pandit[m][len('ANO  '):-1])
        
        elif pandit[m][:len('ALN')]=='ALN': 
            dic_ali['ALN'].append(pandit[m][len('ALN  '):-1])

        elif pandit[m][:len('DNO')]=='DNO': 
            dic_ali['DNO'].append(pandit[m][len('DNO  '):-1])   

        elif pandit[m][:len('DLN')]=='DLN': 
            dic_ali['DLN'].append(pandit[m][len('DLN  '):-1])  

        elif pandit[m][:len('NAM')]=='NAM': 
            
            if pandit[m+1][:len('ASQ')]=='ASQ': 
                name_amino.setdefault(pandit[m][len('NAM  '):-1], pandit[m+1][len('ASQ  '):-1])

            if pandit[m+2][:len('DSQ')]=='DSQ': 
                name_dna.setdefault(pandit[m][len('NAM  '):-1], pandit[m+2][len('DSQ  '):-1])                      

        if pandit[m][:len('//')]=='//':
            break
    
    counter=1
    if len(name_amino.keys())>0: 
        prefix_aa = os.path.join(prefix, 'PANDIT-aa')
        with open(os.path.join(prefix_aa, dic_ali['FAM'][-1]+'-aa.fasta'), 'w') as w: 
            for key in name_amino.keys(): 
                w.write('>'+str(key))
                w.write('\n')
                w.write(name_amino[key])
                if counter < len(name_amino.keys()):
                    w.write('\n')
                counter+=1

    counter=1       
    if len(name_dna.keys())>0: 
        prefix_dna = os.path.join(prefix, 'PANDIT-dna')
        with open(os.path.join(prefix_dna, dic_ali['FAM'][-1]+'-dna.fasta'), 'w') as w: 
            for key in name_dna.keys(): 
                w.write('>'+str(key))
                w.write('\n')
                w.write(name_dna[key])
                if counter < len(name_amino.keys()):
                    w.write('\n')
                counter+=1
    
    return dic_ali

def main(): 
    '''
    Python3 script that filters out the alignments of the PANDIT database. 
    The path to and name of the file containing the PANDIT database (as downloaded from https://www.ebi.ac.uk/research/goldman/software/pandit/)
    can be provided on the commandline using the --file option (default is filename 'Pandit17.0' in current directory.)
    The path to and name of the output folder can be indicated using the --prefix option. 

    USAGE
    ------
    python3 parse_pandit.py --file [PATH/TO/FILE/Pandit17.0] --prefix [PATH/TO/OUTPUT/FOLDER/]

    RESULT
    ------
    The DNA alignments in FASTA format will be written into the PANDIT-dna/ folder. 
    The protein alignments in FASTA format will be written into the PANDIT-aa/ folder. 
    Alignment descriptions are written into 'alignment_information.tsv' file (tab seperated).
    '''

    file='Pandit17.0'
    prefix = ''

    for i in range (len(sys.argv)): 
        if sys.argv[i] == '--file': 
            file=sys.argv[i+1]    
        if sys.argv[i] == '--prefix':
            prefix=sys.argv[i+1]

    if os.path.exists(file) is False: 
        print('File was not found '+file+'. Type --help for help.')
        sys.exit(2)
    if os.path.exists(os.path.join(prefix, 'PANDIT-dna')) is False: 
        os.makedirs(os.path.join(prefix, 'PANDIT-dna'))
    if os.path.exists(os.path.join(prefix, 'PANDIT-aa')) is False: 
        os.makedirs(os.path.join(prefix, 'PANDIT-aa'))

    with open (file) as t: 
        pandit=t.readlines()

    dic_ali={'FAM':[],'DES':[], 'ANO':[], 'ALN':[], 'DNO':[], 'DLN':[]}
    for i in range(len(pandit)): 
        if pandit[i][:len('FAM')]=='FAM': 
            dic_ali = FindAli(pandit, i, dic_ali, prefix)

    dic_ali=pd.DataFrame(dic_ali)
    dic_ali.to_csv(os.path.join(prefix, 'alignment_information.tsv'), sep='\t', index=False)

if __name__ == "__main__":
    main()