import pandas as pd
import numpy as np
import sys
import urllib
import urllib.request
import os
from socket import timeout
from bs4 import BeautifulSoup
import string
import re
import time

def download_nexml_file(id: str, url: str, output_file_name: str, failure: list) -> list:
    '''Function to get NEXML file from TreeBase server and writes
    it into local file. Input is the matrix ID, the name of the 
    output file, a timeout limit and a list of IDs where a failure occurs.
    Returns the (potentially updated) failure list.
    '''

    # Try to download file, write downloaded information into file.
    # Except if timeout occurs, in that case append ID to timeout list.
    try: 
        webf = urllib.request.urlopen(url, timeout=timeout_limit)
        txt = webf.read()
        txt = txt.decode("utf-8")
        with open (output_file_name, 'w', encoding="utf-8") as w: 
            w.write(txt)
    except timeout: 
        print(id, 'timeout')
        failure.append([id, 'timeout_'+str(timeout_limit)])
    except urllib.error.HTTPError as err:
        print(id, err)
        failure.append([id, err])
        if err.code == 404: 
            print('Sleeping for 60s...')
            time.sleep(60)
    except Exception as x: 
        print(id, x)
        failure.append([id, x])
            
    # Return timeout list
    return failure

def get_nexml_file(ids: list, url: str, folder: str): 
    '''
    Download matrix nexml files from the TreeBASE database.
    
    Input: 
    - ids: list of IDs to be downloaded
    - url: starting purl link (e.g., 'http://purl.org/phylo/treebase/phylows/matrix/TB2:' for matrices)
    - folder: name of the nexml file should be written into.
    '''

    failure = []
    output_stream = sys.stdout
    remaining_ids = []
    
    # Check how many files are still missing.
    for id in ids:

        filename = os.path.join(folder, id+'.nexml')
        if os.path.exists(filename) is False:
            remaining_ids.append([id, filename])

    print('Number of NEXML files to be downloaded: '+str(len(remaining_ids)))

    count_index = 1
    for file in remaining_ids: 

        output_stream.write('%d %s\r' % (count_index, id))
        output_stream.flush()

        failure = download_nexml_file(file[0], url+file[0]+'?format=nexml', file[1], failure)
                
        count_index += 1

    return failure

def check_matrix_xml_file(file_name: str) -> list: 
    '''
    Function to check the content of the downloaded NEXML file. It parses out all sequences
    as well as their names (labels) and the taxonomy ID, should there exist one. 

    Input is the path to and name of the file. Output is a dictionary (at position 0) and 
    a list (at position 1). 

    The returned dictionary holds the labels of the sequences as keys. The dictionary entry contains the taxonomy IDs 
    at position 0 (or 'None' should there exist none) and the sequence at position 1.
    The returned list contains the specified character set as found in the NEXML file 
    (e.g., A,C,G,T,... for DNA sequences). 
    '''

    # Declare output seq dictionary
    # Declare output character list
    seq_dict = {}
    char_list = []

    # Try to oen file, should there be a problem, return status cannot read.
    with open(file_name, 'r') as f:
        try: 
            data = f.read()
        except: 
            return seq_dict, 'cannot read'

    # Read as xml file
    nexml_file = BeautifulSoup(data, "xml")

    # Get all otus, character tags and states
    otus = nexml_file.find_all('otu')
    character = nexml_file.find_all('characters')
    states = nexml_file.find_all('states')

    # Iterate over all otu tags in otus
    for otu in otus: 
        id = otu.get('id')
        seq_dict.setdefault(id, []).append(otu.get('label'))
        meta = otu.find_all('meta')
        check = 0
        # Get the taxonomy ID, should there exist one
        for me in meta: 
            href = me.get('href')
            if href and 'taxonomy' in href: 
                seq_dict.setdefault(id, []).append(href.split('/')[-1])
                check = 1
        # Append None to seq_dict if no taxonomy ID was found. 
        if check == 0: 
            seq_dict.setdefault(id, []).append(None)

    # Get all sequences as stores in characters -> matrix -> rows.
    for char in character: 
        for ma in char.find_all('matrix'): 
            for row in ma.find_all('row'): 
                id = row.get('otu')
                # Stor sequence in seq_dict
                for seq in row.find_all('seq'): 
                    seq_dict.setdefault(id, []).append(seq.contents[0])

    # Get all states as found in the NEXML file, store them in the char_list
    for state in states: 
        tmp_list = []
        for certain in state.find_all('state'):
            tmp_list.append(certain.get('symbol'))
        char_list.append(tmp_list)
        tmp_list = []
        for uncertain in state.find_all('uncertain_state_set'): 
            tmp_list.append(uncertain.get('symbol'))
        char_list.append(tmp_list)

    # Retrun sequence dictionary and list.
    return seq_dict, char_list

