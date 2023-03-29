#! /usr/bin/env python3

'''
Python script to gather information and statistics on the phylogenetic tree used as input. 

Created: August 2022
Last updated: 14.03.2022
Author: Franziska Reden
'''

import pandas as pd
import networkx
import numpy as np
import sys
from os import path
from Bio import Phylo
from Bio import SeqIO
from datetime import datetime 
import shutil

def CheckFiles(file, text): 
    '''Function to check if input file exists, prints a warning if input is missing or file does not exists. 
    Script will be exited if file does not exist.'''

    if file != '':
        if path.exists(file) == False: 
            print('Could not find file '+file+'.')
            sys.exit(2)
    else: 
        print(text)

def SeqName(ali_file): 
    '''Function reads in the alignement file that has been used for tree reconstruction. It returns a dictionary 
    with the names of the sequences as key and the order in which they appear in the alignment as input value.'''

    name_dic = {}
    index = 1
    if '.phy' in ali_file: 
        for seq_record in SeqIO.parse(ali_file, 'phylip'):
            name_dic.setdefault(str(seq_record.id).strip('\n'), str(index))
            index +=1

    elif '.fasta' in ali_file or '.fa' in ali_file or '.faa' in ali_file: 
        for seq_record in SeqIO.parse(ali_file, 'fasta'):
            name_dic.setdefault(str(seq_record.id).strip('\n'), str(index))
            index +=1

    return name_dic

def tabulate_names(tree, name_dic : dict, len_external) -> dict:
    '''Function to rename the nodes found in the tree (especially the internal nodes as they initially do not 
    have an unique name but only the associated branch length. If a name_dic exists, the external nodes will 
    be renamed according to the number stored in the dictionary (correponding to the order in which the 
    sequences appear in the alignment). If there is no dictionary, external leaves will be named starting from 1.
    Internal nodes will be named starting with the number of external leaves + 1.
    The function returns a dictionary with the renamed nodes.
    
    Input
    -------
    tree : object
        Tree object (network), for which the nodes will be renamed.
    name_dic : dict or None
        Name dictionary that stores the index in which the sequences (external nodes) appear in the original alignment.
    len_external : int
        Number of external leaves in tree.

    Returns
    --------
    names : dic
        Retruns a dictionary storing all the renamed nodes.
    '''

    names = {}
    external_index = 1
    if not not name_dic:
        maximum = max([int(name_dic[key]) for key in name_dic.keys()])
        internal_index = maximum + 1
        max_external = maximum
    else: 
        internal_index = len_external + 1
        max_external = len_external

    for clade in tree.find_clades(): 
        if clade.name: 
            if not name_dic: 
                clade.name = str(external_index)
                external_index += 1
            else:    
                clade.name = str(name_dic[clade.name])
        else: 
            clade.name = str(internal_index)
            internal_index += 1
        names[clade.name] = clade

    return names, max_external

def CheckStatusParentNode(parent, child, edge_dic, neighbours): 
    '''Function that checks if there are unresolved edges leading to the parent node, whereas the edges from or 
    to the child node (node to be visited) is hereby not condsidered.'''

    # Initialise empty vector
    parent_vectors = []
    parent_ex = []

    # Check each neighnour of the parent node and if it is the child node
    for neighbour in neighbours: 
        if neighbour != child: 
            # If an edge leading to the parent node is unresolved, return an empty array
            if len(edge_dic[(neighbour, parent)][1]) == 0: 
                return [], []
            else: 
                parent_vectors.append(edge_dic[(neighbour, parent)][1])
                parent_ex.append(edge_dic[(neighbour, parent)][2])

    # Return the array inlcuding all vectors leaing to the parent node.
    return parent_vectors, parent_ex

