"""
This Snakefile inlcudes all rules neccessary to run the EvoNAPS worklow on an DNA alignemnt with the name {alignment_ID}. 
To start the worklow type: 

    snakemake --snakefile /path/to/snakefile/EvpoNAPS_snakefile_dna.smk {alignment_ID}_results/{alignment_ID}_NEW_tree_parameters.tsv. 

The goal file *ali_parameter.tsv is one of the files generated in the last rule in the workflow, making sure that all previous 
rules have already run. Replace {alignment_ID} with the name of the alignment file to be investiagted. 

Created: August 2022
Author: Franziska Reden
"""
rule all: 
    input: "{alignment_ID}_results/{alignment_ID}_ali_parameters.tsv", "{alignment_ID}_results/{alignment_ID}_seq_parameters.tsv", \
    "{alignment_ID}_results/{alignment_ID}_model_parameters.tsv", "{alignment_ID}_results/{alignment_ID}_branch_parameters.tsv", \
    "{alignment_ID}_results/{alignment_ID}_tree_parameters.tsv"

rule CopyAlignment: 
"""
Copy the alignment into a subfolder that will hold all results from running IQ-Tree as well as the EvoParaDB workflow.
Inut is the alignment file. Output is the copied alignment file in the subfolder.
"""
    output: "{alignment_ID}_results/{alignment_ID}"
    input: "{alignment_ID}"
    shell: """
        cp {input} ./{input}_results/
        """

rule TestModelsAndTreeSearch: 
"""
The TestModelsAndTreeSearch rule starts the model selection and tree search using the alignment file as input. 
All models included in the -m MF option (see IQ-Tree 2 tutorial as reference http://www.iqtree.org/doc/iqtree-doc.pdf) 
are tested. The -m MFP option ensures that the model testing is followed by a ML tree search using 
the best-fit model (according to BIC). The declared output files are the *.iqtree, *.treefile and the *ckp.gz files. 
Should the rule fail the declared output files will be deleted to ensure that when restarting the workflow 
there is no conflict between the files already generated and to those to be created. 
""" 
    output: "{alignment_ID}_results/{alignment_ID}.iqtree", "{alignment_ID}_results/{alignment_ID}.treefile", \
    "{alignment_ID}_results/{alignment_ID}.ckp.gz", "{alignment_ID}_results/{alignment_ID}.log"
    input: seq = "{alignment_ID}", 
        folder = "{alignment_ID}_results/{alignment_ID}"
    shell:"""
        iqtree2mod -s {input.seq}_results/{input.seq} --seqtype DNA -m MFP -mrate E,I,G,I+G,R > {input.seq}_results/{input.seq}.iqlog
        if test -f {input.seq}_results/{input.seq}.uniqueseq.phy; then
            declare seed_num="$(get_seed.py --file {input.seq}_results/{input.seq}.iqtree)"
            iqtree2mod -s {input.seq}_results/{input.seq} --seqtype DNA -m MFP -mrate E,I,G,I+G,R --keep-ident --seed $seed_num --prefix {input.seq}_results/{input.seq}-keep_ident > {input.seq}_results/{input.seq}-keep_ident.iqlog
        fi;
        """

rule ParseParameters: 
"""
This rule ensures that once the TestModelsAndTreeSearch rule has finished successfully, all relevant parameters is filtered 
from the output files. The input is the alignment file and *.treefile and *iqtree file generated in the TestModelsAndTreeSearch rule.
The parse_parameters.py script takes the name of the alignment file as input and searches for all relevant outout files generated by 
IQ-Tree. It then parses out all relevent information an writes it into the correspoinding *.tsv output files. The declared output 
includes all *tsv files. Therefore, the results will be deleted should the rule fail. This to ensure that the output files
are only filled with data that has been successfully filtered out.  
"""
    output: "{alignment_ID}_results/{alignment_ID}_ali_parameters.tsv", "{alignment_ID}_results/{alignment_ID}_seq_parameters.tsv", \
    "{alignment_ID}_results/{alignment_ID}_model_parameters.tsv", "{alignment_ID}_results/{alignment_ID}_branch_parameters.tsv", \
    "{alignment_ID}_results/{alignment_ID}_tree_parameters.tsv"
    input: 
        seq = "{alignment_ID}",
        treefile = "{alignment_ID}_results/{alignment_ID}.treefile",
        iqtree_file = "{alignment_ID}_results/{alignment_ID}.iqtree",
    shell: """
        parse_parameters.py --prefix {input.seq}_results/{input.seq} --output {input.seq}_results/{input.seq}
        """
