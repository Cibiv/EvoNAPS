
#!/bin/bash 

date_var=$(date +"%Y:%m:%d $H:%M:%S")

if [ 0 -ne $# ]
      then folder=$1
      else echo 'Warning! Missing folder name as input!'
fi

echo $date_var

for i in $folder'/'*'fasta'; do 

	if test -f $folder'/'$i'_results/'$i'.iqtree'; 
		then
			if test -f $j'_tree_parameters.tsv'; then 
				echo 'Done: '$i;
				else echo 'Warning: Missing tsv files for '$i;
			fi; 			
		else echo 'Warning: No iqtree file for '$i; 
	fi;

done; 
