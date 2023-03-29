#! /usr/bin/env python3

import pandas as pd
import sys

def FindAli(pandit, j, dic_ali): 

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
        with open('PANDIT-aa/'+dic_ali['FAM'][-1]+'-aa.fasta', 'w') as w: 
            for key in name_amino.keys(): 
                w.write('>'+str(key))
                w.write('\n')
                w.write(name_amino[key])
                if counter < len(name_amino.keys()):
                    w.write('\n')
                counter+=1

    counter=1       
    if len(name_dna.keys())>0: 
        with open('PANDIT-dna/'+str(dic_ali['FAM'][-1])+'-dna.fasta', 'w') as w: 
            for key in name_dna.keys(): 
                w.write('>'+str(key))
                w.write('\n')
                w.write(name_dna[key])
                if counter < len(name_amino.keys()):
                    w.write('\n')
                counter+=1

def main(): 

    if len(sys.argv)>1: 
        file=sys.argv[1]    
    else: 
        file='Pandit17.0'

    with open (file) as t: 
        pandit=t.readlines()

    dic_ali={'FAM':[],'DES':[], 'ANO':[], 'ALN':[], 'DNO':[], 'DLN':[]}
    for i in range(len(pandit)): 
        if pandit[i][:len('FAM')]=='FAM': 
            FindAli(pandit, i, dic_ali)

    dic_ali=pd.DataFrame(dic_ali)
    dic_ali.to_csv('alignment_information.tsv', sep='\t', index=False)

if __name__ == "__main__":
    main()