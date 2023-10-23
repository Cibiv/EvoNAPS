
<?php

session_start();

include "DB_credentials.php";
//ini_set('memory_limit','1000M');
	error_reporting(0);
		
		
		
	

		
		// Set Variables for the Filter 
		
		
		$DNA_Prot = $_SESSION['datatype'];
		
		
		$Matrices_D = $_SESSION['DNA_model'];
		$Matrices_P = $_SESSION['Protein_model'];
		
		
		//$modelp = $Matrices_P." ".$RHAS;
		//$modeld = $Matrices_D." ".$RHAS;
		
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
		//$tree_top = $_SESSION['tree_top'];
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
		
		
		$Hits = $_SESSION['Hits_anzeigen'];
		
		$Initial_Tree = "initial";
		$ML_Tree = "ml";
		
		
		$NewWick = $_SESSION['Newick'];
		//Variables for the Hit
		
		 
		
		
		
		
		
		// Dynamic Querys Parameters
		
		
		$f_d_conditions = [];
		$f_d_parameters = [];
		
		
		
		/// Model Combination RHAS + BASE model
		
		$model = [];
		$E = $_SESSION['RHAS_uniform'];
		$I = $_SESSION['RHAS_I'];
		$G4 = $_SESSION['RHAS_G4'];
		$IG4 = $_SESSION['RHAS_IG4'];
		//echo $IG4;
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
		if(isset($_SESSION['OrthoMaM'])){
		$Ortho =$_SESSION['OrthoMaM'];
		}
		if(isset($_SESSION['Lanfear'])){
		$Lanf =$_SESSION['Lanfear'];
		}
		$ALL = $_SESSION['selectAll'];




		$f_d_conditions = [];
		$f_d_parameters = [];




/////////////////////String Building Source ///////////////////////

$stringsource = "";
$stringall = "'PANDIT','OrthoMaM','Lanfear'";

