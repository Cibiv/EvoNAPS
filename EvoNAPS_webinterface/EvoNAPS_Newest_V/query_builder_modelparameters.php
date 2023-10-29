<?php


		//Connect to DB
		include "variables_modelparameters.php";
	//	include "DB_credentials.php";
		
		ini_set('memory_limit','1000M');
	
	




		

		
		// Set Variables for the Filter 
		
		/*
		$DNA_Prot = $_POST['DNA_Prot'];
		
		
		$Matrices_D = $_POST['Matrices_D'];
		$Matrices_P = $_POST['Matrices_P'];
		
		
		//$modelp = $Matrices_P." ".$RHAS;
		//$modeld = $Matrices_D." ".$RHAS;
		
		$OPT_uOPT = $_POST['OPT_uOPT'];
		
		$AIC = $_POST['AIC'];
		$AICC = $_POST['AICC'];
		$BIC = $_POST['BIC'];
		
		$Nr_Seq = $_POST['Nr_Seq'];
		$Max_Nr_Seq = $_POST['Max_Nr_Seq'];
		
		$Nr_sites = $_POST['Nr_sites'];
		$Max_Nr_sites = $_POST['Max_Nr_sites'];
		
		$mean_dis = $_POST['mean_dis'];
		$Max_mean_dis = $_POST['Max_mean_dis'];
		
		$tree_len = $_POST['tree_len'];
		//$tree_top = $_POST['tree_top'];
		$tree_dia = $_POST['tree_dia'];
		
		$Alignment_Specs_Check = $_POST['Alignment_Specs_Check'];
		$Trees_Specs_Check = $_POST['Trees_Specs_Check'];
		
		$Hits = $_POST['Hits_anzeigen'];
		
		$Initial_Tree = "initial";
		$ML_Tree = "ml";
		
		
		$NewWick = $_POST['NewWick'];
		//Variables for the Hit
		
		 
		
		
		
		
		
		// Dynamic Querys Parameters
		
		
		$f_d_conditions = [];
		$f_d_parameters = [];
		
		
		
		/// Model Combination RHAS + BASE model
		
		$model = [];
		$E = $_POST['RHAS_MAT_E'];
		$I = $_POST['RHAS_MAT_I'];
		$G4 = $_POST['RHAS_MAT_G4'];
		$IG4 = $_POST['RHAS_MAT_IG4'];
		$R2 = $_POST['RHAS_MAT_R2'];
		$R3 =$_POST['RHAS_MAT_R3'];
		$R4 =$_POST['RHAS_MAT_R4'];
		$R5 =$_POST['RHAS_MAT_R5'];
		$R6 =$_POST['RHAS_MAT_R6'];
		$R7	=$_POST['RHAS_MAT_R7'];
		$R8 =$_POST['RHAS_MAT_R8'];
		$R9 =$_POST['RHAS_MAT_R9'];
		$R10 =$_POST['RHAS_MAT_R10'];
		
//////////////////Setting Variables////////////777
		$Source = [];
		$Pan = $_POST['PANDIT'];
		$Ortho =$_POST['OrthoMaM'];
		$Lanf =$_POST['Lanfear'];
		$ALL = $_POST['selectAll'];

*/


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
	
	//echo $stringsource;
		
		





		
		
		
		
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
				
				
				if($NewWick == "TRUE"){
			
				$select = "`dna_alignments` .`ALI_ID`, `dna_modelparameters`.`KEEP_IDENT`, `dna_modelparameters`.MODEL,`dna_modelparameters`.`MODEL_RATE_HETEROGENEITY`,`dna_modelparameters`.`LOGL`, `dna_modelparameters`.`BIC`, `dna_modelparameters`.`WEIGHTED_BIC`,`dna_modelparameters`.`STAT_FREQ_A`, `dna_modelparameters`.`STAT_FREQ_C`, `dna_modelparameters`.`STAT_FREQ_G`, `dna_modelparameters`.`STAT_FREQ_T`,`dna_modelparameters`.`RATE_AC`, `dna_modelparameters`.`RATE_AG`, `dna_modelparameters`.`RATE_AT`, `dna_modelparameters`.`RATE_CG`, `dna_modelparameters`.`RATE_CT`, `dna_modelparameters`.`RATE_GT`, `dna_modelparameters`.`ALPHA`, `dna_modelparameters`.`PROP_INVAR`,`dna_modelparameters`.`REL_RATE_CAT_1`, `dna_modelparameters`.`PROP_CAT_1` , `dna_modelparameters`.`REL_RATE_CAT_2` , `dna_modelparameters`.`PROP_CAT_2`, `dna_modelparameters`.`REL_RATE_CAT_3`, `dna_modelparameters`.`PROP_CAT_3`,`dna_modelparameters`.`REL_RATE_CAT_4`, `dna_modelparameters`.`PROP_CAT_4`, `dna_modelparameters`.`REL_RATE_CAT_5`, `dna_modelparameters`.`PROP_CAT_5`, `dna_modelparameters`.`REL_RATE_CAT_6`, `dna_modelparameters`.`PROP_CAT_6`,`dna_modelparameters`.`REL_RATE_CAT_7`, `dna_modelparameters`.`PROP_CAT_7` , `dna_modelparameters`.`REL_RATE_CAT_8`, `dna_modelparameters`.`PROP_CAT_8`, `dna_modelparameters`.`REL_RATE_CAT_9`, `dna_modelparameters`.`PROP_CAT_9`,`dna_modelparameters`.`REL_RATE_CAT_10` , `dna_modelparameters`.`PROP_CAT_10`, `dna_trees`.`NEWICK_STRING`" ;
			
				} else {
					
				$select = "`dna_alignments` .`ALI_ID`, `dna_modelparameters`.`KEEP_IDENT`, `dna_modelparameters`.MODEL, `dna_modelparameters`.`MODEL_RATE_HETEROGENEITY`,CAST(`dna_modelparameters`.`LOGL` AS DECIMAL(7,2)),CAST( `dna_modelparameters`.`BIC` AS DECIMAL(7,2)), `dna_modelparameters`.`WEIGHTED_BIC`,CAST(`dna_modelparameters`.`STAT_FREQ_A`AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`STAT_FREQ_C`AS DECIMAL(7,4)), CAST(`dna_modelparameters`.`STAT_FREQ_G`AS DECIMAL(7,4)), CAST( `dna_modelparameters`.`STAT_FREQ_T`AS DECIMAL(7,4)),CAST(`dna_modelparameters`.`RATE_AC` AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`RATE_AG`AS DECIMAL(7,4)),CAST( `dna_modelparameters`.`RATE_AT`AS DECIMAL(7,4)), `dna_modelparameters`.`RATE_CG`, `dna_modelparameters`.`RATE_CT`, `dna_modelparameters`.`RATE_GT`, `dna_modelparameters`.`ALPHA`, `dna_modelparameters`.`PROP_INVAR`,`dna_modelparameters`.`REL_RATE_CAT_1`, `dna_modelparameters`.`PROP_CAT_1` , `dna_modelparameters`.`REL_RATE_CAT_2` , `dna_modelparameters`.`PROP_CAT_2`, `dna_modelparameters`.`REL_RATE_CAT_3`, `dna_modelparameters`.`PROP_CAT_3`,`dna_modelparameters`.`REL_RATE_CAT_4`, `dna_modelparameters`.`PROP_CAT_4`, `dna_modelparameters`.`REL_RATE_CAT_5`, `dna_modelparameters`.`PROP_CAT_5`, `dna_modelparameters`.`REL_RATE_CAT_6`, `dna_modelparameters`.`PROP_CAT_6`,`dna_modelparameters`.`REL_RATE_CAT_7`, `dna_modelparameters`.`PROP_CAT_7` , `dna_modelparameters`.`REL_RATE_CAT_8`, `dna_modelparameters`.`PROP_CAT_8`, `dna_modelparameters`.`REL_RATE_CAT_9`, `dna_modelparameters`.`PROP_CAT_9`,`dna_modelparameters`.`REL_RATE_CAT_10` , `dna_modelparameters`.`PROP_CAT_10` " ;
			
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
		
	
		
	
			
			
			$f_d_query_1 = " SELECT ".$select . " FROM ";
	
				
				$f_d_query = " SELECT count(*) FROM ";
			
		
		
		
	
		
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
					
					
					//Alignment join
					$f_d_query_1 .= "`dna_modelparameters` INNER JOIN `dna_alignments` USING (`ALI_ID`)";	
					//Tree Join 
					$f_d_query_1 .= " INNER JOIN `dna_trees` USING (`ALI_ID`,`TIME_STAMP` ) " ;
					$f_d_query_1 .= " WHERE `dna_modelparameters`.`MODEL` in " . "(" . $string_model. ")";
					$f_d_query_1 .= " AND `dna_modelparameters`.`KEEP_IDENT` = 0 ";
					$f_d_query_1 .= " AND `dna_trees`.`TREE_TYPE` =  'initial' ";
					
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
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
						//max
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
					
					// Insert ML or initial data catch w if here 
					
					
					
					
					
					
					
					
					// if !Modelparameters must be trees due to natiure of form valdiation radio button	
				} else {
					
					//OLD 
					/*
					$f_d_query .= "`dna_trees` INNER JOIN `dna_alignments` ON (`dna_trees`.`ALI_ID` = `dna_alignments`.`ALI_ID` )";
					$f_d_query .= " WHERE `dna_trees`.`BASE_MODEL` in " . "(" . $string_model. ")";
					*/ 
					
					//Count
					$f_d_query .= "`dna_trees` INNER JOIN `dna_alignments` USING (`ALI_ID`)";
					
					
					$f_d_query .= " WHERE `dna_trees`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query .= " AND `dna_trees`.`KEEP_IDENT` = 0 ";
					$f_d_query .= " AND `dna_trees`.`TREE_TYPE` =  'ml' ";
					
					
					//Preview
					$f_d_query_1 .= "`dna_trees` INNER JOIN `dna_alignments` USING (`ALI_ID`)";
					
					
					$f_d_query_1 .= " WHERE `dna_trees`.`MODEL` in " . "(" . $string_model. ")";
					
					$f_d_query_1 .= " AND `dna_trees`.`KEEP_IDENT` = 0 ";
					$f_d_query_1 .= " AND `dna_trees`.`TREE_TYPE` =  'ml' ";
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `dna_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in dna allignments we can collect the data 
						
						//Add SourceList
						
						
						
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
						//max
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
					/*
					if(!empty($Matrices_D && $RHAS)){
						
						
						//$f_d_conditions[] =  ' `dna_trees`.`BASE_MODEL` = ? ';
						//$f_d_parameters[] =  $modeld;
						
						
					}
					*/
					/*
					if ($ML_Tree == "TRUE"){
						
						$f_d_conditions[] =  ' `dna_trees`.`TREE_TYPE` = ?';
						$f_d_parameters[] =  $ML_Tree;
						
					}
					
					*/
					
		
						
					
					
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
					//Preview
					$f_d_query_1 .= "`aa_modelparameters` INNER JOIN `aa_alignments` USING (`ALI_ID`)";
					
					//Tree Join 
					$f_d_query_1 .= " INNER JOIN `aa_trees` USING (`ALI_ID`,`TIME_STAMP` ) " ;
					
					$f_d_query_1 .= " WHERE `aa_modelparameters`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query_1 .= " AND `aa_modelparameters`.`KEEP_IDENT` = 0 ";
					$f_d_query_1 .= " AND `aa_trees`.`TREE_TYPE` =  'initial' ";
					
					
					
				
					
					if($Trees_Specs_Check== "TRUE"){
						
						//Joins earlier due to nature of seach 
						
						
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
						//max
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

					//Add SourceList
						
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						$f_d_query_1 .= "AND  `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";
						

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							$f_d_query_1 .= "AND `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
						
						
					//  is Allignment Checked if yes catch data
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
					
					// Insert ML or initial data catch w if here 
					
					
					
					
					
					
					
					
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
					
					
					//Preview
					$f_d_query_1 .= "`aa_trees` INNER JOIN `aa_alignments` USING (`ALI_ID`)";
					
					
					$f_d_query_1 .= " WHERE `aa_trees`.`MODEL` in " . "(" . $string_model_p. ")";
					
					$f_d_query_1 .= " AND `aa_trees`.`KEEP_IDENT` = 0 ";
					$f_d_query_1 .= " AND `aa_trees`.`TREE_TYPE` =  'ml' ";
					
					
					if ($ALL == "checked"){
							
						$f_d_query .= "AND  `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringall. ")";

						}elseif(!empty($Source)){
							
							$f_d_query .= "AND `aa_alignments`.`FROM_DATABASE` in " . "(" . $stringsource. ")";
							
						}
					
					
					if($Alignment_Specs_Check == "TRUE"){	
						
						//Since we already inner joined in aa allignments we can collect the data 
						
						//Add SourceList
						
						
						
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
						//max
						if(!empty($Max_tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` <= ? ';
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
				
				//echo $f_d_query_1;
				//echo "<br>";
					//echo $f_d_query;
				
				 
				

				
				////////////////////
				
				 // Check if Statements 
				 
				 echo ($f_d_query_1);
			
			
		
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage();
			}


?>

