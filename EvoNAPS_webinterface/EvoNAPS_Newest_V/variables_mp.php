<?php

session_start();
//error_reporting(0);

		$DNA_Prot = $_POST['datatype'];
		$_SESSION['datatype']= $DNA_Prot; 
		
		
		$Matrices_D = $_POST['DNA_model'];
		$_SESSION['DNA_model']= $Matrices_D; 
		
		
		$Matrices_P = $_POST['Protein_model'];
		$_SESSION['Protein_model']= $Matrices_P; 
		
		
		//$modelp = $Matrices_P." ".$RHAS;
		//$modeld = $Matrices_D." ".$RHAS;
		
		$OPT_uOPT = $_POST['query_type'];
		$_SESSION['query_type']= $OPT_uOPT; 
		
		$AIC = $_POST['AIC'];
		$_SESSION['AIC']= $AIC; 
		
		$AICC = $_POST['AICC'];
		$_SESSION['AICC']= $AICC; 
		
		$BIC = $_POST['BIC'];
		$_SESSION['BIC']= $BIC; 
		
		$Nr_Seq = $_POST['number_of_sequences'];
		$_SESSION['number_of_sequences']= $Nr_Seq; 
		
		$Max_Nr_Seq = $_POST['max_number_of_sequences'];
		$_SESSION['max_number_of_sequences']= $Max_Nr_Seq; 
		
		
		$Nr_sites = $_POST['number_of_sites'];
		$_SESSION['number_of_sites']= $Nr_sites; 
		
		$Max_Nr_sites = $_POST['max_number_of_sites'];
		$_SESSION['max_number_of_sites']= $Max_Nr_sites; 
		
		
		$mean_dis = $_POST['mean_distance'];
		$_SESSION['mean_distance']= $mean_dis; 
		
		$Max_mean_dis = $_POST['max_mean_distance'];
		$_SESSION['max_mean_distance']= $Max_mean_dis; 
		
		
		$tree_len = $_POST['tree_length'];
		$_SESSION['tree_length']= $tree_len; 
		
		//$tree_top = $_POST['tree_top'];
		$tree_dia = $_POST['tree_diameter'];
		$_SESSION['tree_diameter']= $tree_dia; 
		
		
		$Alignment_Specs_Check = $_POST['alignment_features'];
		$_SESSION['alignment_features']= $Alignment_Specs_Check; 
		
		
		if(isset($_POST['tree_features'])){
			
		$Trees_Specs_Check = $_POST['tree_features'];
		$_SESSION['tree_features']= $Trees_Specs_Check; 
		
		}
		
		
		
		$Hits = $_POST['Hits_anzeigen'];
		$_SESSION['Hits_anzeigen']= $Hits; 
		
		print(implode(" , ", $_POST));
		
		

		
		
		$NewWick = $_POST['Newick'];
		$_SESSION['Newick']= $NewWick; 
		//Variables for the Hit
		
		 
		
		
		
		
		
		// Dynamic Querys Parameters
		
		
		
		
		
		/// Model Combination RHAS + BASE model
		
		$model = [];
		
		$E = $_POST['RHAS_uniform'];
		$_SESSION['RHAS_uniform']= $E; 
		
		$I = $_POST['RHAS_I'];
		$_SESSION['RHAS_I']= $I;
		
		$G4 = $_POST['RHAS_G4'];
		$_SESSION['RHAS_G4']= $G4;
		
		$IG4 = $_POST['RHAS_IG4'];
		$_SESSION['RHAS_IG4']= $IG4;
		
		$R2 = $_POST['RHAS_R2'];
		$_SESSION['RHAS_R2']= $R2;
		
		$R3 =$_POST['RHAS_R3'];
		$_SESSION['RHAS_R3']= $R3;
		
		$R4 =$_POST['RHAS_R4'];
		$_SESSION['RHAS_R4']= $R4;
		
		$R5 =$_POST['RHAS_R5'];
		$_SESSION['RHAS_R5']= $R5;
		
		$R6 =$_POST['RHAS_R6'];
		$_SESSION['RHAS_R6']= $R6;
		
		$R7	=$_POST['RHAS_R7'];
		$_SESSION['RHAS_R7']= $R7;
		
		$R8 =$_POST['RHAS_R8'];
		$_SESSION['RHAS_R8']= $R8;
		
		$R9 =$_POST['RHAS_R9'];
		$_SESSION['RHAS_R9']= $R9;
		
		$R10 =$_POST['RHAS_R10'];
		$_SESSION['RHAS_R10']= $R10;
		
		$F = $_POST['+F'];
		$_SESSION['+F']= $F;
//////////////////Setting Variables////////////777
		$Source = [];
		
		
		
		$Pan = $_POST['PANDIT'];
		$_SESSION['PANDIT']= $Pan;
		
		$Ortho =$_POST['OrthoMaM'];
		$_SESSION['OrthoMaM']= $Ortho;
		
		$Lanf =$_POST['Lanfear'];
		$_SESSION['Lanfear']= $Lanf;
		
		$ALL = $_POST['selectAll'];
		$_SESSION['selectAll']= $ALL;
		
		?>