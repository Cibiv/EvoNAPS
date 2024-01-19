<?php


		//Connect to DB
		include "variables_modelparameters.php";
	//	include "DB_credentials.php";
		
		ini_set('memory_limit','1000M');
	
	




		

		//initalize query parameters


		$f_d_conditions = [];
		$f_d_parameters = [];




/////////////////////String Building Source ///////////////////////

$stringsource = "";
$stringall = "'PANDIT','Lanfear','TreeBASE', 'OrthoMaM_v10c', 'OrthoMaM_v12a'";

if(!empty($Ortho_v1)){
			
			$Source[] = $OrthoM_v1;
		}


if(!empty($Ortho_v2)){
			
			$Source[] = $OrthoM_v2;
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
	
	
		
	////////////////String Building Model/////////////////////////////////////////////
		
		
		$string_model = "";
		$string_model_p = "";
		
		
		
		if(!empty($I)){
			
			$model[] = $I;
		}
		
		
		if(!empty($IG4)){
			
			$model[] = $IG4;
		}
		
		if(!empty($G4)){
			
			$model[] = $G4;
		}
		
		
		if(!empty($R2)){
			
			$model[] = $R2;
		}
		
		if(!empty($R3)){
			
			$model[] = $R3;
		}
		
		if(!empty($R4)){
			
			$model[] = $R4;
		}
		
		if(!empty($R5)){
			
			$model[] = $R5;
		}
		
		
		if(!empty($R6)){
			
			$model[] = $R6;
		}
		
		if(!empty($R7)){
			
			$model[] = $R7;
		}
		
		if(!empty($R8)){
			
			$model[] = $R8;
		}
		
		
		if(!empty($R9)){
			
			$model[] = $R9;
		}
		
		if(!empty($R10)){
			
			$model[] = $R10;
		}
		
		
		
		
		/////////////Loop for String Building////////////////////////////
		$first_one = false;
		
		
		if(!empty($model)){
			
		foreach($model as $list){
			
			if($first_one == false){
				
				//$string_model .= "'".$Matrices_D . " " . $list . "'";
				//$string_model .= " 'Index '". "," ."'". $Matrices_D. "'";
				//$string_model_p .= " 'Index '". ","."'". $Matrices_P."'";
				
				//Works 
				$string_model .= " 'Index '";
				$string_model_p .= " 'Index '";
				
				//New freestyle
				//$string_model .= "'".$Matrices_D . "" . $list . "'";
				//$string_model_p .="'".$Matrices_P . "" . $list . "'";
				$first_one = true; 
				if($E == "checked"){
					$string_model .= ","."'".$Matrices_D. "'";
					$string_model_p .= ","."'".$Matrices_P. "'";
				}
			}
			
			////////////////Check if Space for L8ter////////////////////////////////////
		
			$string_model .= " ,". "'".$Matrices_D . "" . $list . "'";
			$string_model_p .= " ,". "'".$Matrices_P . "" . $list . "'";
			
		}
		
		} else {
			$string_model = "'".$Matrices_D. "'";
			$string_model_p = "'".$Matrices_P. "'";
			
		}
		
		
		if($F =="checked"){
			
			
			$string_model_p= "'".$Matrices_P."+F". "'";
		}
		
		//echo "Start here : ".$string_model;
		/////////////////////////Filter Outpur//////////////////////////////
		
		
		
				
		
		/////////////Query Start //////////
		
		
		
		//check which select depending on input
		
		if($DNA_Prot == "dna"){
			
			// DNA w ModelP
			if( $OPT_uOPT == "modelparameters"){
				
				//W Newick String
				if($NewWick == "TRUE"){
			
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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

				$select2 = " COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
				ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
				ROUND(AVG(`mod`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`mod`.FREQ_C),4) AS AVG_FREQ_C,
				ROUND(AVG(`mod`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`mod`.FREQ_T),4) AS AVG_FREQ_T,
				ROUND(AVG(`mod`.RATE_AC),4) AS AVG_RATE_AC, ROUND(AVG(`mod`.RATE_AG),4) AS AVG_RATE_AG, ROUND(AVG(`mod`.RATE_AT),4) AS AVG_RATE_AT,
				ROUND(AVG(`mod`.RATE_CG),4) AS AVG_RATE_CG, ROUND(AVG(`mod`.RATE_CT),4) AS AVG_RATE_CT, ROUND(AVG(`mod`.RATE_GT),4) AS AVG_RATE_GT,
				ROUND(AVG(`mod`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`mod`.PROP_INVAR),5) AS AVG_PROP_INVAR,
				ROUND(AVG(`mod`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`mod`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
				ROUND(AVG(`mod`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`mod`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
				ROUND(AVG(`mod`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`mod`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
				ROUND(AVG(`mod`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`mod`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
				ROUND(AVG(`mod`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`mod`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
				ROUND(AVG(`mod`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`mod`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
				ROUND(AVG(`mod`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`mod`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
				ROUND(AVG(`mod`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`mod`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
				ROUND(AVG(`mod`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`mod`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
				ROUND(AVG(`mod`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`mod`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";


				//WO Newick String
				} else {
					
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
				ROUND(`mod`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`mod`.PROP_CAT_10,5) AS PROP_CAT_10";

				
				$select2 = " COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
				ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
				ROUND(AVG(`mod`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`mod`.FREQ_C),4) AS AVG_FREQ_C,
				ROUND(AVG(`mod`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`mod`.FREQ_T),4) AS AVG_FREQ_T,
				ROUND(AVG(`mod`.RATE_AC),4) AS AVG_RATE_AC, ROUND(AVG(`mod`.RATE_AG),4) AS AVG_RATE_AG, ROUND(AVG(`mod`.RATE_AT),4) AS AVG_RATE_AT,
				ROUND(AVG(`mod`.RATE_CG),4) AS AVG_RATE_CG, ROUND(AVG(`mod`.RATE_CT),4) AS AVG_RATE_CT, ROUND(AVG(`mod`.RATE_GT),4) AS AVG_RATE_GT,
				ROUND(AVG(`mod`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`mod`.PROP_INVAR),5) AS AVG_PROP_INVAR,
				ROUND(AVG(`mod`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`mod`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
				ROUND(AVG(`mod`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`mod`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
				ROUND(AVG(`mod`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`mod`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
				ROUND(AVG(`mod`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`mod`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
				ROUND(AVG(`mod`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`mod`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
				ROUND(AVG(`mod`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`mod`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
				ROUND(AVG(`mod`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`mod`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
				ROUND(AVG(`mod`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`mod`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
				ROUND(AVG(`mod`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`mod`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
				ROUND(AVG(`mod`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`mod`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";

				
				}  
					//DNA w Trees 
			}else {
				
				
				if($NewWick == "TRUE"){
			
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL,ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
				`tree`.NEWICK_STRING";

				$select2 ="COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
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
				ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";



				} else {
					
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL,ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
				ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10";

				$select2 ="COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
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
				ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";


				}
				
				
				
				
			}
			//Prot 
		}else {
			//Prot w Modelp
			if( $OPT_uOPT == "modelparameters"){
			
				if($NewWick == "TRUE"){
			
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT,ROUND(`tree`.TREE_LENGTH,5) as Tree_L, ROUND(`tree`.TREE_DIAMETER,5) as Tree_D,  
				ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_R,4) AS FREQ_R, ROUND(`tree`.FREQ_N,4) AS FREQ_N, 
				ROUND(`tree`.FREQ_D,4) AS FREQ_D, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_Q,4) AS FREQ_Q, 
				ROUND(`tree`.FREQ_E,4) AS FREQ_E, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_H,4) AS FREQ_H, 
				ROUND(`tree`.FREQ_I,4) AS FREQ_I, ROUND(`tree`.FREQ_L,4) AS FREQ_L, ROUND(`tree`.FREQ_K,4) AS FREQ_K, 
				ROUND(`tree`.FREQ_M,4) AS FREQ_M, ROUND(`tree`.FREQ_F,4) AS FREQ_F, ROUND(`tree`.FREQ_P,4) AS FREQ_P, 
				ROUND(`tree`.FREQ_S,4) AS FREQ_S, ROUND(`tree`.FREQ_T,4) AS FREQ_T, ROUND(`tree`.FREQ_W,4) AS FREQ_W, 
				ROUND(`tree`.FREQ_Y,4) AS FREQ_Y, ROUND(`tree`.FREQ_V,4) AS FREQ_V,
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

				$select2 ="COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
				ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
				ROUND(AVG(`mod`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`mod`.FREQ_R),4) AS AVG_FREQ_R, ROUND(AVG(`mod`.FREQ_N),4) AS AVG_FREQ_N,
				ROUND(AVG(`mod`.FREQ_D),4) AS AVG_FREQ_D, ROUND(AVG(`mod`.FREQ_C),4) AS AVG_FREQ_C, ROUND(AVG(`mod`.FREQ_Q),4) AS AVG_FREQ_Q,
				ROUND(AVG(`mod`.FREQ_E),4) AS AVG_FREQ_E, ROUND(AVG(`mod`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`mod`.FREQ_H),4) AS AVG_FREQ_H,
				ROUND(AVG(`mod`.FREQ_I),4) AS AVG_FREQ_I, ROUND(AVG(`mod`.FREQ_L),4) AS AVG_FREQ_L, ROUND(AVG(`mod`.FREQ_K),4) AS AVG_FREQ_K,
				ROUND(AVG(`mod`.FREQ_M),4) AS AVG_FREQ_M, ROUND(AVG(`mod`.FREQ_F),4) AS AVG_FREQ_F, ROUND(AVG(`mod`.FREQ_P),4) AS AVG_FREQ_P,
				ROUND(AVG(`mod`.FREQ_S),4) AS AVG_FREQ_S, ROUND(AVG(`mod`.FREQ_T),4) AS AVG_FREQ_T, ROUND(AVG(`mod`.FREQ_W),4) AS AVG_FREQ_W,
				ROUND(AVG(`mod`.FREQ_Y),4) AS AVG_FREQ_Y, ROUND(AVG(`mod`.FREQ_V),4) AS AVG_FREQ_V,
				ROUND(AVG(`mod`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`mod`.PROP_INVAR),5) AS AVG_PROP_INVAR,
				ROUND(AVG(`mod`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`mod`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
				ROUND(AVG(`mod`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`mod`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
				ROUND(AVG(`mod`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`mod`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
				ROUND(AVG(`mod`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`mod`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
				ROUND(AVG(`mod`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`mod`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
				ROUND(AVG(`mod`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`mod`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
				ROUND(AVG(`mod`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`mod`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
				ROUND(AVG(`mod`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`mod`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
				ROUND(AVG(`mod`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`mod`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
				ROUND(AVG(`mod`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`mod`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";

			
				} else {
					
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.BIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT,ROUND(`tree`.TREE_LENGTH,5) AS Tree_L , ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
				ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_R,4) AS FREQ_R, ROUND(`tree`.FREQ_N,4) AS FREQ_N, 
				ROUND(`tree`.FREQ_D,4) AS FREQ_D, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_Q,4) AS FREQ_Q, 
				ROUND(`tree`.FREQ_E,4) AS FREQ_E, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_H,4) AS FREQ_H, 
				ROUND(`tree`.FREQ_I,4) AS FREQ_I, ROUND(`tree`.FREQ_L,4) AS FREQ_L, ROUND(`tree`.FREQ_K,4) AS FREQ_K, 
				ROUND(`tree`.FREQ_M,4) AS FREQ_M, ROUND(`tree`.FREQ_F,4) AS FREQ_F, ROUND(`tree`.FREQ_P,4) AS FREQ_P, 
				ROUND(`tree`.FREQ_S,4) AS FREQ_S, ROUND(`tree`.FREQ_T,4) AS FREQ_T, ROUND(`tree`.FREQ_W,4) AS FREQ_W, 
				ROUND(`tree`.FREQ_Y,4) AS FREQ_Y, ROUND(`tree`.FREQ_V,4) AS FREQ_V,
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
				ROUND(`mod`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`mod`.PROP_CAT_10,5) AS PROP_CAT_10";


				$select2 ="COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
				ROUND(AVG(`tree`.TREE_LENGTH),5) AS Tree_L, ROUND(AVG(`tree`.TREE_DIAMETER),5) AS Tree_D,
				ROUND(AVG(`mod`.FREQ_A),4) AS AVG_FREQ_A, ROUND(AVG(`mod`.FREQ_R),4) AS AVG_FREQ_R, ROUND(AVG(`mod`.FREQ_N),4) AS AVG_FREQ_N,
				ROUND(AVG(`mod`.FREQ_D),4) AS AVG_FREQ_D, ROUND(AVG(`mod`.FREQ_C),4) AS AVG_FREQ_C, ROUND(AVG(`mod`.FREQ_Q),4) AS AVG_FREQ_Q,
				ROUND(AVG(`mod`.FREQ_E),4) AS AVG_FREQ_E, ROUND(AVG(`mod`.FREQ_G),4) AS AVG_FREQ_G, ROUND(AVG(`mod`.FREQ_H),4) AS AVG_FREQ_H,
				ROUND(AVG(`mod`.FREQ_I),4) AS AVG_FREQ_I, ROUND(AVG(`mod`.FREQ_L),4) AS AVG_FREQ_L, ROUND(AVG(`mod`.FREQ_K),4) AS AVG_FREQ_K,
				ROUND(AVG(`mod`.FREQ_M),4) AS AVG_FREQ_M, ROUND(AVG(`mod`.FREQ_F),4) AS AVG_FREQ_F, ROUND(AVG(`mod`.FREQ_P),4) AS AVG_FREQ_P,
				ROUND(AVG(`mod`.FREQ_S),4) AS AVG_FREQ_S, ROUND(AVG(`mod`.FREQ_T),4) AS AVG_FREQ_T, ROUND(AVG(`mod`.FREQ_W),4) AS AVG_FREQ_W,
				ROUND(AVG(`mod`.FREQ_Y),4) AS AVG_FREQ_Y, ROUND(AVG(`mod`.FREQ_V),4) AS AVG_FREQ_V,
				ROUND(AVG(`mod`.ALPHA),5) AS AVG_ALPHA, ROUND(AVG(`mod`.PROP_INVAR),5) AS AVG_PROP_INVAR,
				ROUND(AVG(`mod`.REL_RATE_CAT_1),5) AS AVG_RATE_CAT_1, ROUND(AVG(`mod`.PROP_CAT_1),5) AS AVG_PROP_CAT_1,
				ROUND(AVG(`mod`.REL_RATE_CAT_2),5) AS AVG_RATE_CAT_2, ROUND(AVG(`mod`.PROP_CAT_2),5) AS AVG_PROP_CAT_2,
				ROUND(AVG(`mod`.REL_RATE_CAT_3),5) AS AVG_RATE_CAT_3, ROUND(AVG(`mod`.PROP_CAT_3),5) AS AVG_PROP_CAT_3,
				ROUND(AVG(`mod`.REL_RATE_CAT_4),5) AS AVG_RATE_CAT_4, ROUND(AVG(`mod`.PROP_CAT_4),5) AS AVG_PROP_CAT_4,
				ROUND(AVG(`mod`.REL_RATE_CAT_5),5) AS AVG_RATE_CAT_5, ROUND(AVG(`mod`.PROP_CAT_5),5) AS AVG_PROP_CAT_5,
				ROUND(AVG(`mod`.REL_RATE_CAT_6),5) AS AVG_RATE_CAT_6, ROUND(AVG(`mod`.PROP_CAT_6),5) AS AVG_PROP_CAT_6,
				ROUND(AVG(`mod`.REL_RATE_CAT_7),5) AS AVG_RATE_CAT_7, ROUND(AVG(`mod`.PROP_CAT_7),5) AS AVG_PROP_CAT_7,
				ROUND(AVG(`mod`.REL_RATE_CAT_8),5) AS AVG_RATE_CAT_8, ROUND(AVG(`mod`.PROP_CAT_8),5) AS AVG_PROP_CAT_8,
				ROUND(AVG(`mod`.REL_RATE_CAT_9),5) AS AVG_RATE_CAT_9, ROUND(AVG(`mod`.PROP_CAT_9),5) AS AVG_PROP_CAT_9,
				ROUND(AVG(`mod`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`mod`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";
				}  
			//Prot w Trees 		
			}else {
				
				
				if($NewWick == "TRUE"){
			
				$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
				`tree`.NEWICK_STRING";

				$select2 = "COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
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
				ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";



				} else {
					
					$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
					`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
					`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
					ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10";


					$select2 = "COUNT(`ali`.ALI_ID) AS NR_HITS, ROUND(AVG(`ali`.TAXA), 4) AS AVG_TAXA, ROUND(AVG(`ali`.SITES),4) AS AVG_SITES, ROUND(AVG(`ali`.DISTINCT_PATTERNS), 4) AS AVG_DISTINCT_PATTERNS,
				ROUND(AVG(`ali`.PARSIMONY_INFORMATIVE_SITES),4) AS AVG_PARSIMONY_INFORMATIVE_SITES, ROUND(AVG(`ali`.FRAC_WILDCARDS_GAPS),4) AS AVG_FRAC_WILDCARDS_GAPS, ROUND(AVG(`ali`.DIST_MEAN),4) AS AVG_DIST_MEAN,
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
				ROUND(AVG(`tree`.REL_RATE_CAT_10),5) AS AVG_RATE_CAT_10, ROUND(AVG(`tree`.PROP_CAT_10),5) AS AVG_PROP_CAT_10";
				}
			
			
			
		}
		
		}
		
	
		
	
			
			
			$f_d_query_1 = " SELECT ".$select . " FROM ";
	
				
				//$f_d_query = " SELECT count(*) FROM ";
				$f_d_query ="SELECT ".$select2. " FROM ";
		
		
		
	
		
			// Activate Try here 
		try {
			
			//decide to search in Proteins or DNA 
			
			if($DNA_Prot == "dna"){
				
				
				//decide to search in modelparameters or trees
				
				if( $OPT_uOPT == "modelparameters"){
					
					//Alignment join
					$f_d_query .= "`dna_alignments` as `ali`  INNER JOIN `dna_modelparameters` as `mod` USING (`ALI_ID`)";
					//Tree Join 
					$f_d_query .= " INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) " ;
					$f_d_query .= " WHERE `mod`.`MODEL` in " . "(" . $string_model. ")";
					$f_d_query .= " AND `mod`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'initial' ";
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					
					//Alignment join
					$f_d_query_1 .= "`dna_alignments` as `ali` INNER JOIN `dna_modelparameters` as `mod` USING (`ALI_ID`)";
					//Tree Join 
					$f_d_query_1 .= " INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) " ;
					$f_d_query_1 .= " WHERE `mod`.`MODEL` in " . "(" . $string_model. ")";
					$f_d_query_1 .= " AND `mod`.`ORIGINAL_ALI` = 1 ";
					$f_d_query_1 .= " AND `tree`.`TREE_TYPE` =  'initial' ";
					$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					if($Trees_Specs_Check== "TRUE"){
						
						//Joins earlier due to nature of seach 
						//$f_d_query .= " INNER JOIN `dna_models` ON (`dna_modelparameters`.`BASE_MODEL` = `dna_models`.`MODEL_NAME` ) ";
						//$f_d_query .= " INNER JOIN `dna_trees` ON (`dna_models`.`MODEL_NAME` = `dna_trees`.`BASE_MODEL`) AND (`dna_trees`.`ALI_ID` = `dna_alignments`.`ALI_ID`) " ;
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						if(!empty($Max_tree_len)){
					
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
						
					}
						
						
					//  is Allignment Checked if yes catch data
					if($Alignment_Specs_Check == "TRUE"){
						
						

						
						//Since we already inner joined in dna allignments we can collect the data 
						
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
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}
						// fraction parsimony sies
						if(!empty($parsimony_sites_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`PARSIMONY_INFORMATIVE_SITES` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $parsimony_sites_fraction;
							
						}
						//fraction of patterns
						if(!empty($distinct_patterns_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`DISTINCT_PATTERNS` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $distinct_patterns_fraction;
							
						}
						
						//wildcard gaps
						if(!empty($wildcard_gaps_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`FRAC_WILDCARDS_GAPS` <= ? ';
							$f_d_parameters[] =  $wildcard_gaps_fraction;
							
						}

						

				
						
					}
						
					
					
					
					///////////////////////////////////////////////////
					
	
						//AIC check 
					if( !empty($AIC)){
						
						$f_d_conditions[] =  ' `mod`.`AIC_WEIGHT` >= ? ';
						$f_d_parameters[] =  $AIC;
						
					}
					
					
					if( !empty($AICC)){
											
						$f_d_conditions[] =  ' `mod`.`AICC_WEIGHT` >= ? ';
						$f_d_parameters[] =  $AICC;
						
						
					}
					
										
					
					if( !empty($BIC)){
						
						$f_d_conditions[] =  ' `mod`.`BIC_WEIGHT` >= ?';
						$f_d_parameters[] =  $BIC;
						
					}
					
					
					
					
					
					
					
					
					
					
					// if !Modelparameters must be trees due to natiure of form valdiation radio button	
				} else {
					
					
					//Count
					$f_d_query .= "`dna_alignments` as `ali`  INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `tree`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					//Preview
					$f_d_query_1 .= "`dna_alignments` as `ali`  INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query_1 .= " WHERE `tree`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query_1 .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in dna allignments we can collect the data 
						
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
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}
						// fraction parsimony sies
						if(!empty($parsimony_sites_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`PARSIMONY_INFORMATIVE_SITES` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $parsimony_sites_fraction;
							
						}
						//fraction of patterns
						if(!empty($distinct_patterns_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`DISTINCT_PATTERNS` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $distinct_patterns_fraction;
							
						}

						//wildcard gaps
						if(!empty($wildcard_gaps_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`FRAC_WILDCARDS_GAPS` <= ? ';
							$f_d_parameters[] =  $wildcard_gaps_fraction;
							
						}




						
					}
						
					
					
					if($Trees_Specs_Check== "TRUE"){
						
					
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						if(!empty($Max_tree_len)){
				
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
					}					
				}
				
					// if !DNA must be Proteins
					
				} else {
					// Same code with Protein
					if( $OPT_uOPT == "modelparameters"){
				
					//Alignment join
					$f_d_query .= "`aa_alignments` as `ali` INNER JOIN `aa_modelparameters` as `mod`  USING (`ALI_ID`)";
					//Tree Join 
					$f_d_query .= " INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) " ;
					$f_d_query .= " WHERE `mod`.`MODEL` in " . "(" . $string_model_p. ")";
					$f_d_query .= " AND `mod`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'initial' ";
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					//Preview
					//Alignment join
					$f_d_query_1 .= "`aa_alignments` as `ali` INNER JOIN `aa_modelparameters` as `mod`  USING (`ALI_ID`)";
					//Tree Join 
					$f_d_query_1 .= " INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) " ;
					$f_d_query_1 .= " WHERE `mod`.`MODEL` in " . "(" . $string_model_p. ")";
					$f_d_query_1 .= " AND `mod`.`ORIGINAL_ALI` = 1 ";
					$f_d_query_1.= " AND `tree`.`TREE_TYPE` =  'initial' ";
					$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					
					
					
				
					
					if($Trees_Specs_Check== "TRUE"){
						
						//Joins earlier due to nature of seach 
						
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
							
						if(!empty($Max_tree_len)){
				
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
						
						
						/* Tree topology
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` = ? ';
							$f_d_parameters[] =  $mean_dis;
							
							
							
						}
						*/ 
						
					}

					//Add SourceList
						
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
						
						
					//  is Allignment Checked if yes catch data
					if($Alignment_Specs_Check == "TRUE"){
						
						
						
						
						//Since we already inner joined in aa allignments we can collect the data 
						
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
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}


						// fraction parsimony sies
						if(!empty($parsimony_sites_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`PARSIMONY_INFORMATIVE_SITES` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $parsimony_sites_fraction;
							
						}
						//fraction of patterns
						if(!empty($distinct_patterns_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`DISTINCT_PATTERNS` / `ali`.`SITES` >= ? ';
							$f_d_parameters[] =  $distinct_patterns_fraction;
							
						}

						//wildcard gaps
						if(!empty($wildcard_gaps_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`FRAC_WILDCARDS_GAPS` <= ? ';
							$f_d_parameters[] =  $wildcard_gaps_fraction;
							
						}
						
					}
					
						// Check matrices
						
						
						
						
						
						/* Old
					if(!empty($Matrices_D && $RHAS)){
						
						
						$f_d_conditions[] =  ' `aa_modelparameters`.`BASE_MODEL` = ? ';
						$f_d_parameters[] =  $modeld;
						
						
					}
					
					*/ //////////////////////New//////////////////////
					
					
					
					
					
					
					///////////////////////////////////////////////////
					
	
						//AIC check 
					if( !empty($AIC)){
						
						$f_d_conditions[] =  ' `mod`.`AIC_WEIGHT` >= ? ';
						$f_d_parameters[] =  $AIC;
						
					}
					
					
					if( !empty($AICC)){
											
						$f_d_conditions[] =  ' `mod`.`AICC_WEIGHT` >= ? ';
						$f_d_parameters[] =  $AICC;
						
						
					}
					
										
					
					if( !empty($BIC)){
						
						$f_d_conditions[] =  ' `mod`.`BIC_WEIGHT` >= ?';
						$f_d_parameters[] =  $BIC;
						
					}
					
					// Insert ML or initial data catch w if here 
					
					
					
					
					
					
					
					
					// if !Modelparameters must be trees due to natiure of form valdiation radio button	
				} else {
					
					//OLD 
					/*
					$f_d_query .= "`dna_trees` INNER JOIN `aa_alignments` ON (`dna_trees`.`ALI_ID` = `aa_alignments`.`ALI_ID` )";
					$f_d_query .= " WHERE `dna_trees`.`BASE_MODEL` in " . "(" . $string_model. ")";
					*/ 
					
					//new
					$f_d_query .= "`aa_alignments` as `ali`  INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `tree`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					//Preview
					$f_d_query_1 .= "`aa_alignments` as `ali`  INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query_1 .= " WHERE `tree`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query_1 .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query_1 .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
							
						}
					
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in aa allignments we can collect the data 
						
						
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
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `ali`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}

						// fraction parsimony sies
						if(!empty($parsimony_sites_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`PARSIMONY_INFORMATIVE_SITES` / `ali`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $parsimony_sites_fraction;
							
						}
						//fraction of patterns
						if(!empty($distinct_patterns_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`DISTINCT_PATTERNS` / `ali`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $distinct_patterns_fraction;
							
						}
						//wildcard gaps
						if(!empty($wildcard_gaps_fraction)){
							
							$f_d_conditions[] =  ' `ali`.`FRAC_WILDCARDS_GAPS` <= ? ';
							$f_d_parameters[] =  $wildcard_gaps_fraction;
							
						}
						
					}
						
					
					
					if($Trees_Specs_Check== "TRUE"){
						
					
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}

						if(!empty($Max_tree_len)){
				
							$f_d_conditions[] =  ' `tree`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `tree`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
					}							
				}	
			}
					
				
				
				
				//Dynamic Query ///////////
				
				
				
				// fuze conditions 
				if($f_d_conditions){
					
				$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);
				$f_d_query_1 .= " AND ".implode(" AND ", $f_d_conditions);	
				}		

				$f_d_query_1 .= " LIMIT 20";
				
				 

				
				////////////////////
				
				 // Check if Statements 
				 
				// echo ($f_d_query_1);
			
			
		
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage();
			}


?>