def InitialiseGraphTraversal(graph, ex): 
    '''Function that initialises the graph traversal (twice) to find all branch lengths from an external
    leaf to all other external nodes in the graph.''' 

    # Initialise the branch_dic dictionary that will store all BL arrays. 
    # Each directed edge will be stored as key. A tracker is stored at 
    # index 0 which will be set to 0 (not visited) and will be set to 1 
    # once the edge has been traversed. At index 1 the arrays storing the sum 
    # of branch lengths leading from the edge to all external edges.
    # At position 2: A list of all external nodes in the subtree. 
    # At position 3: The neighbouring edges of the edge.
    edge_dic = {}
    # Initialise an array that will keep track on which edges have not been visited yet.
    edge_to_visit_1 = []
    for edge in graph.edges(): 
        edge_dic.setdefault(edge, [0, [], [], []])
        edge_dic.setdefault((edge[1], edge[0]), [0, [], [], []])
        edge_to_visit_1.append(edge)
        edge_to_visit_1.append((edge[1], edge[0]))

    edge_to_visit_2 = edge_to_visit_1[:]

    # Determine an initial internal node that is a neighbour to an external node
    for neighbour in graph[ex[0]]: 
        initial_node = neighbour

    # Start the first traversal
    edge_dic = TraverseGraph(initial_node, graph, edge_dic, edge_to_visit_1)

    # Reset tracker in edge_dic to zero (no node has been visitied yet).
    for edge in edge_dic.keys(): 
        edge_dic[edge][0] = 0

    # Start the second traversal
    edge_dic = TraverseGraph(initial_node, graph, edge_dic, edge_to_visit_2)

    # Return the dictionary with all the relevent information.
    return edge_dic

def TraverseGraph(node, graph, edge_dic, to_visit) -> dict: 
    '''Function that traverses the graph so that each edge in the graph is visited twice (once in each direction).
    Saves all branch lengths that lead from an external node to each edge in the graph (in both directions) in the
    edge_dic dictionary.'''

    # While not all edges have been visited twice, continue the traversal
    while len(to_visit) > 0: 

        # Helper variable that helps keeps track if a new node to be visited has been found.
        new_node = 0

        # Find all neighbours of the current node.
        neighbours = graph[node]

        for neighbour in neighbours: 

            # Check if the edge leading to the neighbour has been visited before.
            if edge_dic[(node, neighbour)][0] == 0: 

                # If the current node is an external node and the (number of neighours is 1) visit the node.
                if len(neighbours) == 1: 

                    # Branch length(s) from the current (external) node to the new node is simply the length of the external edge.
                    # Save the edge in the branch_dic dictionary (the edge has been visited).
                    edge_dic[(node, neighbour)][1] = [neighbours[neighbour]['weight']]
                    edge_dic[(node, neighbour)][2] = [node]
                    for neigh_of_neigh in graph[neighbour]: 
                        if neigh_of_neigh != node and (neighbour, neigh_of_neigh) not in edge_dic[(node, neighbour)][3]: 
                            edge_dic[(node, neighbour)][3].append((neighbour, neigh_of_neigh))

                    # Visit the neighbour (new current node) and remove edge from the to_visit array.
                    edge_dic[(node, neighbour)][0] = 1
                    to_visit.remove((node, neighbour))
                    node = neighbour
                    new_node = 1
                    
                    # Break and continue the traversal with the new node
                    break
                
                # If it is an internal edge, check if edge leading to the neighbour has been visited in the other direction.
                elif edge_dic[(neighbour, node)][0] == 0: 

                    # Check if the parent node is fully resolved. If it is save the results in the edge_dic dictionary.
                    parent_vectors, parent_ex = CheckStatusParentNode(node, neighbour, edge_dic, neighbours)

                    if len(parent_vectors) > 0:
                        wheight = neighbours[neighbour]['weight']
                        edge_dic[(node, neighbour)][1] = [x + wheight for x in np.concatenate([i for i in parent_vectors])]
                        edge_dic[(node, neighbour)][2] = [x for x in np.concatenate([i for i in parent_ex])]
                    for neigh_of_neigh in graph[neighbour]: 
                        if neigh_of_neigh != node and (neighbour, neigh_of_neigh) not in edge_dic[(node, neighbour)][3]: 
                            edge_dic[(node, neighbour)][3].append((neighbour, neigh_of_neigh))

                    # Visit the neighbour (new node) and remove edge from the to_visit array.
                    edge_dic[(node, neighbour)][0] = 1
                    to_visit.remove((node, neighbour))
                    node = neighbour
                    new_node = 1
                    break

        # Only if all neighbouring external edges have been visited and all neighbouring internal edges have been traversed once,
        # traverse back on an internal node.   
        if new_node == 0: 

            for neighbour in neighbours:

                if edge_dic[(node, neighbour)][0] == 0: 

                    edge_dic[(node, neighbour)][0] = 1
                    parent_vectors, parent_ex = CheckStatusParentNode(node, neighbour, edge_dic, neighbours)
                    
                    if len(parent_vectors) > 0:
                        wheight=neighbours[neighbour]['weight']
                        edge_dic[(node, neighbour)][1] = [x + wheight for x in np.concatenate([i for i in parent_vectors])]
                        edge_dic[(node, neighbour)][2] = [x for x in np.concatenate([i for i in parent_ex])]
                    for neigh_of_neigh in graph[neighbour]: 
                        if neigh_of_neigh != node and (neighbour, neigh_of_neigh) not in edge_dic[(node, neighbour)][3]: 
                            edge_dic[(node, neighbour)][3].append((neighbour, neigh_of_neigh))
                    
                    to_visit.remove((node, neighbour))
                    node = neighbour
                    new_node = 1

                    break
    
    # Once each edge in the graph has been visited once in each direction, return the edge_dic dictionary.
    return edge_dic