def check_seq(seq:str) -> bool: 
    '''Function to check if the input sequence contains at least one significant character (returns True)
    or only ambigious characters such as 'N','X','_' or '?'  (returns False).'''
    for i in range (len(seq)): 
        if seq[i] not in ['?', 'N', '-', 'X']:
            return True
    return False

def check_char_seq(seq:str, allowed_symbols:list) -> bool: 
    '''Function to check if a sequence (given as first input) contains any characters that are not conform 
    with the FASTA-format (allowed characters are declared in list given as second input). 
    Should the sequence contain at least one non-conformative character, the function returns False. Returns True otherwise.'''
    for i in range (len(seq)): 
        if seq[i] not in allowed_symbols: 
            return False 
    return True

def create_fasta(id: str, char_list: list, seq_dict: dict, transform_dict: dict, allowed_symbols: list, folder: str) -> str: 
    '''
    Function to write the parsed information from the NEXML file into a FASTA file. 
    
    The required inut for the function is: 
    - the name of the matrix ID (id)
    - the list of characters 
    - the dictionary containing the sequences
    - a dictionary containing the information for transforming ambigious characters (e.g., AG->R)
    - a list of allowed symbols (FASTA format conform)
    - path to and name of the folder (folder) the FASTA file will be written into.
    
    Returns a string indicating the status. Possible status reports: 
    - 'status done' indicates that the the sequences were successfully written into an alignment file (in FASTA format). 
    Additionally, the taxonomy IDs for each sequence (should there exist one, None otherwise) was written (appended) into the file 
    'prefix/taxid.tsv'.
    - 'status seqchar' indicates that there were characters in the sequences that are not FASTA format conform. 
    Accordingly, no alignment file was created.
    - 'status length' indicates that the sequences are not all of the same length.
    Accordingly, no alignment file was created. 
    - 'status short' indicates that the alignment is too short (less than 10 characters). 
    Accordingly, no alignment file was created.
    '''

    # Check if gap is '.' instead of '-'
    gap = None
    if '.' in char_list[1]: 
        gap = '.'

    # Declare dictionary for transformed sequences and taxon_ids
    fasta_seq = {}
    taxon_dic = {}

    # Iterate over original seq_dict dictionary        
    for seq_key in seq_dict.keys(): 

        # For each sequence we have a label (sequence name) and potentially a taxonomy ID
        label = seq_dict[seq_key][0]
        tax_id = seq_dict[seq_key][1]

        # Declare the characters that are allowed in the label.
        allowed_letters = list(string.ascii_uppercase)+list(string.ascii_lowercase)
        for i in ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_', '/', '-'): 
            allowed_letters.append(i)

        # Iterate over letters in label name. 
        # Should an ambigious character (not in allowed_letters list) appear,
        # swap it with an underscore. 
        for i in range (len(label)): 
            if label[i] not in allowed_letters: 
                label = label.replace(label[i], '_')
        label = re.sub('\s+', '_', label)
        while label[-1] in ('_', '/', '-') and len(label)>1: 
            label = label[:-1]
        # Finally, replace potential double underscores with a single one     
        label = label.replace('__', '_')

        # Check if a sequence for the label actually exists.
        if len(seq_dict[seq_key])>2:
            seq = seq_dict[seq_key][2]

            # Replace ambigious characters as sometimes declared in {} brackets
            # with FASTA format conformative characters as decalred in transform_dict.
            for key in transform_dict.keys():
                seq = seq.replace('{'+key+'}', transform_dict[key])       
            if gap: 
                seq = seq.replace(gap, '-')
            # Replace 'null' as found in some protein sequences with missing '?' character.
            seq = seq.replace('null', '?')

            # Check if all characters in the sequence are conformative with the allowed symbols.
            # If not return status 'char_check' (failes character check)
            if check_char_seq(seq, allowed_symbols) is False: 
                return 'char_check'
            
            # Check if sequence contains actual characters (as opposed to only ambigious characters)
            # If the sequence passed the test, write the sequence into fasta_seq dictionary.
            # Also, should a tax_id exist for the sequence, write it into taxon_dic
            if check_seq(seq) is True:
                fasta_seq[label] = seq
                if tax_id: 
                    taxon_dic[label] = tax_id
                else: 
                    taxon_dic[label] = np.nan
    
    # Check if the length of all sequences are the same, otherwise return status 'vary_length'. 
    # Return status 'min_length' if length of the alignment is under 10.
    check_length = None
    for key in fasta_seq.keys(): 
        if check_length and check_length != len(fasta_seq[key]): 
            return 'vary_length'
        elif check_length and check_length < 10: 
            return 'min_length'
        if check_length is None: 
            check_length = len(fasta_seq[key])

    # Check if the number of sequences in the alignments is greater than 3 
    # (otherwise the alignment is deemed to be uninteresting for a phylogenetic analysis)
    if len(fasta_seq.keys()) < 4: 
        return 'min_seq'
    
    # Finally, write the results into a fasta file and taxid.txt file
    with open (os.path.join(folder, id+'.fasta'), 'w') as w: 
        for key in fasta_seq.keys(): 
            w.write('>'+key+'\n')
            w.write(fasta_seq[key]+'\n')

    with open (os.path.join(folder,'taxid.tsv'), 'a') as w:  
        for key in taxon_dic.keys(): 
            if taxon_dic[key] is not np.nan: 
                w.write(id+'\t'+key+'\t'+str(taxon_dic[key])+'\t2\n')
            else: 
                w.write(id+'\t'+key+'\t'+str(taxon_dic[key])+'\t0\n')
    
    return 'done'

