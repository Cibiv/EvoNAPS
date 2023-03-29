
#!/bin/bash

date_var=$(date +"%Y:%m:%d $H:%M:%S")

if [ 0 -ne $# ]
      then folder=$1
      else echo Missing folder name as input
fi

echo $date_var
mkdir $folder'_results_combined' 

for i in $folder/*fasta; do

        if test -f $i'_results/'*'_ali_parameters.tsv';
            then
                if test -f $folder'_results_combined'/pandit_aa_ali_parameters_combined.tsv;
                    then
                        tail -n +2 $i'_results/'*'_ali_parameters.tsv' >> $folder'_results_combined'/pandit_aa_ali_parameters_combined.tsv;
                    else
                        less $i'_results/'*'_ali_parameters.tsv' > $folder'_results_combined'/pandit_aa_ali_parameters_combined.tsv;
                fi;
            else
                echo 'Warning! '$i'_ali_parameters.tsv not found!'
        fi;

        if test -f $i'_results/'*'_seq_parameters.tsv';
            then
                if test -f $folder'_results_combined'/pandit_aa_seq_parameters_combined.tsv;
                    then
                        tail -n +2 $i'_results/'*'_seq_parameters.tsv' >> $folder'_results_combined'/pandit_aa_seq_parameters_combined.tsv;
                    else
                        less $i'_results/'*'_seq_parameters.tsv' > $folder'_results_combined'/pandit_aa_seq_parameters_combined.tsv;
                fi;
            else
                echo 'Warning! '$i'_seq_parameters.tsv not found!'
        fi;

        if test -f $i'_results/'*'_branch_parameters.tsv';
            then
                if test -f $folder'_results_combined'/pandit_aa_branch_parameters_combined.tsv;
                    then
                        tail -n +2 $i'_results/'*'_branch_parameters.tsv' >> $folder'_results_combined'/pandit_aa_branch_parameters_combined.tsv;
                    else
                        less $i'_results/'*'_branch_parameters.tsv' > $folder'_results_combined'/pandit_aa_branch_parameters_combined.tsv;
                fi;
            else
                echo 'Warning! '$i'_branch_parameters.tsv not found!'
        fi;

        if test -f $i'_results/'*'_tree_parameters.tsv';
            then
                if test -f $folder'_results_combined'/pandit_aa_tree_parameters_combined.tsv;
                    then
                        tail -n +2 $i'_results/'*'_tree_parameters.tsv' >> $folder'_results_combined'/pandit_aa_tree_parameters_combined.tsv;
                    else
                        less $i'_results/'*'_tree_parameters.tsv' > $folder'_results_combined'/pandit_aa_tree_parameters_combined.tsv;
                fi;
            else
                echo 'Warning! '$i'_tree_parameters.tsv not found!'
        fi;


        if test -f $i'_results/'*'_model_parameters.tsv';
            then
                if test -f $folder'_results_combined'/pandit_aa_model_parameters_combined.tsv;
                    then
                        tail -n +2 $i'_results/'*'_model_parameters.tsv' >> $folder'_results_combined'/pandit_aa_model_parameters_combined.tsv;
                    else
                        less $i'_results/'*'_model_parameters.tsv' > $folder'_results_combined'/pandit_aa_model_parameters_combined.tsv;
                fi;
            else
                echo 'Warning! '$i'_model_parameters.tsv not found!'
        fi;

done




