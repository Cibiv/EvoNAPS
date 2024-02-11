<?php

//start session here in order to catch variables
session_start();

// include credentials data here 
include "DB_credentials.php";

		//Initalize variables 
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];
        $usedna = false;
      
        $select = " SELECT `a`.SEQ_NAME,`a`.TAX_ID, `b`.TAX_NAME FROM dna_sequences AS `a` INNER JOIN taxonomy AS `b`
        USING (TAX_ID) WHERE `a`.ALI_ID=".'"'.$Ali_ID.'"';

        $query = $connect->query($select);
        $result_query = $query->fetchAll(PDO::FETCH_ASSOC);

        /// set headers for donwnloading the data	
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=taxon_ids.tsv');

    //created file
    $output_file = fopen("php://output", "w"); 
            
    $headers_printed = false; 
    $output = " ";

    // loop through the fetched data
		foreach ($result_query as $list) {
			 
			
			//check for headders 	
			if(!$headers_printed){
			
			//Fill in Headers here 
			fputcsv($output_file,array('Seq_Name','Taxon_ID','Taxon_Name'),"\t");
			$headers_printed = true;
			}
			
		// Write Results in Document 
		fputcsv($output_file,$list,"\t");
		
		fpassthru($output_file);
		
		}
		
		//close connection
		$connect = null;