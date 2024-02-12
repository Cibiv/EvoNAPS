<?php

//start session here in order to catch variables
session_start();

// include credentials data here 
include "DB_credentials.php";

		//Initalize variables 
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];




		if($DNA_Prot == "dna"){
			
			// DNA select
			$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
			`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS,
			`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, 
			ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_T,4) AS FREQ_T,
			ROUND(`tree`.RATE_AC,4) AS RATE_AC, ROUND(`tree`.RATE_AG,4) AS RATE_AG, ROUND(`tree`.RATE_AT,4) AS RATE_AT, 
			ROUND(`tree`.RATE_CG,4) AS RATE_CG, ROUND(`tree`.RATE_CT,4) AS RATE_CT, ROUND(`tree`.RATE_GT,4) AS RATE_GT,
			ROUND(`tree`.ALPHA,5) AS ALPHA, ROUND(`tree`.PROP_INVAR,5) AS PROP_INVAR,
			ROUND(`tree`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`tree`.PROP_CAT_1,5) AS PROP_CAT_1, 
			ROUND(`tree`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`tree`.PROP_CAT_2,5) AS PROP_CAT_2, 
			ROUND(`tree`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`tree`.PROP_CAT_3,5) AS PROP_CAT_3,
			ROUND(`tree`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`tree`.PROP_CAT_4,5) AS PROP_CAT_4, 
			ROUND(`tree`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`tree`.PROP_CAT_5,5) AS PROP_CAT_5, 
			ROUND(`tree`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`tree`.PROP_CAT_6,5) AS PROP_CAT_6,
			ROUND(`tree`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`tree`.PROP_CAT_7,5) AS PROP_CAT_7, 
			ROUND(`tree`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`tree`.PROP_CAT_8,5) AS PROP_CAT_8, 
			ROUND(`tree`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`tree`.PROP_CAT_9,5) AS PROP_CAT_9,
			ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10,
			ROUND(`tree`.BL_MAX,5) AS BL_MAX, ROUND(`tree`.BL_MEAN,5) AS BL_MEAN, 
			ROUND(`tree`.IBL_MAX,5) AS IBL_MAX, ROUND(`tree`.IBL_MEAN,5) AS IBL_MEAN,
			ROUND(`tree`.EBL_MAX,5) AS EBL_MAX, ROUND(`tree`.EBL_MEAN,5) AS EBL_MEAN,
			`tree`.NEWICK_STRING ";
			$usedna = true;
			
			
		} else {
			
			//Aa select
			$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
			`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS,
			`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, 
			ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_R,4) AS FREQ_R, ROUND(`tree`.FREQ_N,4) AS FREQ_N, 
			ROUND(`tree`.FREQ_D,4) AS FREQ_D, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_Q,4) AS FREQ_Q, 
			ROUND(`tree`.FREQ_E,4) AS FREQ_E, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_H,4) AS FREQ_H, 
			ROUND(`tree`.FREQ_I,4) AS FREQ_I, ROUND(`tree`.FREQ_L,4) AS FREQ_L, ROUND(`tree`.FREQ_K,4) AS FREQ_K, 
			ROUND(`tree`.FREQ_M,4) AS FREQ_M, ROUND(`tree`.FREQ_F,4) AS FREQ_F, ROUND(`tree`.FREQ_P,4) AS FREQ_P, 
			ROUND(`tree`.FREQ_S,4) AS FREQ_S, ROUND(`tree`.FREQ_T,4) AS FREQ_T, ROUND(`tree`.FREQ_W,4) AS FREQ_W, 
			ROUND(`tree`.FREQ_Y,4) AS FREQ_Y, ROUND(`tree`.FREQ_V,4) AS FREQ_V, 
			ROUND(`tree`.ALPHA,5) AS ALPHA, ROUND(`tree`.PROP_INVAR,5) AS PROP_INVAR,
			ROUND(`tree`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`tree`.PROP_CAT_1,5) AS PROP_CAT_1, 
			ROUND(`tree`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`tree`.PROP_CAT_2,5) AS PROP_CAT_2, 
			ROUND(`tree`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`tree`.PROP_CAT_3,5) AS PROP_CAT_3,
			ROUND(`tree`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`tree`.PROP_CAT_4,5) AS PROP_CAT_4, 
			ROUND(`tree`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`tree`.PROP_CAT_5,5) AS PROP_CAT_5, 
			ROUND(`tree`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`tree`.PROP_CAT_6,5) AS PROP_CAT_6,
			ROUND(`tree`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`tree`.PROP_CAT_7,5) AS PROP_CAT_7, 
			ROUND(`tree`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`tree`.PROP_CAT_8,5) AS PROP_CAT_8, 
			ROUND(`tree`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`tree`.PROP_CAT_9,5) AS PROP_CAT_9,
			ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10,
			ROUND(`tree`.BL_MAX,5) AS BL_MAX, ROUND(`tree`.BL_MEAN,5) AS BL_MEAN, 
			ROUND(`tree`.IBL_MAX,5) AS IBL_MAX, ROUND(`tree`.IBL_MEAN,5) AS IBL_MEAN,
			ROUND(`tree`.EBL_MAX,5) AS EBL_MAX, ROUND(`tree`.EBL_MEAN,5) AS EBL_MEAN,
			`tree`.NEWICK_STRING ";
			$useprot = true;
		}



		$result_query = " SELECT ".$select . " FROM ";


		if($usedna == true){

			//joins for trees				
			$result_query .= " `dna_alignments` as `ali` INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) "; 
			//$result_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			//$result_query .= " AND `tree`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';
			$result_query .= " WHERE `tree`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';
		}else{
			
			$result_query.= " `aa_alignments` as `ali` INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) "; 
			//$result_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			//$result_query .= " AND `tree`.`ORIGINAL_ALI` = 1 "." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';
			$result_query .= " WHERE `tree`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';



		}

		$query = $connect->query($result_query);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

		/// set headers for donwnloading the data	
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=trees_alignment.tsv');

    //created file
    $output_file = fopen("php://output", "w"); 
            
    $headers_printed = false; 
    $output = " ";


	// loop through the fetched data
    foreach ($result as $list) {
			 	
		//check for headders 	
	if(!$headers_printed){


		if($DNA_Prot == "dna"){


			fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS','MODEL','BASE_MODEL','RHAS','LOGL','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','BL_MAX','BL_MEAN','IBL_MAX','IBL_MEAN','EBL_MAX','EBL_MEAN','NEWICK_STRING'),"\t");
				$headers_printed = true;


		} else{

			fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
			'FRAC_WILDCARDS_GAPS','MODEL','BASE_MODEL','RHAS','LOGL','FREQ_A', 'FREQ_R', 'FREQ_N', 
			'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
			'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR',
			'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
			'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
			'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','BL_MAX','BL_MEAN','IBL_MAX','IBL_MEAN','EBL_MAX','EBL_MEAN','NEWICK_STRING'),"\t");
			$headers_printed = true;
		}
	}

	  // Write Results in Document 
	  fputcsv($output_file,$list,"\t");
    
	  fpassthru($output_file);


}

$connect = null;





        ?>