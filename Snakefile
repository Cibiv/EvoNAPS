"""
The EvoNAPS workflow
--------------------
This Snakemake workflow was designed to be applied to a target alignment the user whishes to import to
the EvoNAPS database. Note, that this workflow assumes that the EvoNAPS database already exists on your 
system.

Other than the MySQL database, the workflow includes all neccessary scripts, software, and *.yaml 
files to run. 
- Third-party software can be found in workflow/bin. 
- Python scipts can be found in workflow/scipts. 
- The instructions to create the neccessary conda environments (yaml files) can be found in workflow/envs. 
- The Snakemake rules for this workflow are stored in workflow/rules.

Setup
-----
Please refer to the README.md file for instructions on how to set up the EvoNAPS database.

Usage 
-----
The Snakemake workflow is applied on an alignment as such:

>> snakemake PATH/TO/alignment1.fasta_summary.txt --configfile config/config.yaml --config seq_type=DNA [info=metadata.info] [tax=metadata.tax] --use-conda

or (specifiy wildcard ali_id as config variable):

>> snakemake --configfile config/config.yaml --config ali_id=alignment.fasta seq_type=DNA [info=metadata.info] [tax=metadata.tax] --use-conda
 
Please specify the target file "PATH/TO/alignment.fasta_summary.txt" if you wish
to import the alignment into the EvoNAPS database directly. 
Use "PATH/TO/alignment.fasta_ali_parameters.tsv" if you only wish to run IQTree2 
and parse out all alignment and tree information. 
Use "PATH/TO/alignment.fasta.pythia" if you only wish to calculate the Pythia 
difficulty score (source: https://github.com/tschuelia/PyPythia).

Note, that the sequence type needs to be set manually. Declare the 
sequence type with --config seq_type=[ DNA | AA ] from the command line. 

Also note, that the flag --use-conda is neccessary because
different Snakemake rules use different environments, which requires access to conda. 

"""

print("\n========== CONFIG DEBUG ==========")
print(config)
print("==================================\n")

# Include all rule files
include: "workflow/rules/generate_data.smk"
include: "workflow/rules/import_to_db.smk"

# Use the config file to get optional batch list
# If user passes --config ali_id=xxx, that will override
SINGLE_ALI_ID = config.get("ali_id")  # command-line override
BATCH_ALI_IDS = config.get("ali_ids") or []

if SINGLE_ALI_ID:
    ALI_IDS = [SINGLE_ALI_ID]
else:
    ALI_IDS = BATCH_ALI_IDS

print(f"SINGLE_ALI_ID: {SINGLE_ALI_ID}")
print(f"BATCH_ALI_IDS: {BATCH_ALI_IDS}")
print(f"ALI_IDS: {ALI_IDS}")

# Rule all: expands to all summary files if batch mode is used
rule all:
    input:
        expand("{ali_id}_summary.txt", ali_id=ALI_IDS) if ALI_IDS else []