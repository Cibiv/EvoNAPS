<?php


		
		//Include files and set memory limit
		
		ini_set('memory_limit','1000M');
		include('variables_alignment.php');
		include('DB_credentials.php');
		
		//initalize query parameters
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
				
				//alignment joins
				$f_d_query .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				$f_d_query_1 .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`dna_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}
				
				
				
				
								
			
					
				
				
				//Proteins only do if dna is done and finished 
			}else{
				
				if($useprot == true){
				
				
				
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
				//Fuze conditions in 1 string for preview and limit it
				$f_d_query_1 .= " WHERE ".implode(" AND ", $f_d_conditions)." LIMIT 20";
			
		}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
			
		?>