def get_fasta_files(ids, folder, source_folder, transform_dict, allowed_symbols): 
    '''
    This functon can be used to parse out the alignment from the downloaded matrix NeXML files, and write it into an 
    alignment file in FASTA format. 
    
    Input: 
    - ids: list of matrix ids ro be investigated
    - folder: name of the folder the alignments in FASTA format as well as the log file and taxonomy file will be written into. 
    - transform_dict: a dictionary holding ambigious characters that can appear in the alignment
    - allowed_symbols: a list of symbols that are allowed to be in the alignment. 

    Output 
    - alignment files will be written into folder/id.fasta files. 
    - log file will be written into file folder/create_fasta.log. 
    - taconomy IDs will be written into file folder/taxid.tsv.
    '''

    with open (os.path.join(folder, 'taxid.tsv'), 'a') as w: 
        w.write('ALI_ID\tSEQ_NAME\tTAX_ID\tTAX_CHECK\n')

    with open (os.path.join(folder, 'create_fasta.log'), 'w') as w: 
        w.write('matrix_id\tstatus\tsymbols\n')

    output_stream = sys.stdout

    count_index = 1
    for id in ids:

        file_name_nexml = os.path.join(source_folder, id+'.nexml')
        file_name_fasta = os.path.join(folder, id+'.fasta')

        status = 'pending'

        output_stream.write('%d %s\r' % (count_index, id))
        output_stream.flush() 

        if os.path.exists(file_name_nexml) is True and (os.stat(file_name_nexml).st_size==0) is False and os.path.exists(file_name_fasta) is False:

            seq_dict, char_list = check_matrix_xml_file(file_name_nexml)

            if char_list == 'cannot read': 
                with open (os.path.join(folder, 'create_fasta.log'), 'a') as w: 
                    w.write(id+'\tcannot read file\n')

            elif len(char_list) > 0: 
        
                status = create_fasta(id, char_list, seq_dict, transform_dict, allowed_symbols, folder)
                if status != 'done': 
                    with open (os.path.join(folder, 'create_fasta.log'), 'a') as w: 
                        w.write(id+'\t'+status+'\n')

        count_index += 1

