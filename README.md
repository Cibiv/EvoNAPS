
# **EvoNAPS**

## **Introduction**

The EvoNAPS database provides a large variety of phylogenetic trees as well as empirical parameter estimates of various models of sequence evolution. The main purpose of the database is to provide meaningful parameter settings for sequence simulations. The database offers various filter options to enable the user to find alignments, trees and/or parameter settings of different evolutionary models that fit their requirements. 

The parameter estimates stored in EvoNAPS were gathered using the phylogenetic inference software IQ-Tree (v.2.2.0.5) (Minh, 2022) and were estimated based on biological alignments. The alignments were gathered from existing published sources, namely the online respiratory provided by Rob Lanfear (Lanfear, 2019) as well as the *PANDIT* (Whelan, 2006), the *OrthoMaM* (Douzery, 2014), and the TreeBASE (Piel, 2003) databases.

The EvoNAPS database holds: 

* around 22,600 DNA alignments  
* around 6,600 protein alignments and
* over 64,000 phylogenetic trees.

## **Overview**

This folder provides scripts concerning the MySQL database itself (such as the create statements and example import commands): 

* [EvoNAPS database](EvoNAPS_database/)

Additionally, the scripts that were used to create the data in the EvoNAPS database can be found here: 

* [EvoNAPS workflow](EvoNAPS_workflow/)

## **References**

Douzery, E. J. P., Scornavacca, C., Romiguier, J., Belkhir, K., Galtier, N., Delsuc, F., & Ranwez, V. (2014). OrthoMaM v8: A Database of Orthologous Exons and Coding Sequences for Comparative Genomics in Mammals. Molecular Biology and Evolution, 31(7), 1923-1928. https://doi.org/10.1093/molbev/msu132

Lanfear, R. (2019). BenchmarkAlignments. https://github.com/roblanf/BenchmarkAlignments/

Minh, B. Q., Schmidt, H. A., Chernomor, O., Schrempf, D., Woodhams, M. D., von Haeseler, A., & Lanfear, R. (2020). IQ-TREE 2: New Models and Efficient Methods for Phylogenetic Inference in the Genomic Era. Mol Biol Evol, 37(5), 1530-1534. https://doi.org/10.1093/molbev/msaa015 

Piel, W. H., Sanderson, M. J., & Donoghue, M. J. (2003). The small-world dynamics of tree networks and data mining in phyloinformatics. Bioinformatics, 19(9), 1162–1168. https://www.treebase.org/treebase-web/home.html

Whelan, S., Bakker, P., Quevillon, E., Rodriguez, N., & Goldman, N. (2006). PANDIT: an evolution-centric database of protein and associated nucleotide domains with inferred trees. Nucleic acids research, 34, D327-331. https://doi.org/10.1093/nar/gkj087 
