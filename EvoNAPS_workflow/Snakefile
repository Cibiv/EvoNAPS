"""
The EvoNAPS workflow
--------------------
This Snakemake workflow was designed to be applied to a target alignment the user whishes to import to
the EvoNAPS database. Note, that this workflow assumes that the EvoNAPS database already exists on your 
system.

Other than the MySQL database, the workflow includes all neccessary scripts, software, and *.yaml 
files to run. 
- Third-party software can be found in workflow/bin. 
- The Python scipts can be found in workflow/scipts. 
- The instructions to create the neccessary conda environments (yaml files) can be found in workflow/envs. 
- The Snakemake rules for this workflow are stored in workflow/rules.

Setup
-----
This workflow requires an instance of Snakemake. A detailed installation guide can be found here:



Note, that before running the workflow on an alignment, the config/EvoNAPS_credentials.cnf file
needs to be created. This file includes all neccessary information to allow access to the target
database. An example file is given in config/EvoNAPS_credentials_example.cnf. Exchange the names 
of the credentials according to your database setup and rename the file to 
config/EvoNAPS_credentials.cnf.

Usage 
-----
The Snakemake workflow is applied onto an alignment as such:

>> snakemake PATH/TO/alignment.fasta_summary.txt --config seq_type=DNA --use-conda

Please specify the target file "PATH/TO/alignment.fasta_summary.txt" if you wish
to import the alignment into the EvoNAPS database directly. 
Use "PATH/TO/alignment.fasta_ali_parameters.tsv" if you only wish to run IQTree2 
and parse out all alignment and tree information. 
Use "PATH/TO/alignment.fasta.pythia" if you only wish to calculate the Pythia 
difficulty score (source: https://github.com/tschuelia/PyPythia).

Note, that the sequence type needs to be set manually. Declare the 
sequence type with --config seq_type=[ DNA | AA ] from the command line. 

Also note, that the flag --use-conda is neccessary because different environments 
are used for different rules, which requires access to conda. 

Sources
-------
"""

include: "workflow/rules/generate_data.smk"
include: "workflow/rules/import_to_db.smk"

rule all: 
    input: 
        "{ali_id}_ali_parameters.tsv", 
        "{ali_id}_seq_parameters.tsv",
        "{ali_id}_model_parameters.tsv",
        "{ali_id}_branch_parameters.tsv",
        "{ali_id}_tree_parameters.tsv",
        "{ali_id}_summary.txt"
