# **Tables of the EvoNAPS database**

## **Overview**

You can find a detailed description of the tables of the EvoNAPS database in the following chapters: 

1. [dataorigin](#dataorigin)
2. [aa_models](#2-aa_models)
3. [dna_models](#3-dna_models)
4. [aa_alignments](#4-aa_alignments)
5. [dna_alignments](#5-dna_alignments)
6. [aa_sequences](#6-aa_sequences)
7. [dna_sequences](#7-dna_sequences)
8. [aa_modelparameters](#8-aa_modelparameters)
9. [dna_modelparameters](#9-dna_modelparameters)
10. [aa_trees](#10-aa_trees)
11. [dna_trees](#11-dna_trees)
12. [aa_branches](#12-aa_branches)
13. [dna_branches](#13-dna_branches)

Note that, sometimes the tables containing information regarding DNA and protein alignments are  identical (e.g., *alignments* tables). However, in some tables there are differences in the number and kind of columns (e.g., *sequences*, *modelparameters*, *trees* tables). 

### **Abbreviations**:

* **PK**: primary key
* **NN**: not null
* **UQ**: unique key
* **AI**: auto-incremented
* **+**: applies to
* **\***: is involved in

## **1. dataorigin**

**Comment**: The dataorigin table holds information regarding the original sources of the alignments in the EvoNAPS database.

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**DATABASE\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**DATABASE\_ID** | varchar\(100\) |  | \+ | \+ |  |  | This field holds the name of the source database, which in turn serves as the ID of said database\. The entries of this column must be unique\. 
**DOI** | varchar\(100\) |  |  |  |  | NULL | States the DOI of the paper describing the source database, should there exist one\. 
**PUBMED\_ID** | varchar\(100\) |  |  |  |  | NULL | States the PUBMED\-ID of the paper describing the source database, should there exist one\. 
**LAST\_UPDATED** | varchar\(100\) |  |  |  |  | NULL | States the date the source database was last updated, if available\. 
**SEQ\_TYPE** | varchar\(100\) |  |  |  |  | NULL | States whether the source database holds of DNA and/or protein alignments\. 
**DESCRIPTION** | text |  |  |  |  | NULL | A text field that gives a short description of the source database\. 
**SIZE** | text |  |  |  |  | NULL | States the number of alignments the source database holds\. 
**COMMENT** | text |  |  |  |  | NULL | An optional text field for any comments regarding the source database\.                                                       

## **2. aa_models**

**Comment**: The aa_models table lists the different protein substitution rate matrices that were tested in the EvoNAPS workflow and includes the assumed amino acid frequencies and substitution rates.

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**MODEL\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**MODEL\_NAME** | varchar\(100\) |  | \+ | \+ |  |  | Name of the protein model \(substitution rate matrix\)\. The name must be unique\. 
**REGION** | varchar\(50\) |  |  |  |  | NULL | States the region of the cell where the proteins from which the substitution rate matrix was derived from are abundant\. Optional, default is NULL\. 
**EXPLANATION** | varchar\(100\) |  |  |  |  | NULL | This field contains a short description of the model\. 
**STAT\_DIS\_TYPE** | varchar\(50\) |  |  |  |  |  | This field states whether the state frequencies of the stationary distribution assumed in the model are empirical \(counted frequencies from the alignment\) or if they are predefined by the model\. 
**FREQ\_A** | decimal\(10,9\) |  |  |  |  | NULL | Either NULL if STAT\_DIS\_TYPE='empirical'\. Else, the frequency of the amino acid alanine \(A\) assumed by the model\. 
**…** |  |  |  |  |  |  | … 
**FREQ\_V** | decimal\(10,9\) |  |  |  |  | NULL | Either NULL if STAT\_DIS\_TYPE='empirical'\. Else, the frequency of the amino acid tyrosine \(Y\) assumed by the model\. 
**RATE\_AR** | DECIMAL\(15,9\) |  | \+ |  |  |  | The substitution rate from aa A to aa R assumed by the model\. 
**…** |  |  |  |  |  |  | … 
**RATE\_YV** | DECIMAL\(15,9\) |  | \+ |  |  |  | The substitution rate from aa Y to aa V assumed by the model\. 

## **3. dna_models**

**Comment**: The dna_models table lists the different DNA substitution rate matrices that were tested in the EvoNAPS workflow.

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**MODEL\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**MODEL\_NAME** | varchar\(100\) |  | \+ |  |  |  | Name of the DNA model \(substitution rate matrix\)\. The name must be unique\. 
**FREE\_PARAMETERS** | int\(11\) |  | \+ |  |  |  | States the number of free parameters of the model\. 
**BASE\_FREQUENCIES** | varchar\(30\) |  | \+ |  |  |  | States whether the assumed base frequencies of the model are uniform  \(0\.25 for each base\) or unequal\. 
**SUBSTITUTION\_RATES** | varchar\(100\) |  | \+ |  |  |  | States \(possible\) restrictions the model has on the substitution rates\. 
**EXPLANATION** | varchar\(100\) |  |  |  |  | NULL | This field contains a short description of the model\. 
**SUBSTITUTION\_CODE** | varchar\(100\) |  | \+ |  |  |  | This field shows the substitution code of the rate matrix\. 

## **4. aa_alignments**

**Comment**: The aa_alignments table holds general information and characteristics regarding each protein alignment in the database.

**Constraints:**

* **FOREIGN KEY** (*FROM_DATABASE*) REFERENCES **dataorigin** (*DATABASE_ID*) 

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**ALI\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**ALI\_ID** | varchar\(250\) |  | \+ | \+ |  |  | Name of the alignment \(alignment ID\)\. Must be unique\. 
**FROM\_DATABASE** | varchar\(100\) |  | \+ |  |  |  | States from which original database the alignemnt stems from \(e\.g\. PANDIT\)\. Serves as foreign key to connect to the dataorigin table\. 
**DESCRIPTION** | varchar\(100\) |  |  |  |  | NULL | A field that can hold an optional comment regarding the alignment\. This can be left blank and the default value is accordingly NULL\. 
**SEQUENCES** | int\(11\) |  | \+ |  |  |  | This column states how many seqeunces \(taxa\) the alignemnt holds\. 
**COLUMNS** | int\(11\) |  | \+ |  |  |  | This column states how many sites \(columns\) the alignemnt has / states the length of the alignment\. 
**PARSIMONY\_INFORMATIVE\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of parsimony informative sites in alignment\. 
**SINGELTON\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of singelton sites in alignment\. 
**CONSTANT\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of singelton sites in alignment\. 
**FRAC\_WILDCARDS\_GAPS** | decimal\(5,4\) |  | \+ |  |  |  | States the fraction of wildcards and gaps in the alignment\. 
**DISTINCT\_PATTERNS** | int\(11\) |  | \+ |  |  |  | States the number of distinct patterns in alignment\. 
**FAILED\_CHI2** | int\(11\) |  | \+ |  |  |  | States the number of sequences that failed the chi2 \(chi\-squared\) test\. The test examines whether the nucleotide composition of the sequences matches the mean nucleotide frequencies across all sequences\. 
**IDENTICAL\_SEQ** | int\(11\) |  |  |  |  | NULL | States the number of identical sequences in the alignment, should there be any\. Default is NULL\. 
**EXCLUDED\_SEQ** | int\(11\) |  |  |  |  | NULL | States the number of excluded sequences in the alignment, should there be any\. Default is NULL\. 

## **5. dna_alignments**

**Comment**: The dna_alignments table holds general information and characteristics regarding each DNA alignment in the database.

**Constraints:**

* **FOREIGN KEY** (*FROM_DATABASE*) REFERENCES **dataorigin** (*DATABASE_ID*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**ALI\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**ALI\_ID** | varchar\(250\) |  | \+ | \+ |  |  | Name of the alignment \(alignment ID\)\. Must be unique\. 
**FROM\_DATABASE** | varchar\(100\) |  | \+ |  |  |  | States from which original database the alignemnt stems from \(e\.g\. PANDIT\)\. Serves as foreign key to connect to the dataorigin table\. 
**DESCRIPTION** | varchar\(100\) |  |  |  |  | NULL | A field that can hold an optional comment regarding the alignment\. This can be left blank and the default value is accordingly NULL\. 
**SEQUENCES** | int\(11\) |  | \+ |  |  |  | This column states how many seqeunces \(taxa\) the alignemnt holds\. 
**COLUMNS** | int\(11\) |  | \+ |  |  |  | This column states how many sites \(columns\) the alignemnt has / states the length of the alignment\. 
**PARSIMONY\_INFORMATIVE\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of parsimony informative sites in alignment\. 
**SINGELTON\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of singelton sites in alignment\. 
**CONSTANT\_SITES** | int\(11\) |  | \+ |  |  |  | States the number of singelton sites in alignment\. 
**FRAC\_WILDCARDS\_GAPS** | decimal\(5,4\) |  | \+ |  |  |  | States the fraction of wildcards and gaps in the alignment\. 
**DISTINCT\_PATTERNS** | int\(11\) |  | \+ |  |  |  | States the number of distinct patterns in alignment\. 
**FAILED\_CHI2** | int\(11\) |  | \+ |  |  |  | States the number of sequences that failed the chi2 \(chi\-squared\) test\. The test examines whether the nucleotide composition of the sequences matches the mean nucleotide frequencies across all sequences\. 
**IDENTICAL\_SEQ** | int\(11\) |  |  |  |  | NULL | States the number of identical sequences in the alignment, should there be any\. Default is NULL\. 
**EXCLUDED\_SEQ** | int\(11\) |  |  |  |  | NULL | States the number of excluded sequences in the alignment, should there be any\. Default is NULL\. 

## **6. aa_sequences** 

**Comment**: The aa_sequences table holds the sequences of each protein alignment in the EvoNAPS database as well as information regarding each sequence.

**Constraints:**

* **UNIQUE KEY** (*ALI_ID*,*SEQ_INDEX*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **aa_alignments** (*ALI_ID*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**SEQ\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**ALI\_ID** | varchar\(250\) |  | \+ | * |  |  | Name of the alignment \(alignment ID\)\. Serves as foreign key to connect to the aa\_alignments table\. 
**SEQ\_INDEX** | int\(11\) |  | \+ | * |  |  | This column holds the unique index \(integer starting with 1\) for each sequence of an alignment\. 
**SEQ\_NAME** | varchar\(250\) |  | \+ |  |  |  | States the name of the sequence as it appears in the original alignment\. 
**FRAC\_WILDCARDS\_GAPS** | decimal\(10,9\) |  |  |  |  | NULL | States the fraction of wildcards and gaps in the sequence\. 
**CHI2\_P\_VALUE** | decimal\(7,2\) |  |  |  |  | NULL | States the p\-value of the Chi-Square test for the sequence\. The Chi-Square test tests whether the amino acid composition of the sequence fits the mean aa frequencies across all sequences in the alignment\. 
**CHI2\_PASSED** | tinyint\(1\) |  |  |  |  | NULL | States whether the sequence passed \(1\) or failed \(0\) the Chi-Square test\.  
**EXCLUDED** | int\(11\) |  |  |  |  | NULL | States whether the sequence has been excluded from IQ\-Tree calculations \(without the flag \*\-\-keep\-ident\*\)\. IQ\-Tree excludes a sequence from its computations if there already exist at least two identical sequences in the alignment\. 
**IDENTICAL\_TO** | varchar\(10000\) |  |  |  |  | NULL | States to which sequence\(s\) the sequence is identical to, if such \(a\) sequence\(s\) exist\(s\)\. 
**FREQ\_A** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the amino acid alanine \(A\) in the sequence\. 
**…** |  |  |  |  |  |  | … 
**FREQ\_V** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the amino acid tyrosine \(V\) in the sequence\. 
**SEQ** | mediumtext |  | \+ |  |  |  | This text field contains the sequence \(with wildcards and gaps\) as it appears in the alignment\. 

## **7. dna_sequences** 

**Comment**: The dna_sequences table holds the sequences of each DNA alignment in the EvoNAPS database as well as information regarding each sequence.

**Constraints:**

* **UNIQUE KEY** (*ALI_ID*,*SEQ_INDEX*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **dna_alignments** (*ALI_ID*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**SEQ\_KEY** | int\(11\) | \+ | \+ |  | \+ |  | Autoincremented primary key\. 
**ALI\_ID** | varchar\(250\) |  | \+ | * |  |  | Name of the alignment \(alignment ID\)\. Serves as foreign key to connect to the dna\_alignments table\. 
**SEQ\_INDEX** | int\(11\) |  | \+ | * |  |  | This column holds the unique index \(integer starting with 1\) for each sequence of an alignment\. 
**SEQ\_NAME** | varchar\(250\) |  | \+ |  |  |  | States the name of the sequence as it appears in the original alignment\. 
**FRAC\_WILDCARDS\_GAPS** | decimal\(10,9\) |  |  |  |  | NULL | States the fraction of wildcards and gaps in the sequence\. 
**CHI2\_P\_VALUE** | decimal\(7,2\) |  |  |  |  | NULL | States the p\-value of the Chi-Square test for the sequence\. The Chi-Square test tests whether the nucleotide composition of the sequence fits the mean dna frequencies across all sequences in the alignment\. 
**CHI2\_PASSED** | tinyint\(1\) |  |  |  |  | NULL | States whether the sequence passed \(1\) or failed \(0\) the Chi-Square test\.  
**EXCLUDED** | int\(11\) |  |  |  |  | NULL | States whether the sequence has been excluded from IQ\-Tree calculations \(without the flag \*\-\-keep\-ident\*\)\. IQ\-Tree excludes a sequence from its computations if there already exist at least two identical sequences in the alignment\. 
**IDENTICAL\_TO** | varchar\(10000\) |  |  |  |  | NULL | States to which sequence\(s\) the sequence is identical to, if such \(a\) sequence\(s\) exist\(s\)\. 
**FREQ\_A** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the base adenine \(A\) in the sequence\. 
**FREQ\_C** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the base cytosine \(C\) in the sequence\. 
**FREQ\_G** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the base guanine \(G\) in the sequence\. 
**FREQ\_T** | decimal\(10,9\) |  | \+ |  |  |  | The frequency of the base thymine \(T\) in the sequence\. 
**SEQ** | mediumtext |  | \+ |  |  |  | This text field contains the sequence \(with wildcards and gaps\) as it appears in the alignment\. 

## **8. aa_modelparameters** 

**Comment**: The aa_modelparameters table holds the results of model selection. The performance of each evaluated model (LogL, AIC, BIC,...) is clearly documented as well as the parameters of the model (state frequencies, rates, shape parameter alpha,...).

**Constraints:**

* **KEY** (*BASE_MODEL*)
* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*MODEL*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **aa_alignments** (*ALI_ID*)
* **FOREIGN KEY** (*BASE_MODEL*) REFERENCES **aa_models** (*MODEL_NAME*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**MODELTEST_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**IQTREE_VERSION** | varchar(100) |  | + |  |  |  |  
**RANDOM_SEED** | int(11) |  | + |  |  |  | The random number seed used by IQ-Tree.  
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp enables mapping of the tested model to one IQ-Tree run. 
**MODEL_TYPE** | varchar(100) |  | + |  |  |  | The type of model testing or the type of models that were tested in the IQ-Tree run. Will mostly be MF (the models included in the default ModelFinder algorithm).  
**KEEP_IDENT** | tinyint(1) |  |  |  |  | NULL | Boolean stating whether the *--keep-ident* flag has been enabled (1) or disabled (0) in the IQ-Tree run.  
**MODEL** | varchar(100) |  | + | * |  |  | Name of the tested model 
**BASE_MODEL** | varchar(100) |  | + |  |  |  | Name of the substitution rate matrix used in the model.  
**MODEL_RATE_HETEROGENEITY** | varchar(100) |  |  |  |  | NULL | Name of the model of rate heterogeneity (should one have been employed). 
**NUM_RATE_CAT** | int(11) |  |  |  |  | NULL | Number of rate categories assumed by the model. 
**LOGL** | decimal(21,9) |  | + |  |  |  | Logarithmic likelihood 
**AIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_AIC** | float |  | + |  |  |  |  
**CONFIDENCE_AIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted AIC is above 0.05 (1) or under (0). 
**AICC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_AICC** | float |  | + |  |  |  |  
**CONFIDENCE_AICC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted AICC is above 0.5 (1) or under (0). 
**BIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_BIC** | float |  | + |  |  |  |  
**CONFIDENCE_BIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted BIC is above 0.05 (1) or under (0). 
**CAIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_CAIC** | float |  | + |  |  |  |  
**CONFIDENCE_CAIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted CAIC is above 0.05 (1) or under (0). 
**ABIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_ABIC** | float |  | + |  |  |  |  
**CONFIDENCE_ABIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted ABIC is above 0.05 (1) or under (0). 
**NUM_FREE_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters (=NUM_MODEL_PARAMETERS+NUM_BRANCHES). 
**NUM_MODEL_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters of the model of sequence evolution 
**NUM_BRANCHES** | int(11) |  | + |  |  |  | Number of branches in the phylogenetic tree. In a fully resolved tree: 2n-3 (with n taxa). 
**TREE_LENGTH** | decimal(15,9) |  | + |  |  |  | Length of the tree (might differ for the different models as the branch lengths are being re-estimated during model evaluation). 
**PROP_INVAR** | decimal(10,9) |  |  |  |  | NULL | Proportion of invariable sites in case the *+I* model of rate heterogeneity was employed. Else, NULL. 
**ALPHA** | decimal(15,9) |  |  |  |  | NULL | Shape parameter alpha should an Gamma *+G4* model have been employed. Else, NULL. 
**STAT_FREQ_TYPE** | varchar(100) |  | + |  |  |  | This field states whether the state frequencies of the stationary distribution assumed in the model are *empirical* (counted frequencies from the alignment) or if they are predefined by the model (*model*).  
**STAT_FREQ_A** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the amino acid alanine (A) assumed by the model. 
**…** |  |  |  |  |  |  | … 
**STAT_FREQ_V** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the amino acid tyrosine (V) assumed by the model. 
**PROP_CAT_1** | decimal(10,9) |  |  |  |  | NULL | The proportion of the first rate category (should the model assume different rates across sites). 
**REL_RATE_CAT_1** | decimal(15,9) |  |  |  |  | NULL | The rate of the first rate category (should the model assume different rates across sites). 
**…** |  |  |  |  |  |  | … 
**PROP_CAT_10** | decimal(10,9) |  |  |  |  | NULL | The proportion of the tenth rate category (should there exist one). 
**REL_RATE_CAT_10** | decimal(15,9) |  |  |  |  | NULL | The rate of the tenth rate category (should there exist one). 

## **9. dna_modelparameters** 

**Comment**: The dna_modelparameters table holds the results of model selection. The performance of each evaluated model (LogL, AIC, BIC,...) is clearly documented as well as the parameters of the model (state frequencies, rates, shape parameter alpha,...).

**Constraints:**

* **KEY** (*BASE_MODEL*)
* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*MODEL*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **dna_alignments** (*ALI_ID*)
* **FOREIGN KEY** (*BASE_MODEL*) REFERENCES **dna_models** (*MODEL_NAME*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**MODELTEST_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**IQTREE_VERSION** | varchar(100) |  | + |  |  |  |  
**RANDOM_SEED** | int(11) |  | + |  |  |  | The random number seed used by IQ-Tree.  
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp enables mapping of the tested model to one IQ-Tree run. 
**MODEL_TYPE** | varchar(100) |  | + |  |  |  | The type of model testing or the type of models that were tested in the IQ-Tree run. Will mostly be MF (the models included in the default ModelFinder algorithm).  
**KEEP_IDENT** | tinyint(1) |  |  |  |  | NULL | Boolean stating whether the *--keep-ident* flag has been enabled (1) or disabled (0) in the IQ-Tree run.  
**MODEL** | varchar(100) |  | + | * |  |  | Name of the tested model 
**BASE_MODEL** | varchar(100) |  | + |  |  |  | Name of the substitution rate matrix used in the model.  
**MODEL_RATE_HETEROGENEITY** | varchar(100) |  |  |  |  | NULL | Name of the model of rate heterogeneity (should one have been employed). 
**NUM_RATE_CAT** | int(11) |  |  |  |  | NULL | Number of rate categories assumed by the model. 
**LOGL** | decimal(21,9) |  | + |  |  |  | Logarithmic likelihood 
**AIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_AIC** | float |  | + |  |  |  |  
**CONFIDENCE_AIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted AIC is above 0.05 (1) or under (0). 
**AICC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_AICC** | float |  | + |  |  |  |  
**CONFIDENCE_AICC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted AICC is above 0.5 (1) or under (0). 
**BIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_BIC** | float |  | + |  |  |  |  
**CONFIDENCE_BIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted BIC is above 0.05 (1) or under (0). 
**CAIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_CAIC** | float |  | + |  |  |  |  
**CONFIDENCE_CAIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted CAIC is above 0.05 (1) or under (0). 
**ABIC** | decimal(21,9) |  | + |  |  |  |  
**WEIGHTED_ABIC** | float |  | + |  |  |  |  
**CONFIDENCE_ABIC** | tinyint(1) |  | + |  |  |  | Boolean stating whether the weighted ABIC is above 0.05 (1) or under (0). 
**NUM_FREE_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters (=NUM_MODEL_PARAMETERS+NUM_BRANCHES). 
**NUM_MODEL_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters of the model of sequence evolution 
**NUM_BRANCHES** | int(11) |  | + |  |  |  | Number of branches in the phylogenetic tree. In a fully resolved tree: 2n-3 (with n taxa). 
**TREE_LENGTH** | decimal(15,9) |  | + |  |  |  | Length of the tree (might differ for the different models as the branch lengths are being re-estimated during model evaluation). 
**PROP_INVAR** | decimal(10,9) |  |  |  |  | NULL | Proportion of invariable sites in case the *+I* model of rate heterogeneity was employed. Else, NULL. 
**ALPHA** | decimal(15,9) |  |  |  |  | NULL | Shape parameter alpha should an Gamma *+G4* model have been employed. Else, NULL. 
**STAT_FREQ_TYPE** | varchar(100) |  | + |  |  |  | This field states whether the state frequencies of the stationary distribution assumed in the model are *empirical* (counted frequencies from the alignment) or if they are predefined by the model (*model*).  
**STAT_FREQ_A** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the base adenine (A) assumed by the model. 
**STAT_FREQ_C** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the base guanine (G) assumed by the model. 
**STAT_FREQ_G** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the base cytosine (C) assumed by the model. 
**STAT_FREQ_T** | decimal(10,9) |  | + |  |  |  | The stationary frequency of the base thymine (T) assumed by the model. 
**RATE_AC** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from A to C. 
**RATE_CA** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from C to A. 
**…** |  |  |  |  |  |  | … 
**RATE_GT** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from G to T. 
**RATE_TG** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from T to G. 
**PROP_CAT_1** | decimal(10,9) |  |  |  |  | NULL | The proportion of the first rate category (should the model assume different rates across sites). 
**REL_RATE_CAT_1** | decimal(15,9) |  |  |  |  | NULL | The rate of the first rate category (should the model assume different rates across sites). 
**…** |  |  |  |  |  |  | … 
**PROP_CAT_10** | decimal(10,9) |  |  |  |  | NULL | The proportion of the tenth rate category (should there exist one). 
**REL_RATE_CAT_10** | decimal(15,9) |  |  |  |  | NULL | The rate of the tenth rate category (should there exist one). 

## **10. aa_trees** 

**Comment**: The aa_trees table contains a set of phylogenetic trees as well as the  parameters of the assumed model of sequence evolution. The trees are either a fast-ML tree used in the model evaluation or a maximum likelihood (ML) tree inferred using the best-fit model.

**Constraints:**

* **KEY** (*BASE_MODEL*)
* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*TREE_TYPE*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **aa_alignments** (*ALI_ID*)
* **FOREIGN KEY** (*BASE_MODEL*) REFERENCES **aa_models** (*MODEL_NAME*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**TREE_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**IQTREE_VERSION** | varchar(100) |  | + |  |  |  |  
**RANDOM_SEED** | int(11) |  | + |  |  |  | The random number seed used by IQ-Tree.  
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp enables mapping of the tested model to one IQ-Tree run. 
**MODEL_TYPE** | varchar(100) |  | + |  |  |  | The type of model testing or the type of models that were tested in the IQ-Tree run. Will mostly be MF (the models included in the default ModelFinder algorithm).  
**TREE_TYPE** | varchar(100) |  | + | * |  |  | States the type of the tree. It is either *initial* (fast ML tree used for model evaluation) or *ML* (maximum likelihood tree). 
**CHOICE_CRITERIUM** | varchar(100) |  |  |  |  | NULL | States the choice criterium used to select the model for the ML tree search. In case of an *initial* tree, this field is left empty (NULL). 
**KEEP_IDENT** | tinyint(1) |  |  |  |  | NULL | Boolean stating whether the *--keep-ident* flag has been enabled (1) or disabled (0) in the IQ-Tree run.  
**MODEL** | varchar(100) |  | + |  |  |  | Name of the tested model 
**BASE_MODEL** | varchar(100) |  | + |  |  |  | Name of the substitution rate matrix used in the model.  
**MODEL_RATE_HETEROGENEITY** | varchar(100) |  |  |  |  | NULL | Name of the model of rate heterogeneity (should one have been employed). 
**NUM_RATE_CAT** | int(11) |  |  |  |  | NULL | Number of rate categories assumed by the model. 
**LOGL** | decimal(21,9) |  | + |  |  |  | Logarithmic likelihood 
**UNCONSTRAINED_LOGL** | decimal(21,9) |  |  |  |  |  | Unconstrained logarithmic likelihood 
**AIC** | decimal(21,9) |  | + |  |  |  |  
**AICC** | decimal(21,9) |  | + |  |  |  |  
**BIC** | decimal(21,9) |  | + |  |  |  |  
**CAIC** | decimal(21,9) |  |  |  |  | NULL |  
**ABIC** | decimal(21,9) |  |  |  |  | NULL |  
**NUM_FREE_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters (=NUM_MODEL_PARAMETERS+NUM_BRANCHES). 
**NUM_MODEL_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters of the model of sequence evolution 
**NUM_BRANCHES** | int(11) |  | + |  |  |  | Number of branches in the phylogenetic tree. In a fully resolved tree: 2n-3 (with n taxa). 
**PROP_INVAR** | decimal(10,9) |  |  |  |  | NULL | Proportion of invariable sites in case the *+I* model of rate heterogeneity was employed. Else, NULL. 
**ALPHA** | decimal(15,9) |  |  |  |  | NULL | Shape parameter alpha should an Gamma *+G4* model have been employed. Else, NULL. 
**STAT_FREQ_TYPE** | varchar(100) |  | + |  |  |  | This field states whether the state frequencies of the stationary distribution assumed in the model are *empirical* (counted frequencies from the alignment) or if they are predefined by the model (*model*).  
**STAT_FREQ_A** | decimal(10,9) |  | + |  |  |  | The frequency of the amino acid alanine (A) assumed by the model. 
**…** |  |  |  |  |  |  | … 
**STAT_FREQ_V** | decimal(10,9) |  | + |  |  |  | The frequency of the amino acid tyrosine (V) assumed by the model. 
**PROP_CAT_1** | decimal(10,9) |  |  |  |  | NULL | The proportion of the first rate category (should the model assume different rates across sites). 
**REL_RATE_CAT_1** | decimal(15,9) |  |  |  |  | NULL | The rate of the first rate category (should the model assume different rates across sites). 
**…** |  |  |  |  |  |  | … 
**PROP_CAT_10** | decimal(10,9) |  |  |  |  | NULL | The proportion of the tenth rate category (should there exist one). 
**REL_RATE_CAT_10** | decimal(15,9) |  |  |  |  | NULL | The rate of the tenth rate category (should there exist one). 
**TREE_LENGTH** | decimal(15,9) |  | + |  |  |  | Total length of the tree (sum of all branch lengths). 
**SUM_IBL** | decimal(15,9) |  | + |  |  |  | Sum of internal branch lengths 
**TREE_DIAMETER** | decimal(15,9) |  | + |  |  |  | The tree diameter states the furthest distance (sum of BLs) between two taxa in the tree. 
**DIST_MIN** | decimal(15,9) |  |  |  |  | NULL | Minimal distance between two sequences in the alignment caculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MAX** | decimal(15,9) |  |  |  |  | NULL | Maximum distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in distances between any two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**BL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest branch in the tree 
**BL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest branch in the tree. 
**BL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean branch length in the tree. 
**BL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median branch length in the tree. 
**BL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in branch lengths in the tree. 
**IBL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest internal branch in the tree 
**IBL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest internal branch in the tree. 
**IBL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean internal branch length in the tree. 
**IBL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median internal branch length in the tree. 
**IBL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in internal branch lengths in the tree. 
**EBL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest external branch in the tree 
**EBL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest external branch in the tree. 
**EBL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean external branch length in the tree. 
**EBL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median external branch length in the tree. 
**EBL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in external branch lengths in the tree. 
**POT_LBA_7** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 7 times larger than the short and internal branch. 
**POT_LBA_8** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 8 times larger than the short and internal branch. 
**POT_LBA_9** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 9 times larger than the short and internal branch. 
**POT_LBA_10** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 10 times larger than the short and internal branch. 
**NEWICK_STRING** | mediumtext |  | + |  |  |  | This field contains the Newick string of the phylogenetic tree. 

## **11. dna_trees** 

**Comment**: The dna_trees table contains a set of phylogenetic trees as well as the  parameters of the assumed model of sequence evolution. The trees are either a fast-ML tree used in the model evaluation or a maximum likelihood (ML) tree inferred using the best-fit model.

**Constraints:**

* **KEY** (*BASE_MODEL*)
* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*TREE_TYPE*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **dna_alignments** (*ALI_ID*)
* **FOREIGN KEY** (*BASE_MODEL*) REFERENCES **dna_models** (*MODEL_NAME*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**TREE_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**IQTREE_VERSION** | varchar(100) |  | + |  |  |  |  
**RANDOM_SEED** | int(11) |  | + |  |  |  | The random number seed used by IQ-Tree.  
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp enables mapping of the tested model to one IQ-Tree run. 
**MODEL_TYPE** | varchar(100) |  | + |  |  |  | The type of model testing or the type of models that were tested in the IQ-Tree run. Will mostly be MF (the models included in the default ModelFinder algorithm).  
**TREE_TYPE** | varchar(100) |  | + | * |  |  | States the type of the tree. It is either *initial* (fast ML tree used for model evaluation) or *ML* (maximum likelihood tree). 
**CHOICE_CRITERIUM** | varchar(100) |  |  |  |  | NULL | States the choice criterium used to select the model for the ML tree search. In case of an *initial* tree, this field is left empty (NULL). 
**KEEP_IDENT** | tinyint(1) |  |  |  |  | NULL | Boolean stating whether the *--keep-ident* flag has been enabled (1) or disabled (0) in the IQ-Tree run.  
**MODEL** | varchar(100) |  | + |  |  |  | Name of the tested model 
**BASE_MODEL** | varchar(100) |  | + |  |  |  | Name of the substitution rate matrix used in the model.  
**MODEL_RATE_HETEROGENEITY** | varchar(100) |  |  |  |  | NULL | Name of the model of rate heterogeneity (should one have been employed). 
**NUM_RATE_CAT** | int(11) |  |  |  |  | NULL | Number of rate categories assumed by the model. 
**LOGL** | decimal(21,9) |  | + |  |  |  | Logarithmic likelihood 
**UNCONSTRAINED_LOGL** | decimal(21,9) |  |  |  |  |  | Unconstrained logarithmic likelihood 
**AIC** | decimal(21,9) |  | + |  |  |  |  
**AICC** | decimal(21,9) |  | + |  |  |  |  
**BIC** | decimal(21,9) |  | + |  |  |  |  
**CAIC** | decimal(21,9) |  |  |  |  | NULL |  
**ABIC** | decimal(21,9) |  |  |  |  | NULL |  
**NUM_FREE_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters (=NUM_MODEL_PARAMETERS+NUM_BRANCHES). 
**NUM_MODEL_PARAMETERS** | int(11) |  | + |  |  |  | Number of free parameters of the model of sequence evolution 
**NUM_BRANCHES** | int(11) |  | + |  |  |  | Number of branches in the phylogenetic tree. In a fully resolved tree: 2n-3 (with n taxa). 
**PROP_INVAR** | decimal(10,9) |  |  |  |  | NULL | Proportion of invariable sites in case the *+I* model of rate heterogeneity was employed. Else, NULL. 
**ALPHA** | decimal(15,9) |  |  |  |  | NULL | Shape parameter alpha should an Gamma *+G4* model have been employed. Else, NULL. 
**STAT_FREQ_TYPE** | varchar(100) |  | + |  |  |  | This field states whether the state frequencies of the stationary distribution assumed in the model are *empirical* (counted frequencies from the alignment) or if they are predefined by the model (*model*).  
**STAT_FREQ_A** | decimal(10,9) |  | + |  |  |  | The frequency of the base adenine (A) assumed by the model. 
**STAT_FREQ_C** | decimal(10,9) |  | + |  |  |  | The frequency of the base guanine (G) assumed by the model. 
**STAT_FREQ_G** | decimal(10,9) |  | + |  |  |  | The frequency of the base cytosine (C) assumed by the model. 
**STAT_FREQ_T** | decimal(10,9) |  | + |  |  |  | The frequency of the base thymine (T) assumed by the model. 
**RATE_AC** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from A to C. 
**RATE_CA** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from C to A. 
**…** |  |  |  |  |  |  | … 
**RATE_GT** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from G to T. 
**RATE_TG** | decimal(15,9) |  | + |  |  |  | Assumed relative substitution rate from T to G. 
**PROP_CAT_1** | decimal(10,9) |  |  |  |  | NULL | The proportion of the first rate category (should the model assume different rates across sites). 
**REL_RATE_CAT_1** | decimal(15,9) |  |  |  |  | NULL | The rate of the first rate category (should the model assume different rates across sites). 
**…** |  |  |  |  |  |  | … 
**PROP_CAT_10** | decimal(10,9) |  |  |  |  | NULL | The proportion of the tenth rate category (should there exist one). 
**REL_RATE_CAT_10** | decimal(15,9) |  |  |  |  | NULL | The rate of the tenth rate category (should there exist one). 
**TREE_LENGTH** | decimal(15,9) |  | + |  |  |  | Total length of the tree (sum of all branch lengths). 
**SUM_IBL** | decimal(15,9) |  | + |  |  |  | Sum of internal branch lengths 
**TREE_DIAMETER** | decimal(15,9) |  | + |  |  |  | The tree diameter states the furthest distance (sum of BLs) between two taxa in the tree. 
**DIST_MIN** | decimal(15,9) |  |  |  |  | NULL | Minimal distance between two sequences in the alignment caculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MAX** | decimal(15,9) |  |  |  |  | NULL | Maximum distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Meadian distance between two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**DIST_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in distances between any two sequences in the alignment calculated using the best-fit model. In case of *initial* tree, this field will be NULL. 
**BL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest branch in the tree 
**BL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest branch in the tree. 
**BL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean branch length in the tree. 
**BL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median branch length in the tree. 
**BL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in branch lengths in the tree. 
**IBL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest internal branch in the tree 
**IBL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest internal branch in the tree. 
**IBL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean internal branch length in the tree. 
**IBL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median internal branch length in the tree. 
**IBL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in internal branch lengths in the tree. 
**EBL_MIN** | decimal(15,9) |  |  |  |  | NULL | Shortest external branch in the tree 
**EBL_MAX** | decimal(15,9) |  |  |  |  | NULL | Longest external branch in the tree. 
**EBL_MEAN** | decimal(15,9) |  |  |  |  | NULL | Mean external branch length in the tree. 
**EBL_MEDIAN** | decimal(15,9) |  |  |  |  | NULL | Median external branch length in the tree. 
**EBL_VAR** | decimal(15,9) |  |  |  |  | NULL | Variation in external branch lengths in the tree. 
**POT_LBA_7** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 7 times larger than the short and internal branch. 
**POT_LBA_8** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 8 times larger than the short and internal branch. 
**POT_LBA_9** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 9 times larger than the short and internal branch. 
**POT_LBA_10** | int(11) |  |  |  |  | NULL | States if there exists a potential long branch attraction (LBA) problem in the tree. Assuming that the long branches need to be at least 10 times larger than the short and internal branch. 
**NEWICK_STRING** | mediumtext |  | + |  |  |  | This field contains the Newick string of the phylogenetic tree. 


## **12. aa_branches** 

**Comment**: The aa_branches table contains information regarding the branches of the phylogenetic trees stored in the aa_trees table. Each line contains information regarding one branch such as the branch type, the branch length, the splitsize, etc.

**Constraints:**

* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*TREE_TYPE*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **dna_alignments** (*ALI_ID*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**BRANCH_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp, paired with the alignment ID and tree type, enables the mapping of each branch to a phylogenetic tree in the aa_trees table. 
**TREE_TYPE** | varchar(100) |  | + | * |  |  | The type of tree: *initial* or *ML*. The tree type, paired with the alignment ID and time stamp, enables the mapping of each branch to a phylogenetic tree in the aa_trees table. 
**BRANCH_INDEX** | int(11) |  | + |  |  |  | Index of the branch. Should the branch be external, then the index connected to a taxon coincides with the *SEQ_INDEX* of the corresponding sequence in the aa_sequences table with the same *ALI_ID.* 
**BRANCH_TYPE** | varchar(30) |  | + |  |  |  | States the type of branch, either internal or external 
**BL** | decimal(15,9) |  | + |  |  |  | Branch length. 
**SPLIT_SIZE** | int(11) |  | + |  |  |  | States the split size (number of taxa in the smaller subtree). For external branches, the splitsize is always 1. 
**MIN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Shortest path length to the leaves in the smaller subtree. 
**MAX_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Longest path length to the leaves in the smaller subtree. 
**MEAN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Mean path length to the leaves in the smaller subtree. 
**MEDIAN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Median path length to the leaves in the smaller subtree. 
**MIN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Shortest path length to the leaves in the larger subtree. 
**MAX_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Longest path length to the leaves in the larger subtree. 
**MEAN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Mean path length to the leaves in the larger subtree. 
**MEDIAN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Median path length to the leaves in the larger subtree. 

## **13. dna_branches** 

**Comment**: The dna_branches table contains information regarding the branches of the phylogenetic trees stored in the dna_trees table. Each line contains information regarding one branch such as the branch type, the branch length, the splitsize, etc.

**Constraints:**

* **UNIQUE KEY** (*ALI_ID*,*TIME_STAMP*,*TREE_TYPE*)
* **FOREIGN KEY** (*ALI_ID*) REFERENCES **dna_alignments** (*ALI_ID*)

**Column Name** | **Datatype** | **PK** | **NN** | **UQ** | **AI** | **Default** | **Comment** 
---|---|:---:|:---:|:---:|:---:|:---:|---
**BRANCH_KEY** | int(11) | + | + |  | + |  | Autoincremented primary key. 
**ALI_ID** | varchar(250) |  | + | * |  |  | Name of the alignment (alignment ID). 
**TIME_STAMP** | datetime |  | + | * |  |  | The timestamp as it appears in the *.iqtree* output file. The timestamp, paired with the alignment ID and tree type, enables the mapping of each branch to a phylogenetic tree in the dna_trees table. 
**TREE_TYPE** | varchar(100) |  | + | * |  |  | The type of tree: *initial* or *ML*. The tree type, paired with the alignment ID and time stamp, enables the mapping of each branch to a phylogenetic tree in the dna_trees table. 
**BRANCH_INDEX** | int(11) |  | + |  |  |  | Index of the branch. Should the branch be external, then the index connected to a taxon coincides with the *SEQ_INDEX* of the corresponding sequence in the aa_sequences table with the same *ALI_ID.* 
**BRANCH_TYPE** | varchar(30) |  | + |  |  |  | States the type of branch, either internal or external 
**BL** | decimal(15,9) |  | + |  |  |  | Branch length. 
**SPLIT_SIZE** | int(11) |  | + |  |  |  | States the split size (number of taxa in the smaller subtree). For external branches, the splitsize is always 1. 
**MIN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Shortest path length to the leaves in the smaller subtree. 
**MAX_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Longest path length to the leaves in the smaller subtree. 
**MEAN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Mean path length to the leaves in the smaller subtree. 
**MEDIAN_PATH_1** | decimal(15,9) |  |  |  |  | NULL | Median path length to the leaves in the smaller subtree. 
**MIN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Shortest path length to the leaves in the larger subtree. 
**MAX_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Longest path length to the leaves in the larger subtree. 
**MEAN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Mean path length to the leaves in the larger subtree. 
**MEDIAN_PATH_2** | decimal(15,9) |  |  |  |  | NULL | Median path length to the leaves in the larger subtree. 