def WriteBranchesToDF(G, edge_dic, stats, len_external, rooted = False): 

    global pot_FF_list
    pot_FF_list = {}
    for limit in [7,8,9,10]:
        stats['POT_FF_'+str(limit)] = 0
        pot_FF_list.setdefault('POT_FF_'+str(limit), [])
    ib_count = 0

    branches_df = pd.DataFrame(columns = ['BRANCH_INDEX', 'BRANCH_TYPE', 'BL', 'SPLIT_SIZE', 'SPLIT', '1_MIN_PATH', '1_MAX_PATH', '1_MEAN_PATH', '1_MEDIAN_PATH', \
            '2_MIN_PATH', '2_MAX_PATH', '2_MEAN_PATH', '2_MEDIAN_PATH'])

    tree_diameter = 0
    sumIBL = 0

    if rooted == True: 
        tree_height = 0
        all_paths = []
        for neighbour in G[len_external+1]: 
            all_paths.append(edge_dic[(neighbour, len_external+1)][1])
        if max(all_paths) > tree_height: 
                tree_height = max(all_paths)
        stats['TREE_HEIGHT'] = tree_height

    for edge in G.edges(): 
        split_1 = [0]*len_external

        wheight = G[edge[0]][edge[1]]['weight']

        corrected_arrays = [[x-wheight for x in edge_dic[edge][1]], [x-wheight for x in edge_dic[(edge[1], edge[0])][1]]] 

        split = ''
        for taxa in edge_dic[edge][2]: 
            split_1[int(taxa)-1] = 1 
        if split_1[0] == 1:
            for split_index in split_1: 
                split += str(split_index)
        else: 
            split_2 = [0]*len_external
            for taxa in edge_dic[(edge[1], edge[0])][2]: 
                split_2[int(taxa)-1] = 1 
            for split_index in split_2: 
                split += str(split_index)

        results = [[len(corrected_arrays[0]), np.mean(corrected_arrays[0]), np.median(corrected_arrays[0]), min(corrected_arrays[0]), max(corrected_arrays[0])], \
                [len(corrected_arrays[1]), np.mean(corrected_arrays[1]), np.median(corrected_arrays[1]), min(corrected_arrays[1]), max(corrected_arrays[1])]]

        results.sort(key=lambda x: x[0])
        if results[0][0] == results[1][0]:
            results.sort(key=lambda x: x[1])

        if len(G[edge[0]]) > 1 and len(G[edge[1]]) > 1: 

            index = int(edge[1])-1
            ib_count+=1

            branches_df = branches_df.append({'BRANCH_INDEX': index, 'BL': wheight, 'BRANCH_TYPE': 'i', 'SPLIT_SIZE': results[0][0],'SPLIT': split,  \
                '1_MEAN_PATH': results[0][1], '1_MEDIAN_PATH': results[0][2], '1_MIN_PATH': results[0][3], '1_MAX_PATH': results[0][4], \
                    '2_MEAN_PATH': results[1][1], '2_MEDIAN_PATH': results[1][2], '2_MIN_PATH': results[1][3], '2_MAX_PATH': results[1][4]}, ignore_index = True)

            for limit in [7,8,9,10]:

                neigh_wheights = []
                for neigh_edge in edge_dic[(edge[1], edge[0])][3]+edge_dic[edge][3]: 
                    neigh_wheights.append(G[neigh_edge[0]][neigh_edge[1]]['weight'])
                
                neigh_wheights.sort()

                if neigh_wheights[0] > 0 and neigh_wheights[1] > 0:
                    short_mean = np.mean(neigh_wheights[:2]+[wheight])

                    if short_mean <= neigh_wheights[2]/limit and short_mean <= neigh_wheights[3]/limit: 
                        stats['POT_FF_'+str(limit)] = stats['POT_FF_'+str(limit)]+1
                        pot_FF_list.setdefault('POT_FF_'+str(limit), []).append(index)

            sumIBL += wheight

        else: 

            all_paths = np.concatenate([edge_dic[(edge[1], edge[0])][1], edge_dic[(edge[0], edge[1])][1]])
            if max(all_paths) > tree_diameter: 
                tree_diameter = max(all_paths)

            branches_df = branches_df.append({'BRANCH_INDEX': int(edge[1]), 'BL': wheight, 'BRANCH_TYPE': 'e', 'SPLIT_SIZE': results[0][0], 'SPLIT': split, \
                    '1_MEAN_PATH': results[0][1], '1_MEDIAN_PATH': results[0][2], '1_MIN_PATH': results[0][3], '1_MAX_PATH': results[0][4], \
                        '2_MEAN_PATH': results[1][1], '2_MEDIAN_PATH': results[1][2], '2_MIN_PATH': results[1][3], '2_MAX_PATH': results[1][4]}, ignore_index = True)

    stats['TREE_DIAMETER'] = tree_diameter
    stats['TREE_LENGTH'] = sum(branches_df['BL'])

    stats['SUM_IBL'] = sumIBL
    stats['BL_MIN'] = min(branches_df['BL'])
    stats['BL_MAX'] = max(branches_df['BL'])
    stats['BL_MEAN'] = np.mean(branches_df['BL'])
    stats['BL_MEDIAN'] = np.median(branches_df['BL'])
    stats['BL_VAR'] = np.var(branches_df['BL'])

    if ib_count > 0: 
        stats['IBL_MIN'] = min(branches_df.loc[branches_df['BRANCH_TYPE'] == 'i']['BL'])
        stats['IBL_MAX'] = max(branches_df.loc[branches_df['BRANCH_TYPE'] == 'i']['BL'])
        stats['IBL_MEAN'] = np.mean(branches_df.loc[branches_df['BRANCH_TYPE'] == 'i']['BL'])
        stats['IBL_MEDIAN'] = np.median(branches_df.loc[branches_df['BRANCH_TYPE'] == 'i']['BL'])
        stats['IBL_VAR'] = np.var(branches_df.loc[branches_df['BRANCH_TYPE'] == 'i']['BL'])

    stats['EBL_MIN'] = min(branches_df.loc[branches_df['BRANCH_TYPE'] == 'e']['BL'])
    stats['EBL_MAX'] = max(branches_df.loc[branches_df['BRANCH_TYPE'] == 'e']['BL'])
    stats['EBL_MEAN'] = np.mean(branches_df.loc[branches_df['BRANCH_TYPE'] == 'e']['BL'])
    stats['EBL_MEDIAN'] = np.median(branches_df.loc[branches_df['BRANCH_TYPE'] == 'e']['BL'])
    stats['EBL_VAR'] = np.var(branches_df.loc[branches_df['BRANCH_TYPE'] == 'e']['BL'])

    branches_df.sort_values(by=['BRANCH_INDEX'], inplace=True)

    return stats, branches_df

