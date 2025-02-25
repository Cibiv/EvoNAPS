
#!/bin/bash

# Example bash script to start the EvoNAPS Snakemake worfkflow on all dna fasta alignments found in the folder given as input.

if [ 0 -ne $# ]
      then folder=$1
      else echo Missing folder name as input
fi

cd $folder 
for file in *fasta; do 
      echo $i 
      snakemake --snakefile EvoNAPS_snakefile_dna.smk $file'_results/'$file'_ali_parameters.tsv' --cores 1 >> workflow.log 2>&1;
done 
