<?php

//start session here in order to catch variables
session_start();

// include credentials data here 
include "DB_credentials.php";

		//Initalize variables 
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];
		$Hits = "checked"; // old version can be deleted
		$f_d_conditions = [];
		$f_d_parameters = [];
		$usedna = false;
		
		
		// Start string building
		if($DNA_Prot == "dna"){
			
			$usedna = true;
			
			// DNA SQL select statements 
			$select = "`dna_sequences` .`SEQ_NAME`,`dna_sequences` .`SEQ`";

		} else {
			
			//Aa  SQL select statements
			$select = "`aa_sequences` .`SEQ_NAME`,`aa_sequences` .`SEQ`";
		}
			
			$f_d_query = " SELECT ".$select . " FROM ";
		
		try {
			
			if($usedna == true){
				
				// joins for dna alignments
				$f_d_query .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				
				// check data for string building
				if(!empty($Ali_ID)){
					
				//add data to the string building arrays	
				$f_d_conditions[] =  '`dna_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}	
				
			}else {
				// joins for aa alignemtns
				$f_d_query .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				
				// check data for string building
				if(!empty($Ali_ID)){
				
				//add data to the string building arrays
				$f_d_conditions[] =  '`aa_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}	
			}
			// check if sth got added to conditions if yes, add the SQL statemens together in the condition array anf form the string
				if($f_d_conditions)
				
				// implode function to convert array in string, delimter with AND and add the "Where" clause to the string
				$f_d_query .= " WHERE ".implode(" AND ", $f_d_conditions);
			
			// eception handling with error message
			}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
				
		// Bind the values of f_d_parameters array to the the SQL statements from the conditions array 	
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

		//fetch data	
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
		/// set headers for donwnloading the data	
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=alignment.fasta');

		//created file
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = true; 
		$output = " ";
	
		// loop through the fetched data
		foreach ($filter_query_result as $list) {
			 
			
			//check for headders 	
			if(!$headers_printed){
			
			//Fill in Headers here 
			fputcsv($output_file,array(''));
			$headers_printed = true;
			}
			
		// Write Results in Document 
		fwrite($output_file,">");
		fputcsv($output_file,$list,"\n");
		
		fpassthru($output_file);
		
		}
		
		//close connection
		$connect = null;
			

?>