def ParseTree(treefile, name_dic = False, rooted = False): 

    stats = {}

    ctrl = 0
    try:    
        tree = Phylo.read(treefile, "newick", rooted = rooted) # Read in the tree (stored as Newick string) using the Bio.Phylo library.
    except: 
        print('ERROR: Failed to read in input treefile. The input tree must be a Newick string.')
        ctrl = 1
    
    if ctrl != 0: 
        return None, None

    external = tree.get_terminals() # Get all leafes (terminals).
    names, max_external = tabulate_names(tree, name_dic, len(external)) # Rename the nodes.

    net = Phylo.to_networkx(tree) # Tranformation of tree (tree) into NetworkX object (net).
    G = networkx.Graph() # Graph of tree with weighted esdges coresponding to the branch length.

    for edge in net.edges(): 
        BL = names[str(edge[1])].branch_length
        G.add_edge(str(edge[0]), str(edge[1]), weight = BL)

    if len(G[str(max_external+1)]) < 3 and rooted == False: 
        weights = []
        node_names = []
        for neighbour in G[str(max_external+1)]:
            node_names.append(neighbour)
            weights.append(names[neighbour].branch_length)
            G.remove_edge(str(max_external+1), neighbour)
        
        G.add_edge(node_names[0], node_names[1], weight = sum(weights))

    edge_dic = InitialiseGraphTraversal(G, [str(x) for x in external]) 

    stats, branches_df = WriteBranchesToDF(G, edge_dic, stats, max_external, rooted = rooted)

    return stats, branches_df  

