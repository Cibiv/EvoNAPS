<?php


		
		//Include files and set memory limit
		
		ini_set('memory_limit','1000M');
		include('variables_modelparameters_ali.php');
		include('DB_credentials.php');
		
		//initalize query parameters
		$f_d_conditions = [];
		$f_d_parameters = [];
		$usedna = false;
		
		
		if($DNA_Prot == "dna"){
			
			$usedna = true;
			
			// DNA select
			$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `tree`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.BIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT, ROUND(`tree`.TREE_LENGTH,5), ROUND(`tree`.TREE_DIAMETER,5),
				ROUND(`mod`.FREQ_A,4) AS FREQ_A, ROUND(`mod`.FREQ_C,4) AS FREQ_C, 
				ROUND(`mod`.FREQ_G,4) AS FREQ_G, ROUND(`mod`.FREQ_T,4) AS FREQ_T,
				ROUND(`mod`.RATE_AC,4) AS RATE_AC, ROUND(`mod`.RATE_AG,4) AS RATE_AG, ROUND(`mod`.RATE_AT,4) AS RATE_AT, 
				ROUND(`mod`.RATE_CG,4) AS RATE_CG, ROUND(`mod`.RATE_CT,4) AS RATE_CT, ROUND(`mod`.RATE_GT,4) AS RATE_GT,
				ROUND(`mod`.ALPHA,5) AS ALPHA, ROUND(`mod`.PROP_INVAR,5) AS PROP_INVAR,
				ROUND(`mod`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`mod`.PROP_CAT_1,5) AS PROP_CAT_1, 
				ROUND(`mod`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`mod`.PROP_CAT_2,5) AS PROP_CAT_2, 
				ROUND(`mod`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`mod`.PROP_CAT_3,5) AS PROP_CAT_3,
				ROUND(`mod`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`mod`.PROP_CAT_4,5) AS PROP_CAT_4, 
				ROUND(`mod`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`mod`.PROP_CAT_5,5) AS PROP_CAT_5, 
				ROUND(`mod`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`mod`.PROP_CAT_6,5) AS PROP_CAT_6,
				ROUND(`mod`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`mod`.PROP_CAT_7,5) AS PROP_CAT_7, 
				ROUND(`mod`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`mod`.PROP_CAT_8,5) AS PROP_CAT_8, 
				ROUND(`mod`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`mod`.PROP_CAT_9,5) AS PROP_CAT_9,
				ROUND(`mod`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`mod`.PROP_CAT_10,5) AS PROP_CAT_10,
				`tree`.NEWICK_STRING ";
			
			
			
		} else {
			
			//Aa select
			$select = "`aa_sequences` .`SEQ_NAME`,`aa_sequences` .`SEQ`";
			$useprot = true;
		}
		
			
			
			$f_d_query_1 = " SELECT ".$select . " FROM ";
		
				
				$f_d_query = " SELECT count(*) FROM ";
			
		
		
		
		try {
			
			
			if($usedna == true){
				
				//alignment joins
				$f_d_query .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				$f_d_query_1 .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`dna_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}
				
				
				
				
								
			
					
				
				
				//Proteins only do if dna is done and finished 
			}else{
				
				if($useprot == true){
				
				
				
				$f_d_query .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				$f_d_query_1 .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				}
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`aa_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}
				
			}
			
			//Fuze conditions in 1 string
			if($f_d_conditions)
				$f_d_query .= " WHERE ".implode(" AND ", $f_d_conditions);
				//Fuze conditions in 1 string for preview and limit it
				$f_d_query_1 .= " WHERE ".implode(" AND ", $f_d_conditions)." LIMIT 20";
			
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
			
		?>