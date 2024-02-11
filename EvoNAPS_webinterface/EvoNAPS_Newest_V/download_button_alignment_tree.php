<?php

//start session here in order to catch variables
session_start();

// include credentials data here 
include "DB_credentials.php";

		//Initalize variables 
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];

        ?>