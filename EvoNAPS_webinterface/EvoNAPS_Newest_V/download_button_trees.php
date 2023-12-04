<?php

//start session here in order to catch variables
session_start();
include "DB_credentials.php";

// initialize variables for the filter

	if(isset($_SESSION['alignment_features'])){
	$Alignment_Specs_Check = $_SESSION['alignment_features'];
	}else{
		$Alignment_Specs_Check="";
	}
	if(isset($_SESSION['tree_features'])){
	$Trees_Specs_Check = $_SESSION['tree_features'];
	}else{
		$Trees_Specs_Check ="";
	}

		$DNA_Prot = $_SESSION['datatype'];
		
	
		if(isset($_SESSION['number_of_sequences'])){
		$Nr_Seq = $_SESSION['number_of_sequences'];

		}
		$Max_Nr_Seq = $_SESSION['max_number_of_sequences'];
		
		$Nr_sites = $_SESSION['number_of_sites'];
		$Max_Nr_sites = $_SESSION['max_number_of_sites'];
		
		
		
		if(isset($_SESSION['min_mean_branch_length'])){
		$BL_mean_min = $_SESSION['min_mean_branch_length'];

		}
		$BL_mean_max = $_SESSION['max_mean_branch_length'];
		if(isset($_SESSION['min_branch_length'])){
		$BL_min = $_SESSION['min_branch_length'];
		}
		$BL_max = $_SESSION['max_branch_length'];
		
	
		$IBL_mean_min =  $_SESSION['min_mean_internal_branch_length'];
		$IBL_mean_max =  $_SESSION['max_mean_internal_branch_length'];

		if(isset($_SESSION['min_internal_branch_length'])){
		$IBL_min  = $_SESSION['min_internal_branch_length'];

		}
		$IBL_max =  $_SESSION['max_internal_branch_length'];
		
		
		$EBL_mean_min = $_SESSION['min_mean_external_branch_length'];
		$EBL_mean_max = $_SESSION['max_mean_external_branch_length'];
		if(isset($_SESSION['min_external_branch_length'])){
		$EBL_min = $_SESSION['min_external_branch_length'];
		}
		$EBL_max = $_SESSION['max_external_branch_length'];
		
		$tree_len = $_SESSION['tree_length'];
		$Max_tree_len = $_SESSION['max_tree_length'];
		$tree_dia = $_SESSION['tree_diameter'];
		$Max_tree_dia = $_SESSION['max_tree_diameter'];
		


	//////////////////Setting Variables////////////777
	$Source = [];
	
	if(isset($_SESSION['PANDIT'])){
		$Pan = $_SESSION['PANDIT'];
	}
	if(isset($_SESSION['OrthoMaM_v10c'])){
	$Ortho_v1 =$_SESSION['OrthoMaM_v10c'];
	}
	if(isset($_SESSION['OrthoMaM_v12a'])){
	$Ortho_v2 =$_SESSION['OrthoMaM_v12a'];
		}
	if(isset($_SESSION['Lanfear'])){
	$Lanf =$_SESSION['Lanfear'];
	}
	if(isset($_SESSION['TreeBASE'])){
	$TreeBASE =$_SESSION['TreeBASE'];
		}
	$ALL = $_SESSION['selectAll'];

	


		$f_d_conditions = [];
		$f_d_parameters = [];




//////////////////////String Building Source ///////////////////////

$stringsource = "";
$stringall = "'PANDIT','Lanfear','TreeBASE', 'OrthoMaM_v10c', 'OrthoMaM_v12a'";

if(!empty($Ortho)){
			
			$Source[] = $Ortho_v1;
		}


if(!empty($Ortho)){
			
			$Source[] = $Ortho_v2;
		}

		
		
if(!empty($Pan)){
			
			$Source[] = $Pan;
		}
		
if(!empty($Lanf)){
			
			$Source[] = $Lanf;
			
		}

if(!empty($TreeBASE)){
			
			$Source[] = $TreeBASE;
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
	

		$f_d_conditions = [];
		$f_d_parameters = [];
		$usedna = false;

		
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
			
			$f_d_query = " SELECT ".$select . " FROM ";
		
		
		
		try {
			
			if($usedna == true){

			//joins for trees				
			$f_d_query .= " `dna_alignments` as `ali` INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) "; 
			$f_d_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
			$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";

			//Add SourceList
			if($ALL == "checked"){
						
				$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

				}elseif(!empty($Source)){
					
					$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
					
				}
			
						
			if($Alignment_Specs_Check == "TRUE"){		
					
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
						
				//Proteins Trees same code
			}else {
								
				$f_d_query .= " `aa_alignments` as `ali` INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) "; 
				$f_d_query .= " WHERE `tree`.`TREE_TYPE` =  'ml' ";
				$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";

					
				//Add SourceList
			if ($ALL == "checked"){
					
				$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

				}elseif(!empty($Source)){
					
					$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
					
				}
			
			if($Alignment_Specs_Check == "TRUE"){
						
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
							
			}
			
			//Fuze Conditions
			// check if sth got added to conditions if yes, add the SQL statemens together in the condition array anf form the string
			if($f_d_conditions)

			// implode function to convert array in string, delimter with AND and add the "Where" clause to the string
				$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);
			
			}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
		// Bind the values of f_d_parameters array to the the SQL statements from the conditions array 	
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

			//fetch data
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
			/// set headers for donwnloading the data
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=trees.txt');
		$output_file = fopen("php://output", "w"); 

		
		
		$headers_printed = false; 
		$output = " ";
		//test counter
		$counter = 0;
		foreach ($filter_query_result as $list) {
			 
			
			///download headder titles 	
			if(!$headers_printed){
				
			fwrite($output_file,"\n");
			fputcsv($output_file,array("Alignment ID","Newick String","Prop Invar"),"\t");
			$headers_printed = true;
			
			
		}
		// Write Results in Document 
		fwrite($output_file,"\n");
		fputcsv($output_file,$list,"\t");
		fpassthru($output_file);
			$counter++;
			
		
		}
		echo "Nr of Hits".$counter;
		
		$connect = null;
			

?>