def find_study_id_in_nexml(id, file_name, target_file):
    
    with open (file_name) as t: 
        try: 
            file = t.readlines()
        except: 
            print('ERROR: Could not open file: ', file_name)
            return 
    
    study_ids = []
    for line in file: 
        if '/study/' in line: 
            tmp_id = line.split('/study/')[1].split(':')[1].split('"')[0]
            if tmp_id not in study_ids and tmp_id!='': 
                study_ids.append(tmp_id)

    with open (target_file, 'a') as w: 
        w.write(id+'\t')
        for i in range (len(study_ids)): 
            if i < len(study_ids)-1: 
                w.write(study_ids[i]+',')
            else: 
                w.write(study_ids[i])
        w.write('\n')

def get_study_ids(matrix_ids_nt: list, matrix_ids_aa: list) -> list: 
    '''
    This function will get all study IDs associated with already downloaded alignment NeXML files of the TreeBASE database.
    The parsed out study IDs and associdated matrix ID are written into the files study_ids_nt.tsv and study_ids_aa.tsv respectivly. 

    The function returns a list with all study IDs. 
    '''

    # Write header into output file
    with open(os.path.join(prefix, 'study_ids_nt.tsv'), 'w') as w: 
        w.write('ALI_ID\tSTUDY_ID\n')

    # For each DNA matrix ID, check wether a NeXML file exists 
    for id in matrix_ids_nt:

        file_name = os.path.join(os.path.join(prefix, 'nt_nexml_files'), id+'.nexml')

        # If the file exists, get the associated study ID(s). 
        if os.path.exists(file_name) is True:
            find_study_id_in_nexml(id, file_name, os.path.join(prefix, 'study_ids_nt.tsv'))

    # Write header into output file
    with open(os.path.join(prefix, 'study_ids_aa.tsv'), 'w') as w: 
        w.write('ALI_ID\tSTUDY_ID\n')

    # For each AA matrix ID, check wether a NeXML file exists 
    for id in matrix_ids_aa:

        file_name = os.path.join(os.path.join(prefix,'aa_nexml_files'), id+'.nexml')

        # If the file exists, get the associated study ID(s). 
        if os.path.exists(file_name) is True:
            find_study_id_in_nexml(id, file_name, os.path.join(prefix, 'study_ids_aa.tsv'))

    # Read in results files.
    nt_study = pd.read_csv(os.path.join(prefix, 'study_ids_nt.tsv'), sep='\t')
    aa_study = pd.read_csv(os.path.join(prefix, 'study_ids_aa.tsv'), sep='\t')

    # Get the list of study IDs from the tsv files.
    study_ids = []
    for i in range (len(nt_study['STUDY_ID'])): 
        tmp = nt_study['STUDY_ID'][i]
        if ',' in str(tmp):
            tmp = tmp.split(',')
            for st in tmp: 
                if st not in study_ids and st is not np.nan: 
                    study_ids.append(st)  
        elif tmp not in study_ids and tmp is not np.nan: 
            study_ids.append(tmp)   

    for i in range (len(aa_study['STUDY_ID'])): 
        tmp = aa_study['STUDY_ID'][i]
        if ',' in str(tmp):
            tmp = tmp.split(',')
            for st in tmp: 
                if st not in study_ids and st is not np.nan:  
                    study_ids.append(st)  
        elif tmp not in study_ids and tmp is not np.nan: 
            study_ids.append(tmp) 

    return study_ids

def check_study_nexml(id: str, file_name: str) -> list: 

    # Declare output dictionary
    info_dict = {'STUDY_ID': id, 'STUDY_URL': None, 'STUDY_CITATION': None}

    # Try to oen file, should there be a problem, return.
    with open(file_name, 'r', encoding='utf-8') as f:
        try: 
            data = f.read()
        except: 
            return None

    # Read as xml file
    nexml_file = BeautifulSoup(data, "xml")

    # Get all meta data
    meta_data = nexml_file.find_all('meta')

    # Iterate over all otu tags in otus
    for meta in meta_data: 
        prop = meta.get('property')
        if prop == 'prism:doi': 
            info_dict['STUDY_URL'] = 'https://doi.org/'+str(meta.get('content'))
        if prop == 'dcterms:bibliographicCitation': 
            citation = meta.get('content').replace('&amp', '&')

            citation = citation.encode(encoding="utf-8",errors="xmlcharrefreplace")
            citation = citation.decode('utf-8', errors="xmlcharrefreplace")
            citation = re.sub('\s+', ' ', citation)
      
            info_dict['STUDY_CITATION'] = citation

    # Return sequence dictionary and list.
    return info_dict

