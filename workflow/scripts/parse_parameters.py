import argparse
import pathlib
from parse_files import check_state_freqs, parse_ali_parameters, parse_all_model_parameters, parse_all_tree_parameters
from classes import ConstantVariabels, Data, Results
from os import path

def main(): 

    parser = argparse.ArgumentParser(description='**Script to parse relevant information from IQ-Tree 2 output files to be imported into the EvoNAPS database..**')
    
    parser.add_argument('-p', '--prefix', 
                        type=str, 
                        action='store', 
                        required = True,
                        help=' Mandatory argument. Declares the path to and prefix of the IQ-Tree2 results files to be investigated. \
                        Typically, the prefix will be the name of the alignment file that has been used as input for IQ-Tree 2.')
    
    parser.add_argument('-o', '--output', 
                        type=str, 
                        action='store',
                        help='Option to declare the prefix of the output files. Default will be prefix from --prefix.')
    
    parser.add_argument('-a', '--ali_file', 
                        type=pathlib.Path, 
                        action='store',
                        help='Option to declare the name of the original alignment file, should it not coincide with the prefix.')
    
    parser.add_argument('-t', '--tax', 
                        type=pathlib.Path, 
                        action='store',
                        help='Provide csv file with taxon IDs for the names in the alignment file. \
                        The taxon file should include the name EXACTLY as it apears in the alignemnt file \
                        in column 1 and its corresponding taxon ID in column 2. Column three should state \
                        the taxon check (how was the taxon ID determined). If this column is \
                        left empty a taxon check of 3 will be assumed (manual checking) \
                        Lines starting with \'#\' will be ignored (including the header line). \
                        Optinally, you can include a fourtht column that states the accession number \
                        of the sequence. If accession number is unknown, please leave the fourtht \
                        column EMPTY! Any further columns will be ignored.')
    
    parser.add_argument('-c', '--config', 
                        type=pathlib.Path, 
                        action='store', 
                        default=path.dirname(path.abspath(__file__)), 
                        help='Option to declare the path to the folder holding the config files. \
                            Default is the same directory as this script.')
    
    parser.add_argument('-q', '--quiet', 
                        action='store_true', 
                        help='Quiet mode will print minimal information.')
    
    args = parser.parse_args()

    if args.quiet is not True:
        print(parser.description)

    if not args.output: 
        args.output = args.prefix

    if not args.ali_file: 
        args.ali_file = args.prefix
    
    # Initialize all objects to store data and results.
    constants = ConstantVariabels(args.config)
    results = Results(args.prefix, args.output, args.config, quiet=args.quiet)
    data = Data(args.prefix, args.quiet, args.ali_file)

    # Don't mess with the order of things! 
    # Each steps filters out info that will be needed later!

    # Check and open all neccessary files
    data.check_files()
    data.open_files()

    # Check data type
    results.type = data.check_ali_type()

    # Initialize dfs
    results.initialize_df()

    # Check state frequencies
    check_state_freqs(data, results)

    # Parse ali parameters
    parse_ali_parameters(data, results)
 
    # Parse model parameters
    parse_all_model_parameters(data, results, constants)

    # Parse tree parameters
    parse_all_tree_parameters(data, results, constants)

    # Finally update ali_para and round numbers....
    results.update_dfs()
    results.round_dfs()

    return 0

if __name__ == '__main__':
    main()
