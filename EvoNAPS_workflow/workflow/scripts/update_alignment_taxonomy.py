import pandas as pd
import mysql.connector as mysql

def get_taxonomy(db_config:dict) -> pd.DataFrame:

    mydb = mysql.connect(**db_config)

    mycursor = mydb.cursor()
    query = "SELECT TAX_ID, PARENT_TAX_ID, TAX_NAME, TAX_RANK from taxonomy;"

    mycursor.execute(query)
    myresult = mycursor.fetchall()

    taxonomy = pd.DataFrame(myresult, columns = ['TAX_ID', 'PARENT_TAX_ID', 'TAX_NAME', 'TAX_RANK'])

    # Reindex taxonomy table to allow for faster lookups.
    return taxonomy.set_index('TAX_ID')

def taxonomic_hierarchy_per_sequence(tax_id:str, taxonomy_db:pd.DataFrame) -> dict:

    # Initailize dictionary, set current tax_id to input tax_id.
    lineage_tax_ids = []
    lineage_names = []
    lineage_ranks = []
    current_tax_id = tax_id

    # For each TAX_ID get the name and the rank (e.g., genus) of the clade.
    while True: 
        
        # Upate dict with the taxanomic rank and ID as key and item.
        lineage_tax_ids.append(current_tax_id) 
        lineage_names.append(taxonomy_db['TAX_NAME'][current_tax_id])
        lineage_ranks.append(taxonomy_db['TAX_RANK'][current_tax_id])

        # Once the root is reached, stop.
        if current_tax_id == 1 and taxonomy_db['PARENT_TAX_ID'][current_tax_id] == 1: 
            break
        
        # Continue with parent tax ID
        current_tax_id = taxonomy_db['PARENT_TAX_ID'][current_tax_id]

    lineage_tax_ids.reverse()
    lineage_names.reverse()
    lineage_ranks.reverse()

    return {'TAX_ID':lineage_tax_ids, 'TAX_RANK': lineage_ranks, 'TAX_NAME': lineage_names}

def get_tax_ids(db_config:dict, ali_id:str, seq_type:str) -> pd.DataFrame:

    mydb = mysql.connect(**db_config)

    mycursor = mydb.cursor()
    query = f"SELECT TAX_ID, TAX_CHECK from {seq_type.lower()}_sequences where ALI_ID='{ali_id}';"

    mycursor.execute(query)
    myresult = mycursor.fetchall()

    seqs = pd.DataFrame(myresult, columns = ['TAX_ID', 'TAX_CHECK'])

    return seqs

def get_lca(new_row:dict, seqs_tax:dict, tax_rank_dict:dict) -> dict:

    # Get lineage of first entry in sequence dictionary to use it as comparison
    first_key = next(iter(seqs_tax))
    prime_tax_ids = seqs_tax[first_key]['TAX_ID']
    prime_ranks = seqs_tax[first_key]['TAX_RANK']
    #print(prime_ranks, prime_tax_ids)

    # Set LCA index to lengt of lineage
    index = len(prime_tax_ids)-1

    # Iterate over all sequences to find overlap in the lineage
    # Update the index if overlap moves closer to the root
    for key, item in seqs_tax.items():
        if index > len(item['TAX_ID'])-1:
            index = len(item['TAX_ID'])-1
        while index >= 0 and item['TAX_ID'][index] != prime_tax_ids[index]:
            index -= 1
        if index == 0:
            break
    
    # Set LCA TAX_ID to the one we found
    new_row['LCA_TAX_ID'] = prime_tax_ids[index]

    # Set the RANk to the first linnean rank found in the lineage
    while prime_ranks[index] not in tax_rank_dict.keys():
        index -= 1
    new_row['LCA_RANK_NR'] = tax_rank_dict[prime_ranks[index]]
    new_row['LCA_RANK_NAME'] = prime_ranks[index]

    # Update new_row with lineage tax_ids starting with index (lca)
    for i in range (index, -1, -1):
        if prime_ranks[i] in tax_rank_dict.keys():
            new_row[f"{tax_rank_dict[prime_ranks[i]]}_{prime_ranks[i]}"] = prime_tax_ids[i]

    return new_row

def create_query(tax_dict:dict, seq_type:str) -> tuple[str, str]:

    column_string = "("
    value_string = "("
    values = []

    for key, item in tax_dict.items():
        column_string += f"{key}, "
        value_string += f"%s, "
        values.append(item)

    column_string = column_string[:-2]+')'
    value_string = value_string[:-2]+')'

    insert_query = f"INSERT IGNORE INTO {seq_type.lower()}_alignments_taxonomy {column_string} VALUES {value_string};"

    return insert_query, tuple(values)

