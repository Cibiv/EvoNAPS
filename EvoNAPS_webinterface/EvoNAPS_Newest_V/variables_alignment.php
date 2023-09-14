<?php
session_start();
		if(isset($_POST['Alignment_ID'])){
		$DNA_Prot = $_POST['datatype'];
		$_SESSION['datatype']= $DNA_Prot; 
		}
	
		if(isset($_POST['Alignment_ID'])){
		$Ali_ID = $_POST['Alignment_ID'];
		$_SESSION['Alignment_ID']= $Ali_ID;
		}
	
	
	
	
		






?>