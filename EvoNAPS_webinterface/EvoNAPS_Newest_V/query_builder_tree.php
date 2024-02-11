<?php


		//Connect to DB
		
		ini_set('memory_limit','1000M');
		include('variables_trees.php');
		include('DB_credentials.php');
		//Old
		
		
		//error_reporting(0);
	
		
		
		/////////////////////String Building Source ///////////////////////

$stringsource = "";
$stringall = "'PANDIT','Lanfear','TreeBASE', 'OrthoMaM_v10c', 'OrthoMaM_v12a'";

if(!empty($Ortho_v1)){
			
			$Source[] = $Ortho_v1;
		}


if(!empty($Ortho_v2)){
			
			$Source[] = $Ortho_v2;
		}



if(!empty($TreeBASE)){
			
			$Source[] = $TreeBASE;
		}
		
		
if(!empty($Pan)){
			
			$Source[] = $Pan;
		}
		
if(!empty($Lanf)){
			
			$Source[] = $Lanf;
			
		}

//////////////Loop for String Source Building////////////////////////


$first = false;
		
		
		if(!empty($Source)){
			
		foreach($Source as $list){
			
			if($first == false){
				
				
				$stringsource .= "'".$list."'";
				
				$first = true; 
				
			}else {
				$stringsource .= ","."'".$list."'";
				
			}
			
			
			
			
		}
	}
		
		
		// Dynamic Querys Parameters
		
		
		$f_d_conditions = [];
		$f_d_parameters = [];
		
		
		
		
		if($DNA_Prot == "dna" ){
			
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


			$select2 = "COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS, 
			ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS,
			ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
			ROUND(AVG(`tree`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`tree`.FREQ_C),4) AS AVG_FREQ_C,
			ROUND(AVG(`tree`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`tree`.FREQ_T),4) AS AVG_FREQ_T,
			ROUND(AVG(`tree`.RATE_AC),4) AS AVG_RATE_AC, ROUND(AVG(`tree`.RATE_AG),4) AS AVG_RATE_AG, ROUND(AVG(`tree`.RATE_AT),4) AS AVG_RATE_AT,
			ROUND(AVG(`tree`.RATE_CG),4) AS AVG_RATE_CG, ROUND(AVG(`tree`.RATE_CT),4) AS AVG_RATE_CT, ROUND(AVG(`tree`.RATE_GT),4) AS AVG_RATE_GT,
			ROUND(AVG(`tree`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`tree`.PROP_INVAR),5) AS AVG_PROP_INVAR,
			ROUND(AVG(`tree`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`tree`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
			ROUND(AVG(`tree`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`tree`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
			ROUND(AVG(`tree`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`tree`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
			ROUND(AVG(`tree`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`tree`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
			ROUND(AVG(`tree`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`tree`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
			ROUND(AVG(`tree`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`tree`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
			ROUND(AVG(`tree`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`tree`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
			ROUND(AVG(`tree`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`tree`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
			ROUND(AVG(`tree`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`tree`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
			ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10,
			ROUND(AVG(`tree`.BL_MAX),5) AS AVG_BL_MAX, ROUND(AVG(`tree`.BL_MEAN),5) AS AVG_BL_MEAN, 
			ROUND(AVG(`tree`.IBL_MAX),5) AS AVG_IBL_MAX, ROUND(AVG(`tree`.IBL_MEAN),5) AS AVG_IBL_MEAN,
			ROUND(AVG(`tree`.EBL_MAX),5) AS AVG_EBL_MAX, ROUND(AVG(`tree`.EBL_MEAN),5) AS AVG_EBL_MEAN";
			
			
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


		$select2 = "COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
		ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS,
		ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
		ROUND(AVG(`tree`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`tree`.FREQ_R),4) AS AVG_FREQ_R, ROUND(AVG(`tree`.FREQ_N),4) AS AVG_FREQ_N,
		ROUND(AVG(`tree`.FREQ_D),4) AS AVG_FREQ_D, ROUND(AVG(`tree`.FREQ_C),4) AS AVG_FREQ_C, ROUND(AVG(`tree`.FREQ_Q),4) AS AVG_FREQ_Q,
		ROUND(AVG(`tree`.FREQ_E),4) AS AVG_FREQ_E, ROUND(AVG(`tree`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`tree`.FREQ_H),4) AS AVG_FREQ_H,
		ROUND(AVG(`tree`.FREQ_I),4) AS AVG_FREQ_I, ROUND(AVG(`tree`.FREQ_L),4) AS AVG_FREQ_L, ROUND(AVG(`tree`.FREQ_K),4) AS AVG_FREQ_K,
		ROUND(AVG(`tree`.FREQ_M),4) AS AVG_FREQ_M, ROUND(AVG(`tree`.FREQ_F),4) AS AVG_FREQ_F, ROUND(AVG(`tree`.FREQ_P),4) AS AVG_FREQ_P,
		ROUND(AVG(`tree`.FREQ_S),4) AS AVG_FREQ_S, ROUND(AVG(`tree`.FREQ_T),4) AS AVG_FREQ_T, ROUND(AVG(`tree`.FREQ_W),4) AS AVG_FREQ_W,
		ROUND(AVG(`tree`.FREQ_Y),4) AS AVG_FREQ_Y, ROUND(AVG(`tree`.FREQ_V),4) AS AVG_FREQ_V,
		ROUND(AVG(`tree`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`tree`.PROP_INVAR),5) AS AVG_PROP_INVAR,
		ROUND(AVG(`tree`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`tree`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
		ROUND(AVG(`tree`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`tree`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
		ROUND(AVG(`tree`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`tree`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
		ROUND(AVG(`tree`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`tree`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
		ROUND(AVG(`tree`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`tree`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
		ROUND(AVG(`tree`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`tree`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
		ROUND(AVG(`tree`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`tree`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
		ROUND(AVG(`tree`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`tree`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
		ROUND(AVG(`tree`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`tree`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
		ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10,
		ROUND(AVG(`tree`.BL_MAX),5) AS AVG_BL_MAX, ROUND(AVG(`tree`.BL_MEAN),5) AS AVG_BL_MEAN, 
		ROUND(AVG(`tree`.IBL_MAX),5) AS AVG_IBL_MAX, ROUND(AVG(`tree`.IBL_MEAN),5) AS AVG_IBL_MEAN,
		ROUND(AVG(`tree`.EBL_MAX),5) AS AVG_EBL_MAX, ROUND(AVG(`tree`.EBL_MEAN),5) AS AVG_EBL_MEAN";



			$useprot = true;
		}
		
		
		
			
			
			$f_d_query_1 = " SELECT ".$select . " FROM ";
		
				
			$f_d_query ="SELECT ".$select2. " FROM ";
			
		
		
		
		try {
			
		
			
			if($DNA_Prot == "dna"){
				

			//count
			$f_d_query .= " `dna_alignments` as `ali` INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) "; 
			$f_d_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
			//preview
			$f_d_query_1 .= " `dna_alignments` as `ali` INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) "; 
			$f_d_query_1 .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";

			if ($ALL == "checked"){
					
				$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
				$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

				}elseif(!empty($Source)){
					
					$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
					$f_d_query_1 .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
					
				}

			if($Alignment_Specs_Check == "TRUE"){
						
						
				//Add SourceList
				
				

			
					//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `ali`.`Taxa` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
						
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `ali`.`Taxa` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `ali`.`Sites` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `ali`.`Sites` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
					}

					if($Trees_Specs_Check== "TRUE"){
						
						//min
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
							//max
						if(!empty($Max_tree_len)){
						
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
						//min	
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						//Branch length
						
						//min
						if(!empty($BL_min)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MIN` >= ? ';
							$f_d_parameters[] =  $BL_min;
							
							}
						//max	
						if(!empty($BL_max)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MAX` <= ? ';
							$f_d_parameters[] =  $BL_max;
							
						}
						//mean (min)
						if(!empty($BL_mean_min)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MEAN` >= ? ';
							$f_d_parameters[] =  $BL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($BL_mean_max)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MEAN` <= ? ';
							$f_d_parameters[] =  $BL_mean_max;
							
							}
							
							//Internal Branch
							
							
							//min
						if(!empty($IBL_min)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MIN` >= ? ';
							$f_d_parameters[] =  $IBL_min;
							
							}
						//max	
						if(!empty($IBL_max)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MAX` <= ? ';
							$f_d_parameters[] =  $IBL_max;
							
						}
						//mean (min)
						if(!empty($IBL_mean_min)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MEAN` >= ? ';
							$f_d_parameters[] =  $IBL_mean_min;
							
							}
							
						//mean (max)
						if(!empty($IBL_mean_max)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MEAN` <= ? ';
							$f_d_parameters[] =  $IBL_mean_max;
							
							}
							
							
							
							
							
						//External Branch
						
						//min
						if(!empty($EBL_min)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MIN` >= ? ';
							$f_d_parameters[] =  $EBL_min;
							
							}
						//max	
						if(!empty($EBL_max)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MAX` <= ? ';
							$f_d_parameters[] =  $EBL_max;
							
						}
						//mean (min)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MEAN` >= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($EBL_mean_max)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MEAN` <= ? ';
							$f_d_parameters[] =  $EBL_mean_max;
							
							}

						}
						
				
				
				//Proteins Trees
			}else{
					
				//count
			$f_d_query .= " `aa_alignments` as `ali` INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) "; 
			$f_d_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
			//preview
			$f_d_query_1 .= " `aa_alignments` as `ali` INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) "; 
			$f_d_query_1 .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
			
				if ($ALL == "checked"){
						
					$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
					$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
	
					}elseif(!empty($Source)){
						
						$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
						$f_d_query_1 .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
						
					}

				if($Alignment_Specs_Check == "TRUE"){
						
						
					//Add SourceList
					
					
					//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `ali`.`TAXA` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
						
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `ali`.`TAXA` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `ali`.`SITES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
					}

					if($Trees_Specs_Check== "TRUE"){

						
						//min
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						//max
						if(!empty($Max_tree_len)){
						
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $$Max_tree_len;
							
							}
						//min	
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max

						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						//Branch length
						
						//min
						if(!empty($BL_min)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MIN` >= ? ';
							$f_d_parameters[] =  $BL_min;
							
							}
						//max	
						if(!empty($BL_max)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MAX` <= ? ';
							$f_d_parameters[] =  $BL_max;
							
						}
						//mean (min)
						if(!empty($BL_mean)){
							
							$f_d_conditions[] =  ' `tree`.`BL_MEAN` >= ? ';
							$f_d_parameters[] =  $BL_mean_min;
							
							}
							//mean (max)
						if(!empty($BL_mean)){
						
							$f_d_conditions[] =  ' `tree`.`BL_MEAN` <= ? ';
							$f_d_parameters[] =  $BL_mean_max;
							
							}
							
							//Internal Branch
							
							
							
						if(!empty($IBL_min)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MIN` >= ? ';
							$f_d_parameters[] =  $IBL_min;
							
							}
						//max	
						if(!empty($IBL_max)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MAX` <= ? ';
							$f_d_parameters[] =  $IBL_max;
							
						}
						//mean (min)
						if(!empty($IBL_mean_min)){
							
							$f_d_conditions[] =  ' `tree`.`IBL_MEAN` >= ? ';
							$f_d_parameters[] =  $IBL_mean_min;
							
							}
						if(!empty($IBL_mean_max)){
						
							$f_d_conditions[] =  ' `tree`.`IBL_MEAN` <= ? ';
							$f_d_parameters[] =  $IBL_mean_max;
							
							}
								
								
							
								
							
							
						//External Branch
						
						
						if(!empty($EBL_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`EBL_MIN` >= ? ';
							$f_d_parameters[] =  $EBL_min;
							
							}
						//max	
						if(!empty($EBL_max)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MAX` <= ? ';
							$f_d_parameters[] =  $EBL_max;
							
						}
						//mean (min)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `tree`.`EBL_MEAN` >= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
							//mean (max)
						if(!empty($EBL_mean_max)){
					
							$f_d_conditions[] =  ' `tree`.`EBL_MEAN` <= ? ';
							$f_d_parameters[] =  $EBL_mean_max;
							
							}

						}
				
				
				
			}
			
			
		
			
			
			
			//Fuze conditions in 1 string
			if(empty($f_d_parameters)){
				
				$f_d_query_1 .= "LIMIT 20";
				
			}else {
				$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);
				$f_d_query_1 .= " AND ".implode(" AND ", $f_d_conditions)." LIMIT 20";
				
			}


			//echo $f_d_query;
			 
			 
			
			
			
			
			//exception handling
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage();
			}
			
				
				
				
				
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		?>