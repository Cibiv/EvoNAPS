
# **The EvoNAPS database**

## **Overview**

The EvoNAPS database is a comprehensive resource that contains multiple sequence alignments, their inferred phylogenetic trees, and parameter settings for hundreds of different sequence evolution models. It is designed to provide benchmark materials for phylogenetic research and meaningful parameters based on biological data for sequence simulations.

## **Content**

- [Introduction](#introduction)
- [System Setup](#system-setup)
    - [Database Setup](#database-setup)
    - [Workflow Setup](#workflow-setup)
- [Workflow Manual](#workflow-manual)
- [References](#references)

## **Introduction**

The EvoNAPS database holds a vast number of DNA and protein alignments along with a corresponding inferred phylogenetic tree. The alignments were gathered from various existing databases, including:

- Online repository by Rob Lanfear (Lanfear, 2019)
- PANDIT (Whelan, 2006)
- OrthoMaM (Scornavacca et al., 2019; Allio et al., 2023)
- TreeBASE (Piel et al., 2002)

This repository includes the MySQL database skeleton and a Snakemake workflow to facilitate the import of new alignments into the database.

For more details on the structure of the EvoNAPS database, refer to [EvoNAPS_tables.md](./database/EvoNAPS_tables.md).

## **System Setup**

Should you wish to set up the EvoNAPS database or also the workflow, you will need an [MariaDB](https://github.com/MariaDB/server) (or MySQL) and a [Snakemake](https://github.com/snakemake/snakemake) instance installed on your computer.

### **Database Setup**

The source code of MariaDB as well as links to an installation guide can be found [here](https://github.com/MariaDB/server). An installation guide for MySQL can be found [here](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/).

Once a MariaDB (or MySQL) server is running on your computer, you can create the EvoNAPS database using the create statements as found in file [evonaps_create_statements.sql](database/evonaps_create_statements.sql).

First, log onto the database server. Type in your shell: 

```bash
$ mysql -u your_user -p
```

Then, inside the MySQL promt create the database:

```console
CREATE DATABASE evonaps;
EXIT;
```

Next import the MySQL dump (only holding the create statements):

```bash
$ mysql -u your_user -p --database=evonaps < evonaps_create_statements.sql
```

You can verify if the tables were corretly created by typing: 

```bash
$ mysql -u your_user -p -e "USE evonaps; SHOW TABLES;"
```

Compare the results with the ones stated in [EvoNAPS_tables.md](./database/EvoNAPS_tables.md).

Now you have an empty EvoNAPS database with all the table structures but no data!

### **Workflow Setup**

The workflow requires a [Snakemake](https://github.com/snakemake/snakemake) instance. The recommended way to install Snakemake is by using [miniconda](https://github.com/conda-forge/miniforge). Furthermore, since the workflow requires conda to create different environments for different rules, an installation of conda is required. 

Should you not have conda installed on your computer, here is a full guide on how to install [miniconda](https://www.anaconda.com/docs/getting-started/miniconda/install). 

Once conda is set up on your computer, you can create a new environment using the yml file provided in this repository in [snakemake.yml](snakemake.yml). Each library in the .yml file comes with a specific version, which are the versions that were used to test the workflow. The environment includes a Snakemake instance. 

```bash
$ conda activate base
$ conda env create -f snakemake.yml
$ conda activate skamekamke-env
```

The workflow requires external software, namely [IQTree2](https://github.com/iqtree/iqtree2) (Minh, 2020), [RAxML-NG](https://github.com/amkozlov/raxml-ng) (Kozlov,2019), and the lightweight Python module [PyPythia](https://github.com/tschuelia/PyPythia) (Haag, 2022). For both IQTree2 and RAxML-NG a binary file is already included in [bin](workflow/bin). IQTree2 (v. 2.2.0.5) was modified to output additional parameters and information. The modified source code can be found at [FranziskaReden/iqtree2](https://github.com/FranziskaReden/iqtree2). The RAxML-NG version used in this workflow is 1.2.1, and the Pythia version is 1.1.4 (also see [pypythia.yaml](workflow/env/pyoythia.yml) for more details).

Please ensure that the binary files are executable:

```bash
$ chmod +x workflow/bin/iqtree2mod
$ chmod +x workflow/bin/raxml-ng
```

## **Workflow Manual** 

This section explains how to start the workflow on a given input alignment given the steps as described in [System setup](#system-setup) have been taken. 

### **Overview** 

This Snakemake workflow was designed to be applied to a target alignment the user wishes to import to
the EvoNAPS database. Note, that this workflow assumes that the EvoNAPS database already exists on your 
system.

The workflow includes all necessary scripts, software, and *.yaml files to run. 
- Third-party software can be found in [workflow/bin](workflow/bin). 
- The Python scripts can be found in [workflow/scripts](workflow/scripts). 
- The instructions to create the necessary conda environments (yaml files) can be found in [workflow/envs](workflow/envs). 
- The Snakemake rules for this workflow are stored in [workflow/rules](workflow/rules).
- The [config](config) folder holds necessary files for the workflow to run.

### **Usage**

The workflow can be applied to a multiple sequence alignment of either DNA or protein sequences and should work on any eligible sequence alignment both in `PHY` and `FASTA` format, although `FASTA` format is recommended. 

Note, that before running the workflow on an alignment, the config/EvoNAPS_credentials.cnf file
needs to be created. This file includes all necessary information to allow access to the target
database. An example file is given in [config/EvoNAPS_credentials_example.cnf](config/EvoNAPS_credentials_example.cnf). Exchange the names of the credentials according to your database setup and rename the file to 
config/EvoNAPS_credentials.cnf.

```bash
$ mv config/EvoNAPS_credentials_example.cnf config/EvoNAPS_credentials.cnf
```

It is recommended to create a folder which is empty except for the target alignment file (and potentially an info file and a file holding taxon IDs - see [Options](#options) for more details). All output files will be written into said folder. The Snakemake workflow can be applied onto the alignment as such:

```bash
$ snakemake PATH/TO/alignment.fasta_summary.txt --config seq_type=DNA --use-conda
```

The target file `PATH/TO/alignment.fasta_summary.txt` is used if you wish
to import the alignment into the EvoNAPS database directly. 

Use `PATH/TO/alignment.fasta_ali_parameters.tsv` as target file if you only wish to run IQTree2 
and parse out all alignment and tree features only: 

```bash
$ snakemake PATH/TO/alignment.fasta_ali_parameters.tsv --config seq_type=DNA --use-conda
```

Use `PATH/TO/alignment.fasta.pythia` if you only wish to calculate the Pythia 
difficulty score.

```bash
$ snakemake PATH/TO/alignment.fasta.pythia --config seq_type=DNA --use-conda
```

Note, that the sequence type needs to be set manually. Declare the 
sequence type with `--config seq_type=[ DNA | AA ]` from the command line. 

Also note, that the flag `--use-conda` is necessary because different environments 
are used for different rules, which requires access to conda. 

Additional information regarding the alignment can be provided as will be further discussed in the next section.

### **Options**

#### **Providing an info file**

Various statistics regarding the alignment will be gathered based on the alignment alone (as calculated by IQTree2, the Python scripts in [workflow/scripts](workflow/scripts), and PyPythia). However, there is also an option to provide additional meta-data on the alignment such as a description of the alignment (`DESCRIPTION`), the study the alignment might stem from (`STUDY_ID`), the name of the alignment under which you wish to save it in the database (`ALI_ID`) etc. These additional alignment features can be provided in the `info file`. The file should bear the same name as the alignment and have the ending `.info`. The Snakemake workflow will search for such a file in the same folder as the alignment:

```
user@host:~/PATH/TO/$ ls -l
alignment.fasta
alignment.fasta.info
```

An example `info file` is provided in the config folder under [conig/example.info](config/example.info). The file lists all optional features that can be provided. Simply exchange the example features with features you wish to provide and copy the file into the folder holding the target alignment file. Note, that all lines are set in the example file. If you wish to only set a few features, but not all, please do not forget to commented out (`#`) the remaining features. Do not forget to rename the file according to the name of the target alignment file. Otherwise, the file will be ignored. The file will be considered in the final step, when the alignment is imported into the EvoNAPS database.

#### **Providing taxon IDs**

Besides meta-data on the alignment, the user can also provide a list of taxon IDs for each (or some) sequences in the alignment. The taxon IDs (in accordance to the NCBI taxonomy database) can be provided in a `csv` file. Similarly to the `info file` it is expected to have the same name as the alignment file and to additionally bear the ending `.tax`. 

```
user@host:~/PATH/TO/$ ls -l
alignment.fasta
alignment.fasta.tax
```

An example of how such a `tax` file is supposed to be formatted can be found in [config/example.tax](config/example.tax). Note, that all lines starting with `#` at the top of the file will be ignored. The file will be considered in the feature parsing step after IQTree2 had been run on the alignment. The taxon IDs will be matched to the sequence name as it appears in the alignment. Accordingly, the names provided in the `tax` file need to match the names as found in the alignment file exactly. For more details please refer to the example file.

## **References**

- Allio, R., Delsuc, F., Belkhir, K., Douzery, E. J. P., Ranwez, V., and Scornavacca, C.
(2023). OrthoMaM v12: a database of curated single-copy ortholog alignments and trees
to study mammalian evolutionary genomics. Nucleic Acids Research, page gkad834.

- Haag, J., Höhler, D., Bettisworth, B., and Stamatakis, A. (2022). From easy to hopeless—predicting the difficulty of phylogenetic analyses. Molecular Biology and Evolution, 39(12):msac254.

- Kozlov, A. M., Darriba, D., Flouri, T., Morel, B., and Stamatakis, A. (2019). RAxML-NG: a fast, scalable and user-friendly tool for maximum likelihood phylogenetic inference. Bioinformatics, 35(21):4453–4455.

- Lanfear, R. (2019). BenchmarkAlignments. https://github.com/roblanf/BenchmarkAlignments/

- Minh, B. Q., Schmidt, H. A., Chernomor, O., Schrempf, D., Woodhams, M. D., von Haeseler, A., & Lanfear, R. (2020). IQ-TREE 2: New Models and Efficient Methods for Phylogenetic Inference in the Genomic Era. Mol Biol Evol, 37(5), 1530-1534. https://doi.org/10.1093/molbev/msaa015

- Peter J. A. Cock, Tiago Antao, Jeffrey T. Chang, Brad A. Chapman, Cymon J. Cox, Andrew Dalke, Iddo Friedberg, Thomas Hamelryck, Frank Kauff, Bartek Wilczynski, Michiel J. L. de Hoon, Biopython: freely available Python tools for computational molecular biology and bioinformatics, Bioinformatics, Volume 25, Issue 11, June 2009, Pages 1422–1423, https://doi.org/10.1093/bioinformatics/btp163

- Piel, W. H., Donoghue, M., and Sanderson, M. (2002). TreeBASE: a database of phylogenetic knowledge. In Shimura, J., Wilson, K., and Gordon, D., editors, The Interoperable ”Catalog of Life” with partners – Species 2000 Asia Oceania, Research Report, National Institute for Environmental Studies, pages 41–47, Tsukuba, Japan.

- Scornavacca, C., Belkhir, K., Lopez, J., Dernat, R., Delsuc, F., Douzery, E. J. P., & Ranwez, V. (2019). OrthoMaM v10: Scaling-Up Orthologous Coding Sequence and Exon Alignments with More than One Hundred Mammalian Genomes. Molecular biology and evolution, 36(4), 861–862. https://doi.org/10.1093/molbev/msz015

- Whelan, S., Bakker, P., Quevillon, E., Rodriguez, N., & Goldman, N. (2006). PANDIT: an evolution-centric database of protein and associated nucleotide domains with inferred trees. Nucleic acids research, 34, D327-331. https://doi.org/10.1093/nar/gkj087 