def create_study_file(study_ids: list): 

    failure = []

    output_stream = sys.stdout
    study_df = pd.DataFrame(columns=('STUDY_ID', 'STUDY_URL', 'STUDY_CITATION'))

    # Should a study_ids.tsv file already exist, read in the file and study_ids that have already been parsed out.
    if os.path.exists(os.path.join(prefix,'study_ids.tsv')) is True: 

        study_df = pd.read_csv('study_ids.tsv', sep='\t')
        study_ids = [x for x in study_ids if x not in list(study_df['STUDY_ID'])]

    count_index = 0
    for number in study_ids:

        id = str(number)
        file_name = os.path.join(os.path.join(prefix,'study_nexml_files'), id+'.nexml')

        if os.path.exists(file_name) is True: 

            output_stream.write('%d %s\r' % (count_index, id))
            output_stream.flush()
            
            tmp_dict = check_study_nexml(id, file_name)
            
            if tmp_dict:
                if tmp_dict['STUDY_URL'] is None or tmp_dict['STUDY_URL'] == 'https://doi.org/': 
                    tmp_dict['STUDY_URL'] = 'purl.org/phylo/treebase/phylows/study/TB2:'+id+'?format=html'
                if tmp_dict['STUDY_CITATION'] is None: 
                    print('Error! No citation for:', id)
                    failure.append([id, 'no citation'])
                    
                else:
                    study_df = study_df.append(tmp_dict, ignore_index = True)
            else: 
                print('Error! Could not read XML file for:', id)
                failure.append([id, 'read XML file error'])

        count_index+=1

    with open(os.path.join(prefix,'study_ids.log'), 'w') as w: 
        
        w.write('STUDY_ID\tERROR\n')
        for fail in failure: 
            w.write(str(fail[0])+'\t'+str(fail[1])+'\n')            

    study_df.to_csv(os.path.join(prefix,'study_ids.tsv'), index=False, sep='\t', encoding='utf-8')