def get_alignment_taxonomy(ali_id:str, seq_type:str, db_config:dict) -> tuple[str, tuple]:
    """    
    Parameters
    --------
    ali_id : str
        The alignmentd ID as it appears in the alignment
    seq_type : str
        The type of alignemntd (DNA or AA), which determines in which table in the EvoNAPS
        database to search.
    db_config : dict
        Credentials to access the database (hostname, user name, etc.)
    
    Returns
    --------
    query : str
        A query that can be used to insert a new line into the 
        alignments_taxonomy table of the EvoNAPS database.
    parameters : tuple[str]
        A set of parameters that will replace the correponding spots in the query.
    
    Description
    ----------
    Function to retrieve the taxon IDs of an alignemnt from the EvoNAPS database.
    The taxon ID and rank of last common ancestor (LCA) of the sequences is caculated.
    """

    tax_rank_dict = {'superkingdom': 1,
        'kingdom': 2,
        'subkingdom': 3,
        'superphylum': 4,
        'subphylum': 5,
        'phylum': 6,
        'superclass': 7,
        'class': 8,
        'subclass': 9,
        'infraclass': 10,
        'cohort': 11,
        'subcohort': 12,
        'superorder': 13,
        'order': 14,
        'suborder': 15,
        'infraorder': 16,
        'parvorder': 17,
        'superfamily': 18,
        'family': 19,
        'subfamily': 20,
        'genus': 21,
        'subgenus': 22,
        'species group': 23,
        'species subgroup': 24,
        'species': 25,
        'subspecies': 26,
        'tribe': 27,
        'subtribe': 28,
        'forma': 29,
        'varietas': 30,
        'strain': 320,
        'section': 330,
        'subsection': 340,
        'pathogroup': 350,
        'subvariety': 360,
        'genotype': 370,
        'serotype': 380,
        'isolate': 390,
        'morph': 400,
        'series': 410,
        'forma specialis': 420,
        'serogroup': 430,
        'biotype': 440}
    
    new_row = {'ALI_ID': None, 
        'TAX_RESOLVED': None, 
        'LCA_TAX_ID': None, 
        'LCA_RANK_NR': None, 
        'LCA_RANK_NAME': None, 
        'superkingdom': None, 
        'kingdom': None, 
        'subkingdom': None, 
        'superphylum': None, 
        'subphylum': None, 
        'phylum': None, 
        'superclass': None, 
        'class': None, 
        'subclass': None, 
        'infraclass': None, 
        'cohort': None, 
        'subcohort': None, 
        'superorder': None, 
        'order': None, 
        'suborder': None, 
        'infraorder': None, 
        'parvorder': None, 
        'superfamily': None, 
        'family': None, 
        'subfamily': None, 
        'genus': None, 
        'subgenus': None, 
        'species_group': None, 
        'species_subgroup': None, 
        'species': None, 
        'subspecies': None, 
        'tribe': None, 
        'subtribe': None, 
        'forma': None, 
        'varietas': None, 
        'strain': None, 
        'section': None, 
        'subsection': None, 
        'pathogroup': None, 
        'subvariety': None, 
        'genotype': None, 
        'serotype': None, 
        'isolate': None, 
        'morph': None, 
        'series': None, 
        'forma_specialis': None, 
        'serogroup': None, 
        'biotype': None}

    #Get all tax IDs for the alignment
    seqs = get_tax_ids(db_config, ali_id, seq_type)

    # Check if any taxon ID is unresolved, set TAX_RESOLVED for alignment accordingly.
    new_row['TAX_RESOLVED'] = 0 if (seqs['TAX_CHECK'] == 0).any() else 1
    
    # Also check if any sequence has tax id of 0, to save computational time:
    if (seqs['TAX_ID'] == 1).any():
        new_row['LCA_TAX_ID'] = 1
        new_row['LCA_RANK_NR'] = 0
        new_row['LCA_RANK_NAME'] = 'root'

        return create_query(new_row, seq_type)
    
    # Get all sequences and calculate the LCA:
    seqs_tax = {}
    taxonomy_db = get_taxonomy(db_config)
    for tax_id in seqs['TAX_ID'].unique():
        seqs_tax[tax_id] = taxonomic_hierarchy_per_sequence(tax_id, taxonomy_db)

    new_row = get_lca(new_row, seqs_tax, tax_rank_dict)

    return create_query(new_row, seq_type)
