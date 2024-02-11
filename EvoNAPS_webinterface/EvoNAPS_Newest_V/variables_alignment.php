<?php
session_start();
		if(isset($_GET['datatype'])){
		$DNA_Prot = $_GET['datatype'];
		$_SESSION['datatype']= $DNA_Prot; 
		}
	
		if(isset($_GET['Alignment_ID'])){
		$Ali_ID = $_GET['Alignment_ID'];
		$_SESSION['Alignment_ID']= $Ali_ID;
		}
	
	
	
	
		






?>