if(!empty($Ortho)){
			
			$Source[] = $Ortho;
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
	
	echo $stringsource;
		
		





		
		
		
		
	////////////////String Building Model/////////////////////////////////////////////
		
		
		$string_model = "";
		$string_model_p = "";
		
		
		/* old Uniform
		if(!empty($E)){
			
			$model[] = $E;
		}
		*/ 
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
		
		//echo $E;
		
		
		
		/////////////Loop for String Building////////////////////////////
		$first_one = false;
		
		
		if(!empty($model)){
			
		foreach($model as $list){
			
			if($first_one == false){
				
				//$string_model .= "'".$Matrices_D . " " . $list . "'";
				//$string_model .= " 'Index '". "," ."'". $Matrices_D. "'";
				//$string_model_p .= " 'Index '". ","."'". $Matrices_P."'";
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
		
		
		//echo "Start here : ".$string_model;
		/////////////////////////Filter Outpur//////////////////////////////
		
		
		
				
		
		/////////////Query Start //////////
		
		
		
		//check which select depending on input
		
		if($DNA_Prot == "dna"){
			
			// DNA w ModelP
			if( $OPT_uOPT == "modelparameters"){
				
				
				if($NewWick == "TRUE"){
			
				$select = "`dna_alignments` .`ALI_ID`, `dna_modelparameters`.MODEL, `dna_modelparameters`.BASE_MODEL, `dna_modelparameters`.`MODEL_RATE_HETEROGENEITY`,`dna_modelparameters`.`LOGL`, `dna_modelparameters`.`BIC`, `dna_modelparameters`.`WEIGHTED_BIC`,`dna_modelparameters`.`STAT_FREQ_A`, `dna_modelparameters`.`STAT_FREQ_C`, `dna_modelparameters`.`STAT_FREQ_G`, `dna_modelparameters`.`STAT_FREQ_T`,`dna_modelparameters`.`RATE_AC`, `dna_modelparameters`.`RATE_AG`, `dna_modelparameters`.`RATE_AT`, `dna_modelparameters`.`RATE_CG`, `dna_modelparameters`.`RATE_CT`, `dna_modelparameters`.`RATE_GT`, `dna_modelparameters`.`ALPHA`, `dna_modelparameters`.`PROP_INVAR`,`dna_modelparameters`.`REL_RATE_CAT_1`, `dna_modelparameters`.`PROP_CAT_1` , `dna_modelparameters`.`REL_RATE_CAT_2` , `dna_modelparameters`.`PROP_CAT_2`, `dna_modelparameters`.`REL_RATE_CAT_3`, `dna_modelparameters`.`PROP_CAT_3`,`dna_modelparameters`.`REL_RATE_CAT_4`, `dna_modelparameters`.`PROP_CAT_4`, `dna_modelparameters`.`REL_RATE_CAT_5`, `dna_modelparameters`.`PROP_CAT_5`, `dna_modelparameters`.`REL_RATE_CAT_6`, `dna_modelparameters`.`PROP_CAT_6`,`dna_modelparameters`.`REL_RATE_CAT_7`, `dna_modelparameters`.`PROP_CAT_7` , `dna_modelparameters`.`REL_RATE_CAT_8`, `dna_modelparameters`.`PROP_CAT_8`, `dna_modelparameters`.`REL_RATE_CAT_9`, `dna_modelparameters`.`PROP_CAT_9`,`dna_modelparameters`.`REL_RATE_CAT_10` , `dna_modelparameters`.`PROP_CAT_10`, `dna_trees`.`NEWICK_STRING`" ;
			
				} else {
					
				$select = "`dna_alignments` .`ALI_ID`,  `dna_modelparameters`.MODEL, `dna_modelparameters`.`MODEL_RATE_HETEROGENEITY`,CAST(`dna_modelparameters`.`LOGL` AS DECIMAL(7,2)),CAST( `dna_modelparameters`.`BIC` AS DECIMAL(7,2)), `dna_modelparameters`.`WEIGHTED_BIC`,CAST(`dna_modelparameters`.`STAT_FREQ_A`AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`STAT_FREQ_C`AS DECIMAL(7,4)), CAST(`dna_modelparameters`.`STAT_FREQ_G`AS DECIMAL(7,4)), CAST( `dna_modelparameters`.`STAT_FREQ_T`AS DECIMAL(7,4)),CAST(`dna_modelparameters`.`RATE_AC` AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`RATE_AG`AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`RATE_AT`AS DECIMAL(7,4)), `dna_modelparameters`.`RATE_CG`, `dna_modelparameters`.`RATE_CT`, `dna_modelparameters`.`RATE_GT`, `dna_modelparameters`.`ALPHA`, `dna_modelparameters`.`PROP_INVAR`,`dna_modelparameters`.`REL_RATE_CAT_1`, `dna_modelparameters`.`PROP_CAT_1` , `dna_modelparameters`.`REL_RATE_CAT_2` , `dna_modelparameters`.`PROP_CAT_2`, `dna_modelparameters`.`REL_RATE_CAT_3`, `dna_modelparameters`.`PROP_CAT_3`,`dna_modelparameters`.`REL_RATE_CAT_4`, `dna_modelparameters`.`PROP_CAT_4`, `dna_modelparameters`.`REL_RATE_CAT_5`, `dna_modelparameters`.`PROP_CAT_5`, `dna_modelparameters`.`REL_RATE_CAT_6`, `dna_modelparameters`.`PROP_CAT_6`,`dna_modelparameters`.`REL_RATE_CAT_7`, `dna_modelparameters`.`PROP_CAT_7` , `dna_modelparameters`.`REL_RATE_CAT_8`, `dna_modelparameters`.`PROP_CAT_8`, `dna_modelparameters`.`REL_RATE_CAT_9`, `dna_modelparameters`.`PROP_CAT_9`,`dna_modelparameters`.`REL_RATE_CAT_10` , `dna_modelparameters`.`PROP_CAT_10` " ;
			
				}  
					//DNA w Trees 
			}else {
				
				
				if($NewWick == "TRUE"){
			
				$select = "`dna_alignments` .`ALI_ID`, `dna_trees`.`KEEP_IDENT`, `dna_trees`.MODEL, `dna_trees`.BASE_MODEL, `dna_trees`.`MODEL_RATE_HETEROGENEITY`,`dna_trees`.`LOGL`, `dna_trees`.`BIC`,`dna_trees`.`STAT_FREQ_A`, `dna_trees`.`STAT_FREQ_C`, `dna_trees`.`STAT_FREQ_G`, `dna_trees`.`STAT_FREQ_T`,`dna_trees`.`RATE_AC`, `dna_trees`.`RATE_AG`, `dna_trees`.`RATE_AT`, `dna_trees`.`RATE_CG`, `dna_trees`.`RATE_CT`, `dna_trees`.`RATE_GT`, `dna_trees`.`ALPHA`, `dna_trees`.`PROP_INVAR`,`dna_trees`.`REL_RATE_CAT_1`, `dna_trees`.`PROP_CAT_1` , `dna_trees`.`REL_RATE_CAT_2` , `dna_trees`.`PROP_CAT_2`, `dna_trees`.`REL_RATE_CAT_3`, `dna_trees`.`PROP_CAT_3`,`dna_trees`.`REL_RATE_CAT_4`, `dna_trees`.`PROP_CAT_4`, `dna_trees`.`REL_RATE_CAT_5`, `dna_trees`.`PROP_CAT_5`, `dna_trees`.`REL_RATE_CAT_6`, `dna_trees`.`PROP_CAT_6`,`dna_trees`.`REL_RATE_CAT_7`, `dna_trees`.`PROP_CAT_7` , `dna_trees`.`REL_RATE_CAT_8`, `dna_trees`.`PROP_CAT_8`, `dna_trees`.`REL_RATE_CAT_9`, `dna_trees`.`PROP_CAT_9`,`dna_trees`.`REL_RATE_CAT_10` , `dna_trees`.`PROP_CAT_10`, `dna_trees`.`NEWICK_STRING`" ;
			
				} else {
					
				$select = "`dna_alignments` .`ALI_ID`, `dna_trees`.`KEEP_IDENT`, `dna_trees`.MODEL, `dna_trees`.BASE_MODEL, `dna_trees`.`MODEL_RATE_HETEROGENEITY`,`dna_trees`.`LOGL`, `dna_trees`.`BIC`,`dna_trees`.`STAT_FREQ_A`, `dna_trees`.`STAT_FREQ_C`, `dna_trees`.`STAT_FREQ_G`, `dna_trees`.`STAT_FREQ_T`,`dna_trees`.`RATE_AC`, `dna_trees`.`RATE_AG`, `dna_trees`.`RATE_AT`, `dna_trees`.`RATE_CG`, `dna_trees`.`RATE_CT`, `dna_trees`.`RATE_GT`, `dna_trees`.`ALPHA`, `dna_trees`.`PROP_INVAR`,`dna_trees`.`REL_RATE_CAT_1`, `dna_trees`.`PROP_CAT_1` , `dna_trees`.`REL_RATE_CAT_2` , `dna_trees`.`PROP_CAT_2`, `dna_trees`.`REL_RATE_CAT_3`, `dna_trees`.`PROP_CAT_3`,`dna_trees`.`REL_RATE_CAT_4`, `dna_trees`.`PROP_CAT_4`, `dna_trees`.`REL_RATE_CAT_5`, `dna_trees`.`PROP_CAT_5`, `dna_trees`.`REL_RATE_CAT_6`, `dna_trees`.`PROP_CAT_6`,`dna_trees`.`REL_RATE_CAT_7`, `dna_trees`.`PROP_CAT_7` , `dna_trees`.`REL_RATE_CAT_8`, `dna_trees`.`PROP_CAT_8`, `dna_trees`.`REL_RATE_CAT_9`, `dna_trees`.`PROP_CAT_9`,`dna_trees`.`REL_RATE_CAT_10` , `dna_trees`.`PROP_CAT_10` " ;
			
				}
				
				
				
				
			}
			//Prot 
		}else {
			//Prot w Modelp
			if( $OPT_uOPT == "modelparameters"){
				
				
				if($NewWick == "TRUE"){
			
				$select = "`aa_alignments` .`ALI_ID`, `aa_modelparameters`.`KEEP_IDENT`, `aa_modelparameters`.MODEL, `aa_modelparameters`.BASE_MODEL, `aa_modelparameters`.`MODEL_RATE_HETEROGENEITY`,`aa_modelparameters`.`LOGL`, `aa_modelparameters`.`BIC`, `aa_modelparameters`.`WEIGHTED_BIC`,`aa_modelparameters`.`STAT_FREQ_A`, `aa_modelparameters`.`STAT_FREQ_C`, `aa_modelparameters`.`STAT_FREQ_G`, `aa_modelparameters`.`STAT_FREQ_T`,`aa_modelparameters`.`ALPHA`, `aa_modelparameters`.`PROP_INVAR`,`aa_modelparameters`.`REL_RATE_CAT_1`, `aa_modelparameters`.`PROP_CAT_1` , `aa_modelparameters`.`REL_RATE_CAT_2` , `aa_modelparameters`.`PROP_CAT_2`, `aa_modelparameters`.`REL_RATE_CAT_3`, `aa_modelparameters`.`PROP_CAT_3`,`aa_modelparameters`.`REL_RATE_CAT_4`, `aa_modelparameters`.`PROP_CAT_4`, `aa_modelparameters`.`REL_RATE_CAT_5`, `aa_modelparameters`.`PROP_CAT_5`, `aa_modelparameters`.`REL_RATE_CAT_6`, `aa_modelparameters`.`PROP_CAT_6`,`aa_modelparameters`.`REL_RATE_CAT_7`, `aa_modelparameters`.`PROP_CAT_7` , `aa_modelparameters`.`REL_RATE_CAT_8`, `aa_modelparameters`.`PROP_CAT_8`, `aa_modelparameters`.`REL_RATE_CAT_9`, `aa_modelparameters`.`PROP_CAT_9`,`aa_modelparameters`.`REL_RATE_CAT_10` , `aa_modelparameters`.`PROP_CAT_10`, `aa_trees`.`NEWICK_STRING`" ;
			
				} else {
					
				$select = "`aa_alignments` .`ALI_ID`, `aa_modelparameters`.`KEEP_IDENT`, `aa_modelparameters`.MODEL, `aa_modelparameters`.BASE_MODEL, `aa_modelparameters`.`MODEL_RATE_HETEROGENEITY`,`aa_modelparameters`.`LOGL`, `aa_modelparameters`.`BIC`, `aa_modelparameters`.`WEIGHTED_BIC`,`aa_modelparameters`.`STAT_FREQ_A`, `aa_modelparameters`.`STAT_FREQ_C`, `aa_modelparameters`.`STAT_FREQ_G`, `aa_modelparameters`.`STAT_FREQ_T`, `aa_modelparameters`.`ALPHA`, `aa_modelparameters`.`PROP_INVAR`,`aa_modelparameters`.`REL_RATE_CAT_1`, `aa_modelparameters`.`PROP_CAT_1` , `aa_modelparameters`.`REL_RATE_CAT_2` , `aa_modelparameters`.`PROP_CAT_2`, `aa_modelparameters`.`REL_RATE_CAT_3`, `aa_modelparameters`.`PROP_CAT_3`,`aa_modelparameters`.`REL_RATE_CAT_4`, `aa_modelparameters`.`PROP_CAT_4`, `aa_modelparameters`.`REL_RATE_CAT_5`, `aa_modelparameters`.`PROP_CAT_5`, `aa_modelparameters`.`REL_RATE_CAT_6`, `aa_modelparameters`.`PROP_CAT_6`,`aa_modelparameters`.`REL_RATE_CAT_7`, `aa_modelparameters`.`PROP_CAT_7` , `aa_modelparameters`.`REL_RATE_CAT_8`, `aa_modelparameters`.`PROP_CAT_8`, `aa_modelparameters`.`REL_RATE_CAT_9`, `aa_modelparameters`.`PROP_CAT_9`,`aa_modelparameters`.`REL_RATE_CAT_10` , `aa_modelparameters`.`PROP_CAT_10` " ;
			
				}  
			//Prot w Trees 		
			}else {
				
				
				if($NewWick == "TRUE"){
			
				$select = "`aa_alignments` .`ALI_ID`, `aa_trees`.`KEEP_IDENT`, `aa_trees`.MODEL, `aa_trees`.BASE_MODEL, `aa_trees`.`MODEL_RATE_HETEROGENEITY`,`aa_trees`.`LOGL`, `aa_trees`.`BIC`,`aa_trees`.`STAT_FREQ_A`, `aa_trees`.`STAT_FREQ_C`, `aa_trees`.`STAT_FREQ_G`, `aa_trees`.`STAT_FREQ_T`, `aa_trees`.`ALPHA`, `aa_trees`.`PROP_INVAR`,`aa_trees`.`REL_RATE_CAT_1`, `aa_trees`.`PROP_CAT_1` , `aa_trees`.`REL_RATE_CAT_2` , `aa_trees`.`PROP_CAT_2`, `aa_trees`.`REL_RATE_CAT_3`, `aa_trees`.`PROP_CAT_3`,`aa_trees`.`REL_RATE_CAT_4`, `aa_trees`.`PROP_CAT_4`, `aa_trees`.`REL_RATE_CAT_5`, `aa_trees`.`PROP_CAT_5`, `aa_trees`.`REL_RATE_CAT_6`, `aa_trees`.`PROP_CAT_6`,`aa_trees`.`REL_RATE_CAT_7`, `aa_trees`.`PROP_CAT_7` , `aa_trees`.`REL_RATE_CAT_8`, `aa_trees`.`PROP_CAT_8`, `aa_trees`.`REL_RATE_CAT_9`, `aa_trees`.`PROP_CAT_9`,`aa_trees`.`REL_RATE_CAT_10` , `aa_trees`.`PROP_CAT_10`, `aa_trees`.`NEWICK_STRING`" ;
			
				} else {
					
				$select = "`aa_alignments` .`ALI_ID`, `aa_trees`.`KEEP_IDENT`, `aa_trees`.MODEL, `aa_trees`.BASE_MODEL, `aa_trees`.`MODEL_RATE_HETEROGENEITY`,`aa_trees`.`LOGL`, `aa_trees`.`BIC`,`aa_trees`.`STAT_FREQ_A`, `aa_trees`.`STAT_FREQ_C`, `aa_trees`.`STAT_FREQ_G`, `aa_trees`.`STAT_FREQ_T`,`aa_trees`.`ALPHA`, `aa_trees`.`PROP_INVAR`,`aa_trees`.`REL_RATE_CAT_1`, `aa_trees`.`PROP_CAT_1` , `aa_trees`.`REL_RATE_CAT_2` , `aa_trees`.`PROP_CAT_2`, `aa_trees`.`REL_RATE_CAT_3`, `aa_trees`.`PROP_CAT_3`,`aa_trees`.`REL_RATE_CAT_4`, `aa_trees`.`PROP_CAT_4`, `aa_trees`.`REL_RATE_CAT_5`, `aa_trees`.`PROP_CAT_5`, `aa_trees`.`REL_RATE_CAT_6`, `aa_trees`.`PROP_CAT_6`,`aa_trees`.`REL_RATE_CAT_7`, `aa_trees`.`PROP_CAT_7` , `aa_trees`.`REL_RATE_CAT_8`, `aa_trees`.`PROP_CAT_8`, `aa_trees`.`REL_RATE_CAT_9`, `aa_trees`.`PROP_CAT_9`,`aa_trees`.`REL_RATE_CAT_10` , `aa_trees`.`PROP_CAT_10` " ;
			
				}
			
			
			
		}
		
		}
		
	
		
		
		
			
			
			$f_d_query = " SELECT ".$select . " FROM ";
		
			
		
		
		
	
		
			// Activate Try here 
		try {
			
			//decide to search in Proteins or DNA 
			
			if($DNA_Prot == "dna"){
				
				
				//decide to search in modelparameters or trees
				
				if( $OPT_uOPT == "modelparameters"){
					
					//Alignment join
					$f_d_query .= "`dna_modelparameters` INNER JOIN `dna_alignments` USING (`ALI_ID`)";
					
					//Tree Join 
					$f_d_query .= " INNER JOIN `dna_trees` USING (`ALI_ID`,`TIME_STAMP` ) " ;
					
					$f_d_query .= " WHERE `dna_modelparameters`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query .= " AND `dna_modelparameters`.`KEEP_IDENT` = 0 ";
					$f_d_query .= " AND `dna_trees`.`TREE_TYPE` =  'initial' ";
					
					
					
				
					
					if($Trees_Specs_Check== "TRUE"){
						
						//Joins earlier due to nature of seach 
						//$f_d_query .= " INNER JOIN `dna_models` ON (`dna_modelparameters`.`BASE_MODEL` = `dna_models`.`MODEL_NAME` ) ";
						//$f_d_query .= " INNER JOIN `dna_trees` ON (`dna_models`.`MODEL_NAME` = `dna_trees`.`BASE_MODEL`) AND (`dna_trees`.`ALI_ID` = `dna_alignments`.`ALI_ID`) " ;
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						if(!empty($Max_tree_len)){
				
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
						
						/* Tree topology
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` = ? ';
							$f_d_parameters[] =  $mean_dis;
							
							
							
						}
						*/ 
						
					}

					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
						
						
					//  is Allignment Checked if yes catch data
					if($Alignment_Specs_Check == "TRUE"){
						
						
						//Add SourceList
						
						
						
						//Since we already inner joined in dna allignments we can collect the data 
						
						//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
							
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}
						
					}
					
						// Check matrices
						
						
						
						
						
						/* Old
					if(!empty($Matrices_D && $RHAS)){
						
						
						$f_d_conditions[] =  ' `dna_modelparameters`.`BASE_MODEL` = ? ';
						$f_d_parameters[] =  $modeld;
						
						
					}
					
					*/ //////////////////////New//////////////////////
					
					
					
					
					
					
					///////////////////////////////////////////////////
					
	
						//AIC check 
					if( !empty($AIC)){
						
						$f_d_conditions[] =  ' `dna_modelparameters`.`WEIGHTED_AIC` = ? ';
						$f_d_parameters[] =  $AIC;
						
					}
					
					
					if( !empty($AICC)){
											
						$f_d_conditions[] =  ' `dna_modelparameters`.`WEIGHTED_AICC` = ? ';
						$f_d_parameters[] =  $AICC;
						
						
					}
					
										
					
					if( !empty($BIC)){
						
						$f_d_conditions[] =  ' `dna_modelparameters`.`WEIGHTED_BIC` >= ?';
						$f_d_parameters[] =  $BIC;
						
					}
					
					
					
					
					
					
					
					
					
					// if !Modelparameters must be trees due to natiure of form valdiation radio button	
				} else {
					
					//OLD 
					/*
					$f_d_query .= "`dna_trees` INNER JOIN `dna_alignments` ON (`dna_trees`.`ALI_ID` = `dna_alignments`.`ALI_ID` )";
					$f_d_query .= " WHERE `dna_trees`.`BASE_MODEL` in " . "(" . $string_model. ")";
					*/ 
					
					//new
					$f_d_query .= "`dna_trees` INNER JOIN `dna_alignments` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `dna_trees`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query .= " AND `dna_trees`.`KEEP_IDENT` = 0 ";
					$f_d_query .= " AND `dna_trees`.`TREE_TYPE` =  'ml' ";
					
					
					//Add SourceList
						
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in dna allignments we can collect the data 
						
						
						
						//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
							
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}
						
					}
						
					
					
					if($Trees_Specs_Check== "TRUE"){
						
					
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						if(!empty($Max_tree_len)){
			
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
					}
						
						
						/* Tree topology
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `dna_trees`.`DIST_MEAN` = ? ';
							$f_d_parameters[] =  $mean_dis;
							
							
							
						}
						*/ 
						
					
					
					
					// Check matrices
					
					
					
					
		
						
					
					
				}
				
				
				
				
				
				
				
				
					
					// if !DNA must be Proteins
					
				} else {
					
					
					
					
					// Same code with Protein
					
					
					if( $OPT_uOPT == "modelparameters"){
					
					//Alignment join
					$f_d_query .= "`aa_modelparameters` INNER JOIN `aa_alignments` USING (`ALI_ID`)";
					
					//Tree Join 
					$f_d_query .= " INNER JOIN `aa_trees` USING (`ALI_ID`,`TIME_STAMP` ) " ;
					
					$f_d_query .= " WHERE `aa_modelparameters`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query .= " AND `aa_modelparameters`.`KEEP_IDENT` = 0 ";
					$f_d_query .= " AND `aa_trees`.`TREE_TYPE` =  'initial' ";
					
					
					
				
					
					if($Trees_Specs_Check== "TRUE"){
						
						//Joins earlier due to nature of seach 
						//$f_d_query .= " INNER JOIN `aa_models` ON (`aa_modelparameters`.`BASE_MODEL` = `aa_models`.`MODEL_NAME` ) ";
						//$f_d_query .= " INNER JOIN `aa_trees` ON (`aa_models`.`MODEL_NAME` = `aa_trees`.`BASE_MODEL`) AND (`aa_trees`.`ALI_ID` = `aa_alignments`.`ALI_ID`) " ;
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}

						if(!empty($Max_tree_len)){
			
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
						
						/* Tree topology
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` = ? ';
							$f_d_parameters[] =  $mean_dis;
							
							
							
						}
						*/ 
						
					}
						
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}	

					//  is Allignment Checked if yes catch data
					if($Alignment_Specs_Check == "TRUE"){
						
						
						//Add SourceList
						
						
						
						//Since we already inner joined in aa allignments we can collect the data 
						
						//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
							
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
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
						
						$f_d_conditions[] =  ' `aa_modelparameters`.`WEIGHTED_AIC` = ? ';
						$f_d_parameters[] =  $AIC;
						
					}
					
					
					if( !empty($AICC)){
											
						$f_d_conditions[] =  ' `aa_modelparameters`.`WEIGHTED_AICC` = ? ';
						$f_d_parameters[] =  $AICC;
						
						
					}
					
										
					
					if( !empty($BIC)){
						
						$f_d_conditions[] =  ' `aa_modelparameters`.`WEIGHTED_BIC` >= ?';
						$f_d_parameters[] =  $BIC;
						
					}
					
					
					
					
					
					
					
					
					
					// if !Modelparameters must be trees due to natiure of form valdiation radio button	
				} else {
					
					//OLD 
					/*
					$f_d_query .= "`aa_trees` INNER JOIN `aa_alignments` ON (`aa_trees`.`ALI_ID` = `aa_alignments`.`ALI_ID` )";
					$f_d_query .= " WHERE `aa_trees`.`BASE_MODEL` in " . "(" . $string_model. ")";
					*/ 
					
					//new
					$f_d_query .= "`aa_trees` INNER JOIN `aa_alignments` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `aa_trees`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query .= " AND `aa_trees`.`KEEP_IDENT` = 0 ";
					$f_d_query .= " AND `aa_trees`.`TREE_TYPE` =  'ml' ";
					
					
					
					//Add SourceList
						
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in aa allignments we can collect the data 
						
						
						
						//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
							
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						//Min
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` >= ? ';
							$f_d_parameters[] =  $mean_dis;
							
								
						}
						//Max
						if(!empty($Max_mean_dis)){
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` <= ? ';
							$f_d_parameters[] =  $Max_mean_dis;
							
						}
						
					}
						
					
					
					if($Trees_Specs_Check== "TRUE"){
						
					
						
						//Catch the Data 
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}

						if(!empty($Max_tree_len)){
		
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $Max_tree_len;
							
							}
							
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_DIAMETER` >= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}

						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $Max_tree_dia;
							
						}
						
					}
						
						
						/* Tree topology
						if(!empty($mean_dis)){
							
							
							$f_d_conditions[] =  ' `aa_trees`.`DIST_MEAN` = ? ';
							$f_d_parameters[] =  $mean_dis;
							
							
							
						}
						*/ 
						
					
					
					
					// Check matrices
					
					
					
					
		
						
					
					
				}
		
					
					
				}
					
				
				
				
				//Dynamic Query ///////////
				
				
				
				
		if($f_d_conditions)
		$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);	
					
		//
					
					
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

		

			
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
		

			if($OPT_uOPT == " "){
				
				//Input here 
				header('Content-Type: text/tsv; charset=utf-8');
				header('Content-Disposition: attachment; filename=modelparameters_modelfinder.tsv');
				
				
			}
				
				
		header('Content-Type: text/tsv; charset=utf-8');
		header('Content-Disposition: attachment; filename=modelparameters_modelfinder.tsv');
		
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = false; 
		$output = " ";
		//$fasta = ">";
		$date = "##".date('Y/m/d H:i:s')."\n";
		$counter = 0;
		ob_clean();
		foreach ($filter_query_result as $list) {
			 
			
			///download me 	
			if(!$headers_printed){
				echo $f_d_query;

			fwrite($output_file,"#############################\n");
			fwrite($output_file,"##         EvoNAPS         ##\n");
			fwrite($output_file,"#############################\n");
			fwrite($output_file,$date);
			fwrite($output_file,"#############################\n");
			fwrite($output_file,"## Used search parameters\n");
			
			foreach($_SESSION as $names => $values){
	  
				if(!empty($values)){
				fwrite($output_file, "## ".$names.": ".$values	);
				  //echo "<h3>".$values." "."</h3>";
				  fwrite($output_file,"\n");
			  }  
			}
			fwrite($output_file,"##");
			fwrite($output_file,"\n");
			fwrite($output_file,"##");
			fwrite($output_file,"\n");
			fputcsv($output_file,array('Alignment_ID', 'Base_Model','LOGL','BIC','weighted BIC'),"\t");
			
			$headers_printed = true;

			
			
			
			
		
			
		}
		// Write Results in Document 
		//fwrite($output_file,"\n");
		fputcsv($output_file,$list,"\t");
		fpassthru($output_file);
		$counter++;
			
			
			
			
		
		}

		fwrite($output_file,"Nr of Hits:".$counter);
			
			
			
		
		
		
		
		
				 
				

				
				////////////////////
				
				 // Check if Statements 
				 
				 //echo ($f_d_query);
			
			/*
				foreach($f_d_parameters as $f_d_list)
				{
					
					echo '<br>' . $f_d_list;
					//echo $AIC;
					//echo $AICC;
				}
				
				
				*/
				
				
				//echo $AIC;
				
				//echo $AICC;
				
				
				
				
		
		//work in progress
		
		
		
		
		
		
		//$filter_query = $connect->query('SELECT * FROM `evonaps_try`.`dna_modelparameters` AS modelpara INNER JOIN `evonaps_try`.`dna_trees` AS tree ON (modelpara.`ALI_ID` = tree.`ALI_ID`)');
		//$filter_query = $connect->query('SELECT * FROM `evonaps_try`.`dna_modelparameters` AS modelpara INNER JOIN `evonaps_try`.`dna_trees` AS tree');
		//$filter_query = $connect->prepare('SELECT * FROM `evonaps_try`.`dna_modelparameters` AS modelpara INNER JOIN `evonaps_try`.`dna_trees` AS tree WHERE modelpara.`BASE_MODEL` = :modeld');
		//$filter_query = $connect->prepare('SELECT * FROM `evonaps_try`.`dna_modelparameters` WHERE `BASE_MODEL` = :modeld');
		
		//////////////////////////////////////////works//////////////////////////////////////////////////////////////////////////////////////
		
		/*
		$filter_query = $connect->prepare("SELECT * FROM `dna_modelparameters` INNER JOIN `dna_trees` ON (`dna_modelparameters`.`ALI_ID` = `dna_trees`.`ALI_ID` ) WHERE `dna_modelparameters`.`BASE_MODEL` = :modeld OR `dna_modelparameters`.`WEIGHTED_BIC` = :BIC");
		$filter_query->execute(['modeld' => $modeld , 'BIC' => $BIC]);
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
		*/
		/////////////////////////////////////////////////////Dynmaic Query/////////////////////////////////////////////////
		//session_start();
		//$filter_query = $connect->prepare($f_d_query);
		//$filter_query->execute($f_d_parameters);
		//session_start();
		//$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		//$filter_query_result_column = $filter_query->fetchAll(PDO::FETCH_COLUMN);
		
		//$filter_query_result_column_im = implode("\t",$filter_query_result_column);
		
		
		//$filter_column_name = $filter_query_col->fetchAll(PDO::FETCH_COLUMN);
		
		
		
		
		
	
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		
		
		
		
		
		
		///////////////Download me//////////////
		
		/*
		
		if(!$headers_printed)
		{
			$output .= join(',', array_keys($row)) ."\n";
			$headers_printed = true;
		}
		
		*/
		
		//////////////Old Download me ////////////////////////////////////
		/*
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=data.txt');
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = True; 
		$output = " ";
		*/
		

		//////////////////////////////////////////////////////////////////
		
		
		////////////////////// OutPut List /////////////////////////////
		//$count = count($filter_query_result);
		
		
		 /*
		foreach ($filter_query_result as $list) {
			
			
			
			
			///download me 
			
			if(!$headers_printed){
				
			$output = implode(" ", array_keys($list));
			fputcsv($output_file, $output);
			$headers_printed = true;
		}
		
		//fputcsv($output_file,$list,"\t");
		
		
			
			
			$hits ++;
			
			////////////////////// Check List/////////// output
			
		
		echo "<br>" ;
			
		echo "<br> ". implode( "\t",$list);
		echo "<br>";
		
	
		// echo $list["ALI_ID"] . " " . $list["TIME_STAMP"] . " " . $list["MODEL_TYPE"] . " " . $list["MODEL"] . " " . $list["BASE_MODEL"] . " " . $list["BIC"];
		echo "<br>";
		
		
			//$connect = null;



		
			
			
		}
		
		*/
		
		
		/* Check L8
		echo " <br>";
		echo " <br>";
		 
		 
		echo '<a href="EvoNaps.php">Reset</a>';
		
		// echo "$modeld";
		
		echo " <br>";
		
		
		*/ 
		
	
		///////////////////////Output File Stuff/////////////////////
		//fseek($output_file, 0);
		//fpassthru($output_file);
		
		/////////////////////////////////////
		
		//$connect = null;
		//echo "Anzahl der Hits prim. : ";
		//echo $count;
		
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage();
			}
			
			
			$connect = null;
			
			
	?>