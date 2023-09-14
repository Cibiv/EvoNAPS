<?php

//include 'variables_alignment.php';
//include 'DBConnect_Alignment.php';
//include 'downloadme_alignment.php';

session_start();
include "DB_credentials.php";








		//echo "Der Wert 1 ist:".$Ali_ID;
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];

		$Hits = "checked";

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
			
		}
		
		if($Hits == "checked"){
			
			
			$f_d_query = " SELECT ".$select . " FROM ";
		} 
		
		
		try {
			
			
			if($usedna == true){
				
				
				//$f_d_query .= " `dna_sequences` WHERE `dna_sequences`.`ALI_ID` = ".$Ali_ID; 
				$f_d_query .= " `dna_sequences` INNER JOIN `dna_alignments` USING(`ALI_ID`) ";
				
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`dna_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}	
				
			}else {
				$f_d_query .= " `aa_sequences` INNER JOIN `aa_alignments` USING(`ALI_ID`) ";
				
				
				
				
				if(!empty($Ali_ID)){
					
				$f_d_conditions[] =  '`aa_sequences`.`ALI_ID` =? ';
				$f_d_parameters[] =  $Ali_ID;
				
				}	
			}
				
			
			
			
			
				if($f_d_conditions)
				$f_d_query .= " WHERE ".implode(" AND ", $f_d_conditions);
			
			//echo ($f_d_query);
			
			
						
			}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
			
			
			
			
			
			
		
			
			
			
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

			
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
			
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=alignment.fasta');
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = true; 
		$output = " ";
		//$fasta = ">";
		
		foreach ($filter_query_result as $list) {
			 
			
			///download me 	
			if(!$headers_printed){
			
			//Fill in Headers here 
			fputcsv($output_file,array(''));
			$headers_printed = true;
			}
			
		//fwrite($output_file,"\n");
		fwrite($output_file,">");
		
		
		// Write Results in Document 
		fputcsv($output_file,$list,"\n");
		//fwrite($output_file,"\n");
		fpassthru($output_file);
			
			
			
		
		}
		
		
		$connect = null;
			
			
			
			
			
			
			




						

//echo  "Der Wert ist :".$Ali_ID;

?>