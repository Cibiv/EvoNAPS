<?php
session_start();
		
		$DNA_Prot = $_POST['datatype'];
		$_SESSION['datatype']= $DNA_Prot; 
		
	
		
		$Ali_ID = $_POST['Alignment_ID'];
		$_SESSION['Alignment_ID']= $Ali_ID;
		
	
	
	
	
		//Do elsewhere
		
		/*
		$Fr_WL_Gaps = $_POST['Fr_WL_Gaps'];
		$Fr_Dis_Pat = $_POST['Fr_Dis_Pat'];
		$Fr_Pars = $_POST['Fr_Pars'];
		*/
		
		//$Hits = $_POST['Hits_anzeigen'];






?>