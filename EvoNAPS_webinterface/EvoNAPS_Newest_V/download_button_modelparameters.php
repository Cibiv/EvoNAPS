
<?php
//start session here in order to catch variables
session_start();

include "DB_credentials.php";

	error_reporting(0);

		// initialize variables for the filter 
		

		$DNA_Prot = $_SESSION['datatype'];
		
		
		$Matrices_D = $_SESSION['DNA_model'];
		$Matrices_P = $_SESSION['Protein_model'];
		
		$OPT_uOPT = $_SESSION['query_type'];
		
		$AIC = $_SESSION['AIC'];
		$AICC = $_SESSION['AICC'];
		$BIC = $_SESSION['BIC'];
		
		$Nr_Seq = $_SESSION['number_of_sequences'];
		$Max_Nr_Seq = $_SESSION['max_number_of_sequences'];
		
		$Nr_sites = $_SESSION['number_of_sites'];
		$Max_Nr_sites = $_SESSION['max_number_of_sites'];
		
		$mean_dis = $_SESSION['mean_distance'];
		$Max_mean_dis = $_SESSION['max_mean_distance'];
		
		$tree_len = $_SESSION['tree_length'];
		$Max_tree_len = $_SESSION['max_tree_length'];
		
		$tree_dia = $_SESSION['tree_diameter'];
		$Max_tree_dia = $_SESSION['max_tree_diameter'];
		
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


			$wildcard_gaps_fraction = $_SESSION['wildcard_gaps_fraction'];
		
		
			$parsimony_sites_fraction=$_SESSION['parsimony_sites_fraction'];

		
			$distinct_patterns_fraction =$_SESSION['distinct_patterns_fraction'];
		
		
		$Hits = $_SESSION['Hits_anzeigen'];
		
		$Initial_Tree = "initial";
		$ML_Tree = "ml";
		
		
		$NewWick = $_SESSION['Newick'];		
		
		// Dynamic Querys Parameters
		
		
		$f_d_conditions = [];
		$f_d_parameters = [];
		
		
		/// Model Combination RHAS + BASE model
		
		$model = [];
		$E = $_SESSION['RHAS_uniform'];
		$I = $_SESSION['RHAS_I'];
		$G4 = $_SESSION['RHAS_G4'];
		$IG4 = $_SESSION['RHAS_IG4'];
		$R2 = $_SESSION['RHAS_R2'];
		$R3 =$_SESSION['RHAS_R3'];
		$R4 =$_SESSION['RHAS_R4'];
		$R5 =$_SESSION['RHAS_R5'];
		$R6 =$_SESSION['RHAS_R6'];
		$R7	=$_SESSION['RHAS_R7'];
		$R8 =$_SESSION['RHAS_R8'];
		$R9 =$_SESSION['RHAS_R9'];
		$R10 =$_SESSION['RHAS_R10'];
		$F = $_SESSION['+F'];
		
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


/////////////////////String Building Source ///////////////////////

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
	////////////////String Building Model////////////////////////////////////
		
		
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
				
				$string_model .= " 'Index '";
				$string_model_p .= " 'Index '";
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
		
		
		//check which select depending on input
		
		if($DNA_Prot == "dna"){
			
			// DNA w ModelP
			if( $OPT_uOPT == "modelparameters"){
				
				
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
				}    
					//DNA w Trees 
			}else {
				
				
				if($NewWick == "TRUE"){
			
					$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
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
					} else {
						
						$select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
						`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
						`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
						ROUND(`mod`.LOGL,4) AS LOGL, 
						ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
						ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
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
					}			
			}
		}
			$f_d_query = " SELECT ".$select . " FROM ";
		
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
					
		
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
							
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
					
					
					//Joins
					$f_d_query .= "`dna_alignments` as `ali`  INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `tree`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
							
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
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
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
					
				//Joins
					$f_d_query .= "`aa_alignments` as `ali`  INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `tree`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query .= " AND `tree`.`ORIGINAL_ALI` = 1 ";
					$f_d_query .= " AND `tree`.`TREE_TYPE` =  'ml' ";
					
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `ali`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `ali`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
							
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
				
		// check if sth got added to conditions if yes, add the SQL statemens together in the condition array anf form the string
		if($f_d_conditions)

		// implode function to convert array in string, delimter with AND and add the "Where" clause to the string
		$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);	
					
		// Bind the values of f_d_parameters array to the the SQL statements from the conditions array 								
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

		

		//fetch data
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);

			if($OPT_uOPT == " "){
				
				//Comes later maybe make 
				header('Content-Type: text/tsv; charset=utf-8');
				header('Content-Disposition: attachment; filename=modelparameters_modelfinder.tsv');
				
				
			}
				
		/// set headers for donwnloading the data			
		header('Content-Type: text/tsv; charset=utf-8');
		header('Content-Disposition: attachment; filename=modelparameters_modelfinder.tsv');
		
		//created file
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = false; 
		$output = " ";
		//$fasta = ">";
		$date = "##".date('Y/m/d H:i:s')."\n";
		$counter = 0;
		//clear history 
		ob_clean();

		// loop throgh the data and write results
		foreach ($filter_query_result as $list) {
			 
			
			///download me 	
			if(!$headers_printed){
				
			fwrite($output_file,"#############################\n");
			fwrite($output_file,"##         EvoNAPS         ##\n");
			fwrite($output_file,"#############################\n");
			fwrite($output_file,$date);
			fwrite($output_file,"#############################\n");
			fwrite($output_file,"## Used search parameters\n");
			
			foreach($_SESSION as $names => $values){
	  
				if(!empty($values)){
				fwrite($output_file, "## ".$names.": ".$values	);
				 
				  fwrite($output_file,"\n");
			  }  
			}
			fwrite($output_file,"##");
			fwrite($output_file,"\n");
			fwrite($output_file,"##");
			fwrite($output_file,"\n");

			//dna mod w newick
			if($DNA_Prot == "dna"){

				if($OPT_uOPT=="modelparameters"){

					if($NewWick == "TRUE"){

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','NEWICK_STRING'),"\t");
				$headers_printed = true;
				
					//-.- wo newick
				}else{

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
				$headers_printed = true;
				}
				//dna trees w newick
			}else{

				if($NewWick == "TRUE"){

				fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','NEWICK_STRING'),"\t");
				$headers_printed = true;

				//wo newick
				}else{

				fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
				$headers_printed = true;
				}

			}
				//proteins
			}else{

				if($OPT_uOPT=="modelparameters"){

					if($NewWick == "TRUE"){

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
				'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
				'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','NEWICK_STRING'),"\t");
				$headers_printed = true;
				
					//-.- wo newick
				}else{

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
				'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
				'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
				$headers_printed = true;
				}
				//dna trees w newick
			}else{

				if($NewWick == "TRUE"){

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
					'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
					'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
					'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR','RATE_AC',
					'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
					'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
					'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','NEWICK_STRING'),"\t");
					$headers_printed = true;

				//wo newick
				}else{

					fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
					'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
					'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
					'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR','RATE_AC',
					'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
					'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
					'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','NEWICK_STRING'),"\t");
					$headers_printed = true;
				}

			}


			}
		
			
		}
		// Write Results in Document 
		fputcsv($output_file,$list,"\t");
		fpassthru($output_file);
		// counter for testing activate if needed
		$counter++;
			

		}
		// for testing purposes if nr of hits =! number of download 
		fwrite($output_file,"Nr of Hits:".$counter);
			
		//exception handlong
		
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage();
			}
			
			
			$connect = null;
			
			
	?>