def main(): 
    '''
    Python3 script to access the TreeBASE database. The script can be used to download DNA or protein 
    alignments from the TreeBASE database (in NeXML format) or study files (also in NeXML format).

    Author: Franziska Reden 
    Created: 2023-10-18

    EXAMPLE
    ------
    In this example the script is used to download DNA NeXML files (given by --nt_nexml option). The alignments as stored in the 
    downloaded NeXML files will then be written into seperate alignment files in FASTA format (--nt_fasta option). All results 
    will be stored in the folder declared with the --prefix option. 

    >>> python3 get_treebase_alis.py --prefix [PATH/TO/FOLDER] --nt_nexml --nt_fasta

    INPUT
    -----
    --prefix: str 
        Path and name of the folder the alignments and/or study NeXML files as well as the parsed alignments in FASTA format will be 
        stored in. Default is the current directory. 
    --matrix_id_list: str
        Path to and name of the file containing the matrix IDs (or alignment IDs) of TreeBASE that should be downloaded.
        The file should be a tab seperated file with a header containing the matrix IDs in the first column and type of alignment 
        (NT or AA) in the second column.   
    --study_id_list: str
        Path to and name of the file containing the study IDs of TreeBASE that should be downloaded. Each study ID should be stated 
        in a new line (no header). If this option is not used, but the --study_nexml or --study_file option is still enabled, 
        then a list of study IDs is generated based on already downloaded matrix nexml files.
    --nt_nexml: 
        If this option is enabled, then the script will start to download NeXML files containing DNA alignments from the TreeBASE 
        database. The NeXML files will be written into subfolder nt_nexml_files.
    --nt_fasta: 
        If this option is enabled, then the script will not download any NeXML files, but simply parse out the DNA alignments from 
        already dowmloaded NeXML files and write it into a new file in FASTA format. The alignment in FASTA format will be written 
        into subfolder nt_fasta_files.
    --aa_nexml: 
        If this option is enabled, then the script will start to download NeXML files containing protein alignments from the TreeBASE 
        database. The NeXML files will be written into subfolder aa_nexml_files
    --aa_fasta: 
        If this option is enabled, then the script will not download any NeXML files, but simply parse out the protein alignments from 
        already dowmloaded NeXML files and write it into a new file in FASTA format. The alignment in FASTA format will be written 
        into subfolder aa_fasta_files.
    --study_nexml: 
        If this option is enabled, then the script will start to download NeXML files of the study IDs as stated in the files that 
        was provided with the --study_id_list option. Should no --study_id_list file be provided, then the script will gather study 
        IDs connected to an already downloaded matrix NeXML file (as stored in nt_nexml_files and aa_nexml_files folder). 
        The resulting study NeXML files will be written into folder study_nexml. Note, that this option will not output anything 
        if neither study IDs were provided with the --study_id_list option nor if no alignment NeXML files have yet been downloaded. 
    --study_file: 
        If this option is enabled, then the script will parse out the citation (in APA format) as well as the DOI (if available) 
        from the already downloaded study NeXML files and write them into a file (study_ids.tsv). 
    --timeout: int
        This option can be used to declare the timeout limit (in seconds) that will be used in the requests sent to the TreeBASE server. 
        Default is 30s. 

    OUTPUT
    ------
    - DNA NeXML files will be written into folder nt_nexml_files. 
    - Protein NeXML files will be written into folder aa_nexml_files. 
    - DNA alignments in FASTA format will be written into folder nt_fasta_files. A file containing all available taxon IDs (according to the NCBI 
    taxonomy) will be written into file nt_nexml_files/taxid.tsv.
    - Protein alignments in FASTA format will be written into folder aa_fasta_files. A file containing all available taxon IDs (according to the NCBI 
    taxonomy) will be written into file aa_nexml_files/taxid.tsv.
    - Study NeXML files will be written into folder study_nexml.
    - DataFrame containing the parsed study IDs and citations (mostly in APA format) will be written into study_ids.tsv file
    '''

    # Declare variables
    global prefix
    global timeout_limit

    prefix = ''
    timeout_limit = 30

    matrix_file = None
    study_id_list = None

    study_ids = None
    nt_nexml = False
    aa_nexml = False
    nt_fasta = False
    aa_fasta = False
    study_nexml = False
    study_file = False

    # Read in command line arguments
    for i in range (len(sys.argv)): 
        if sys.argv[i] == '--prefix': 
            prefix = sys.argv[i+1]
        if sys.argv[i] == '--matrix_id_list': 
            matrix_file = sys.argv[i+1]
        if sys.argv[i] == '--study_id_list': 
            study_id_list = sys.argv[i+1]
        if sys.argv[i] == '--nt_nexml': 
            nt_nexml = True
        if sys.argv[i] == '--aa_nexml': 
            aa_nexml = True
        if sys.argv[i] == '--nt_fasta': 
            nt_fasta = True
        if sys.argv[i] == '--aa_fasta': 
            aa_fasta = True
        if sys.argv[i] == '--study_nexml': 
            study_nexml = True
        if sys.argv[i] == '--study_file':
            study_file = True
        if sys.argv[i] == '--timeout': 
            try: 
                timeout_limit = int(sys.argv[i+1])
            except ValueError: 
                print('ValueError: Timeout limit declared with the --timeout option needs to be an integer.')
                sys.exit(2)
        if sys.argv[i] == '--help': 
            print(main.__doc__)
            sys.exit(1)

    print('***Python3 script to download alignments or study citations from the TreeBASE database.***\n')

    # Check for a file containing either study IDs or matrix IDs. If neither was given, exit. 
    if not matrix_file and not study_id_list: 
        print('Please declare the path and name of the file containing the matrix IDs and/or study IDs that are to be investigated \
with the --matrix_id_list or --study_id_list options. Type --help for help.')
        sys.exit(2)

    # Read in infomration regarding the Matrix IDs found in TreeBASE (as obtained from TreeBASE dumpfile).
    if matrix_file and os.path.isfile(matrix_file) is True: 
        
        print('Read in matrix IDs from file '+str(matrix_file)+'...')

        matrices_df = pd.read_csv(matrix_file, sep='\t', header=None, skiprows=1, names=['MATRIX_ID', 'TYPE'])

        matrix_ids_nt = matrices_df[matrices_df['TYPE'] == 'NT']['MATRIX_ID'].unique()
        matrix_ids_aa = matrices_df[matrices_df['TYPE'] == 'AA']['MATRIX_ID'].unique()

        matrix_ids_nt = list(matrix_ids_nt)
        matrix_ids_aa = list(matrix_ids_aa)
        
        print('Number of NT matrix IDs: '+str(len(matrix_ids_nt)))
        print('Number of AA matrix IDs: '+str(len(matrix_ids_aa))+'\n')

    elif matrix_file: 
        print('The file '+str(matrix_file)+' could not be found.')
        sys.exit(2)

    # Read in study IDs from the specified study ID file. 
    if study_id_list and os.path.isfile(study_id_list) is True: 

        print('Read in study IDs from file '+str(study_id_list)+'...')

        with open(study_id_list) as t: 
            study_ids = t.readlines()

            study_ids = [id.strip() for id in study_ids]
            study_ids = [id for id in study_ids if id != '']

        print('Number of study IDs: '+str(len(study_ids))+'\n')

    elif study_id_list: 
        print('The file '+str(study_id_list)+' could not be found.')
        sys.exit(2)

    # Check if matrix_file exists. If it does, continue with downloading nexml files and/or creating FASTA files. 
    if any(var is True for var in [nt_nexml, aa_nexml, nt_fasta, aa_fasta]) and matrix_file: 
        
        # Download nt nexml files. Store them in nt_nexml_files subfolder.
        if nt_nexml is True: 

            if os.path.exists(os.path.join(prefix, 'nt_nexml_files')) is False: 
                os.makedirs(os.path.join(prefix, 'nt_nexml_files'))

            print('Downloading NT matrix NEXML files...')
            failure = get_nexml_file(matrix_ids_nt, 'http://purl.org/phylo/treebase/phylows/matrix/TB2:', os.path.join(prefix, 'nt_nexml_files'))    

            with open(os.path.join(os.path.join(prefix, 'nt_nexml_files'), 'download_nexml.log'), 'w') as w:
                w.write('MATRIX_ID\tERROR\n')
                for i in range (len(failure)): 
                    w.write(str(failure[i][0])+'\t'+str(failure[i][1])+'\n')

            print('NEXML files were written into folder: '+str(os.path.join(prefix, 'nt_nexml_files'))+'.')
            print('Log file was written into: '+str(os.path.join(os.path.join(prefix, 'nt_nexml_files'), 'download_nexml.log'))+'.\n')
        
        # Download aa nexml files. Store them in aa_nexml_files subfolder.
        if aa_nexml is True:
            
            if os.path.exists(os.path.join(prefix, 'aa_nexml_files')) is False: 
                os.makedirs(os.path.join(prefix, 'aa_nexml_files'))

            print('Downloading AA matrix NEXML files...')
            failure = get_nexml_file(matrix_ids_aa, 'http://purl.org/phylo/treebase/phylows/matrix/TB2:', os.path.join(prefix, 'aa_nexml_files'))

            with open(os.path.join(os.path.join(prefix, 'aa_nexml_files'),'download_nexml.log'), 'w') as w:
                w.write('MATRIX_ID\tERROR\n')
                for i in range (len(failure)): 
                    w.write(str(failure[i][0])+'\t'+str(failure[i][1])+'\n')

            print('NEXML files were written into folder: '+str(os.path.join(prefix, 'aa_nexml_files'))+'.')
            print('Log file was written into: '+str(os.path.join(os.path.join(prefix, 'aa_nexml_files'), 'download_nexml.log'))+'.\n')

        # Create FASTA files for the DNA NEXML files that have already been downloaded.
        if nt_fasta is True: 

            if os.path.exists(os.path.join(prefix, 'nt_fasta_files')) is False: 
                os.makedirs(os.path.join(prefix, 'nt_fasta_files'))

            nt_transform_dict = {'AG':'R', 'GA':'R', \
            'CT':'Y', 'CU':'Y', 'TC': 'Y', 'UC':'Y', \
                'GT':'K', 'GU':'K', 'TG': 'K', 'UG':'K', \
                    'AC':'M', 'CA':'M', \
                        'CG':'S', 'GC':'S', \
                            'AT':'W', 'TA':'W', 'AU':'W', 'UA':'W', \
                                'CGT':'B', 'CGU':'B', \
                                    'AGT':'D', 'AGU':'D', \
                                        'ACT':'H', 'ACU':'H', \
                                            'ACG':'V', \
                                                'ACGT':'N', 'ACGU':'N'}
            
            allowed_nt_symbols = "A\tC\tG\tT\tU\tI\tR\tY\tK\tM\tS\tW\tB\tD\tH\tV\tN\t-\t?"
            allowed_nt_symbols = allowed_nt_symbols.split('\t')

            print('Write TreeBASE NT alignments into FASTA files...')
            get_fasta_files(matrix_ids_nt, os.path.join(prefix, 'nt_fasta_files'), os.path.join(prefix, 'nt_nexml_files'), nt_transform_dict, allowed_nt_symbols)
            print('Alignments in FASTA format were written into folder: '+str(os.path.join(prefix, 'nt_fasta_files'))+'.\n')
            print('Log file was written into: '+str(os.path.join(os.path.join(prefix, 'nt_fasta_files'), 'create_fasta.log'))+'.\n')

        # Create FASTA files for the AA NEXML files that have already been downloaded.
        if aa_fasta is True: 

            if os.path.exists(os.path.join(prefix, 'aa_fasta_files')) is False: 
                os.makedirs(os.path.join(prefix, 'aa_fasta_files'))

            allowed_aa_symbols = "A	B	C	D	E	F	G	H	I	J	K	L	M	N	O	P	Q	R	S	T	U	V	W	Y	Z	X	*	-	?"
            allowed_aa_symbols = allowed_aa_symbols.split('\t')
            aa_transform_dict = {'DN':'B', 'ND':'B', 'LI': 'J', 'IL': 'J', 'EQ':'Z', 'QE':'Z'}

            print('Write TreeBASE NT alignments into FASTA files...')
            get_fasta_files(matrix_ids_aa, os.path.join(prefix, 'aa_fasta_files'), os.path.join(prefix, 'aa_nexml_files'), aa_transform_dict, allowed_aa_symbols)
            print('Alignments in FASTA format were written into folder: '+str(os.path.join(prefix, 'aa_fasta_files'))+'.\n')
            print('Log file was written into: '+str(os.path.join(os.path.join(prefix, 'aa_fasta_files'), 'create_fasta.log'))+'.\n')
 
    elif any(var is True for var in [nt_nexml, aa_nexml, nt_fasta, aa_fasta]): 
        print('ERROR! No declared matrix IDs. Please declare the path and name of the file containing the matrix IDs that are to be investigated \
with the --matrix_id_list option. Type --help for help.')

    # If study_id_list was not declared, get study IDs from already downloaded matrix NEXML files. 
    if any(var is True for var in [study_nexml, study_file]) and not study_id_list:
        
        print('Gather study IDs from already downloaded matrix NEXML files...')
        study_ids = get_study_ids(matrix_ids_nt, matrix_ids_aa)
        study_ids = [id for id in study_ids if id != '']

        print('Number of study IDs: '+str(len(study_ids)))

        with open(os.path.join(prefix, 'study_IDs.txt'), 'w') as w: 
            for id in study_ids: 
                w.write(id+'\n')

        print('List of study IDs was written into file: '+str(os.path.join(prefix, 'study_IDs.txt'))+'.\n')

    # Download study NEXML files. Write them into study_nexml_files folder.
    if study_nexml is True:

        if os.path.exists(os.path.join(prefix, 'study_nexml_files')) is False: 
                os.makedirs(os.path.join(prefix, 'study_nexml_files'))

        print('Downloading study NEXML files...')
        failure = get_nexml_file(study_ids, 'http://purl.org/phylo/treebase/phylows/study/TB2:', os.path.join(prefix, 'study_nexml_files'))   

        with open(os.path.join(os.path.join(prefix, 'study_nexml_files'), 'download_nexml.log'), 'w') as w:
            w.write('STUDY_ID\tERROR\n')
            for i in range (len(failure)): 
                w.write(str(failure[i][0])+'\t'+str(failure[i][1])+'\n')

        print('NEXML files were written into folder: '+str(os.path.join(prefix, 'study_nexml_files'))+'.')
        print('Log file was written into: '+str(os.path.join(os.path.join(prefix, 'study_nexml_files'), 'download_nexml.log'))+'.\n')

    # Create a study_file (containing citation of study in APA format and potentially a DOI)
    if study_file is True: 
        
        print('Getting citations in APA format from study NEXML files... ')
        create_study_file(study_ids)
        print('Citations were written into file '+str(os.path.join(prefix,'study_ids.tsv'))+'.')

if __name__=='__main__': 
    main()
