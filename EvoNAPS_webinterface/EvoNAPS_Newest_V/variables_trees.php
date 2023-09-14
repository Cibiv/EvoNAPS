<?php
session_start();

error_reporting(0);

// Set Variables for the Filter 
		
		$Alignment_Specs_Check = $_POST['alignment_features'];
		$_SESSION['alignment_features']= $Alignment_Specs_Check; 
		
		
		$Trees_Specs_Check = $_POST['tree_features'];
		$_SESSION['tree_features']= $Trees_Specs_Check; 
		
		$DNA_Prot = $_POST['datatype'];
		$_SESSION['datatype']= $DNA_Prot; 
		
		
		$Nr_Seq = $_POST['number_of_sequences'];
		$_SESSION['number_of_sequences']= $Nr_Seq; 
		
		$Max_Nr_Seq = $_POST['max_number_of_sequences'];
		$_SESSION['max_number_of_sequences']= $Max_Nr_Seq; 
		
		$Nr_sites = $_POST['number_of_sites'];
		$_SESSION['number_of_sites']= $Nr_sites; 
		
		$Max_Nr_sites = $_POST['max_number_of_sites'];
		$_SESSION['max_number_of_sites']= $Max_Nr_sites; 
		
		
		
	
		$BL_mean_min = $_POST['min_mean_branch_length'];
		$_SESSION['min_mean_branch_length']= $BL_mean_min;
		
		$BL_mean_max = $_POST['max_mean_branch_length'];
		$_SESSION['max_mean_branch_length']= $BL_mean_max;
		
		$BL_min = $_POST['min_branch_length'];
		$_SESSION['min_branch_length']= $BL_min;
		
		$BL_max = $_POST['max_branch_length'];
		$_SESSION['max_branch_length']= $BL_max;
	
		$IBL_mean_min = $_POST['min_mean_internal_branch_length'];
		$_SESSION['min_mean_internal_branch_length']= $IBL_mean_min;
		
		$IBL_mean_max = $_POST['max_mean_internal_branch_length'];
		$_SESSION['max_mean_internal_branch_length']= $IBL_mean_max;
		
		$IBL_min  = $_POST['min_internal_branch_length'];
		$_SESSION['min_internal_branch_length']= $IBL_min;
		
		$IBL_max =  $_POST['max_internal_branch_length'];
		$_SESSION['max_internal_branch_length']= $IBL_max;
		
		
		
		$EBL_mean_min = $_POST['min_mean_external_branch_length'];
		$_SESSION['min_mean_external_branch_length']= $EBL_mean_min;
		
		$EBL_mean_max = $_POST['max_mean_external_branch_length'];
		$_SESSION['max_mean_external_branch_length']= $EBL_mean_max;
		
		$EBL_min = $_POST['min_external_branch_length'];
		$_SESSION['min_external_branch_length']= $EBL_min;
		
		$EBL_max = $_POST['max_external_branch_length'];
		$_SESSION['max_external_branch_length']= $EBL_max;
		
		
		$tree_len = $_POST['tree_length'];
		$_SESSION['tree_length']= $tree_len;
		
		$Max_tree_len = $_POST['max_tree_length'];
		$_SESSION['max_tree_length']= $Max_tree_len;
		
		$tree_dia = $_POST['tree_diameter'];
		$_SESSION['tree_diameter']= $tree_dia;
		
		$Max_tree_dia = $_POST['max_tree_diameter'];
		$_SESSION['max_tree_diameter']= $Max_tree_dia;
		
		
		
		



?>