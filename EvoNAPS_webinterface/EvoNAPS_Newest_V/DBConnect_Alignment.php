<?php


		
		//Connect to DB
		
		ini_set('memory_limit','1000M');
		include('variables_alignment.php');
		include('DB_credentials.php');
		//Old
		//error_reporting(0);
		/*
		
		$hostname = 'localhost';
		$dbname= 'evonaps_try_2';
		$username='root';
		$password='';
		$dsn= "mysql:host=$hostname;dbname=$dbname";
		*/
		
		
		
		
		// Set Variables for the Filter 
		/*
		session_start();
		
		$DNA_Prot = $_POST['DNA_Prot'];
		$OPT_uOPT = $_POST['OPT_uOPT'];
		
		$Ali_ID = $_POST['Ali_ID'];
		$_SESSION['ALID']= $Ali_ID;
	
		
		$Fr_WL_Gaps = $_POST['Fr_WL_Gaps'];
		$Fr_Dis_Pat = $_POST['Fr_Dis_Pat'];
		$Fr_Pars = $_POST['Fr_Pars'];
		
		
		$Hits = $_POST['Hits_anzeigen'];
		*/
		
		
		

		//Variables for the Hit
		
		 
		
		
		
		
		
		// Dynamic Querys Parameters
		
		
		$f_d_conditions = [];
		$f_d_parameters = [];
		$usedna = false;
		
		
		if($DNA_Prot == "dna"){
			
			$usedna = true;
			
			// DNA select
			$select = "`dna_sequences` .`SEQ_NAME`,`dna_sequences` .`SEQ`";
			
			
			
		} else {
			
			//Aa select
			$select = "`aa_sequences` .`SEQ_NAME`,`aa_sequences` .`SEQ`";
			$useprot = true;
		}
		
			
			
			$f_d_query_1 = " SELECT ".$select . " FROM ";
		
				
				$f_d_query = " SELECT count(*) FROM ";
			
		
		
		
		try {
			
			
			if($usedna == true){
				
				
				//$f_d_query .= " `dna_sequences` WHERE `dna_sequences`.`ALI_ID` = ".$Ali_ID; 
				$f_d_query .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				$f_d_query_1 .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`dna_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}
				
				
				
				
								
			
					
				
				
				//Proteins only do if dna is done and finished 
			}else{
				
				if($useprot == true){
				
				
				//$f_d_query .= " `dna_sequences` WHERE `dna_sequences`.`ALI_ID` = ".$Ali_ID; 
				$f_d_query .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				$f_d_query_1 .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				}
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`aa_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}
				
			}
			
			//Fuze conditions in 1 string
			if($f_d_conditions)
				$f_d_query .= " WHERE ".implode(" AND ", $f_d_conditions);
				$f_d_query_1 .= " WHERE ".implode(" AND ", $f_d_conditions)." LIMIT 20";
			
			
			//Echo string for the query
		//	echo ($f_d_query_1);
			
			
			
			
			
			
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
			
				
				
				
				
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		?>