def main(): 
    '''
    parse_tree
    --------------
    Script to parse internal and external branches from a phylogenteic tree given in Newick string format.
    Author: Franziska Reden
    Created: September 2022
    Updated: 14.03.2023

    USAGE:
    --------------
    >>> parse_tree.py --treefile [treefile] --alifile [alignment file] ...

    REQUIRED INPUT:
    --------------
    --treefile or -t : file
        Declare the file name and path to the input tree file. The tree needs to be in Newick format. 
    
    OPTIONAL INPUT 
    --------------
    --ali_file or -a : file
        Declare the file name and path to the alignment file that was used for tree resconstruction. The index of the external nodes 
        in the output file will correspond to the order in which the sequences appear in the alignment file. 
    --output or -o : path/file
        Declare the name and path to the output file. 
    --rooted or -r : bool
        Declare if the tree is rooted (True) or unrooted (False).
    --silent : 
        If this option is chosen, then the DataFrame containing the results will not be displayed on screen but will only 
        be written into the output file.

    OUTPUT 
    --------------
    parse_tree.py writes the results into a tab seperated file. The name of the output file can be declared with the --output options. 

    EXAMPLE
    --------------
    >>> parse_tree.py --treefile myinput.fasta.treefile --alifile myinput.fasta --output myoutput.tsv
    myoutput.tsv                                                                                        
    '''

    global pot_FF_list
    treefile = ''
    ali_file = ''
    output_file = ''
    rooted = False
    silent = False
    date = datetime.now().strftime("%Y-%m-%d_%H:%M:%S")

    for i in range (len(sys.argv)): 
        if sys.argv[i] in ['--treefile', '-t']: 
            treefile = sys.argv[i+1]
        if sys.argv[i] in ['--alifile', '-a']: 
            ali_file = sys.argv[i+1] 
        if sys.argv[i] in ['--outfile', '-o']: 
            output_file = sys.argv[i+1]
        if sys.argv[i] in ['--rooted', '-r']: 
            if sys.argv[i+1].upper() in ['TRUE', 'YES']: 
                rooted = True
        if sys.argv[i] in ['--help', '-h']: 
            print(main.__doc__)
            sys.exit(0)
        if sys.argv[i] in ['--silent']: 
            silent = True

    print('parse_tree.py: Script to parse internal and external branches from a phylogenteic tree given in Newick string format.')
    print('By Franziska Reden')
    print('September 2022\n')

    CheckFiles(treefile, 'Missing input: treefile. Declare tree input file with the --treefile (or -t) option')
    if ali_file != '':
        CheckFiles(ali_file, 'Missing input: ali_file. Declare alignment input file with the --alifile (or -a) option')
    if output_file == '': 
        output_file = treefile+'_parsed_branches.tsv'

    print('Input treefile: '+treefile)
    if ali_file != '':
        print('Input alignment file: '+ali_file)

    if ali_file != '':
        name_dic = SeqName(ali_file)
    else: 
        name_dic = None

    stats, branches_df = ParseTree(treefile, name_dic = name_dic, rooted = rooted)  

    if silent == False:

        for key in stats.keys(): 
            print(''+str(key)+': '+str(stats[key]))
        print('')

        for key in pot_FF_list.keys(): 
            print('# '+key+':  '+str(pot_FF_list[key]))

        # convert DataFrame to string
        df_string = branches_df.to_string(index = False)
        df_split = df_string.split('\n')

        columns = shutil.get_terminal_size().columns
        for i in range(len(branches_df)+1):
            print(df_split[i].center(columns)) 

    with open (output_file, 'w') as w: 
        w.write('# parse_tree.py: parse branch parameters from input tree.\n# Datetime: '+date+'\n#\n')
        w.write('# Input treefile: '+treefile+'\n')
        if ali_file != '':
            w.write('# Input alignment file: '+ali_file+'\n')
        for key in stats.keys(): 
            w.write('# '+str(key)+': '+str(stats[key])+'\n')
        for key in pot_FF_list.keys(): 
            w.write('# '+key+':  '+str(pot_FF_list[key])+'\n')
        w.write('#\n')

    branches_df.to_csv(output_file, mode = 'a', index = False, sep = '\t', header = True)
    print('...branch parameters were written into file '+output_file)

if __name__ == '__main__': 
    main()