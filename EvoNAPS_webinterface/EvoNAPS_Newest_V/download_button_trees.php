<?php


session_start();
include "DB_credentials.php";
			
		$DNA_Prot = $_SESSION['datatype'];
		
	
		
		$Nr_Seq = $_SESSION['number_of_sequences'];
		$Max_Nr_Seq = $_SESSION['max_number_of_sequences'];
		
		$Nr_sites = $_SESSION['number_of_sites'];
		$Max_Nr_sites = $_SESSION['max_number_of_sites'];
		
		
		
	
		$BL_mean_min = $_SESSION['min_mean_branch_length'];
		$BL_mean_max = $_SESSION['max_mean_branch_length'];
		$BL_min = $_SESSION['min_branch_length'];
		$BL_max = $_SESSION['max_branch_length'];
		
	
		$IBL_mean_min =  $_SESSION['min_mean_internal_branch_length'];
		$IBL_mean_max =  $_SESSION['max_mean_internal_branch_length'];
		$IBL_min  = $_SESSION['min_internal_branch_length'];
		$IBL_max =  $_SESSION['max_internal_branch_length'];
		
		
		$EBL_mean_min = $_SESSION['min_mean_external_branch_length'];
		$EBL_mean_max = $_SESSION['max_mean_external_branch_length'];
		$EBL_min = $_SESSION['min_external_branch_length'];
		$EBL_max = $_SESSION['max_external_branch_length'];
		
		$tree_len = $_SESSION['tree_length'];
		$Max_tree_len = $_SESSION['max_tree_length'];
		$tree_dia = $_SESSION['tree_diameter'];
		$Max_tree_dia = $_SESSION['max_tree_diameter'];
		

		
		//$Hits = $_SESSION['Hits_anzeigen'];
		
		

		$Hits = "checked";

		$f_d_conditions = [];
		$f_d_parameters = [];
		$usedna = false;

		
		if($DNA_Prot == "dna"){
			
			// DNA select
			$select = "`dna_alignments` .`ALI_ID`, `dna_trees`.`NEWICK_STRING`, `dna_trees`.`PROP_INVAR`,`dna_trees`.`ALPHA`,`dna_trees`.`STAT_FREQ_TYPE`, `dna_trees`.`STAT_FREQ_A`,`dna_trees`.`STAT_FREQ_C`, `dna_trees`.`STAT_FREQ_G`,`dna_trees`.`STAT_FREQ_T`,`dna_trees`.`RATE_AC`,`dna_trees`.`RATE_AG`,`dna_trees`.`RATE_AT`,`dna_trees`.`RATE_CA`,`dna_trees`.`RATE_CG`,`dna_trees`.`RATE_CT`,`dna_trees`.`RATE_GA`,`dna_trees`.`RATE_GC`,`dna_trees`.`RATE_GT`, `dna_trees`.`RATE_TA`";
			$usedna = true;
			
			
		} else {
			
			//Aa select
			$select = "`aa_alignments` .`ALI_ID`, `aa_trees`.`NEWICK_STRING`, `aa_trees`.`PROP_INVAR`,`aa_trees`.`ALPHA`,`aa_trees`.`STAT_FREQ_TYPE`, `aa_trees`.`STAT_FREQ_A`,`aa_trees`.`STAT_FREQ_R`, `aa_trees`.`STAT_FREQ_N`,`aa_trees`.`STAT_FREQ_D`";
			$useprot = true;
		}
		
		
		if($Hits == "checked"){
			
			
			$f_d_query = " SELECT ".$select . " FROM ";
		
		
		
		try {
			
		
				
	
			
			if($usedna == true){
								
			$f_d_query .= " `dna_trees` INNER JOIN `dna_alignments` USING (`ALI_ID`) "; 
			$f_d_query .= " WHERE `dna_trees`.`TREE_TYPE` =  'ml' ";
			$f_d_query .= " AND `dna_trees`.`KEEP_IDENT` = 0 ";
			
					//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
						
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `dna_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						
						//min
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						//min	
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//Branch length
						
						//min
						if(!empty($BL_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`BL_MIN` >= ? ';
							$f_d_parameters[] =  $BL_min;
							
							}
						//max	
						if(!empty($BL_max)){
							
							$f_d_conditions[] =  ' `dna_trees`.`BL_MAX` <= ? ';
							$f_d_parameters[] =  $BL_max;
							
						}
						//mean (min)
						if(!empty($BL_mean_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $BL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($BL_mean_max)){
							
							$f_d_conditions[] =  ' `dna_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $BL_mean_max;
							
							}
							
							//Internal Branch
							
							
							//min
						if(!empty($IBL_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`IBL_MIN` >= ? ';
							$f_d_parameters[] =  $IBL_min;
							
							}
						//max	
						if(!empty($IBL_max)){
							
							$f_d_conditions[] =  ' `dna_trees`.`IBL_MAX` <= ? ';
							$f_d_parameters[] =  $IBL_max;
							
						}
						//mean (min)
						if(!empty($IBL_mean_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`IBL_MEAN` >= ? ';
							$f_d_parameters[] =  $IBL_mean_min;
							
							}
							
						//mean (max)
						if(!empty($IBL_mean_max)){
							
							$f_d_conditions[] =  ' `dna_trees`.`IBL_MEAN` <= ? ';
							$f_d_parameters[] =  $IBL_mean_max;
							
							}
							
							
							
							
							
						//External Branch
						
						//min
						if(!empty($EBL_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`EBL_MIN` >= ? ';
							$f_d_parameters[] =  $EBL_min;
							
							}
						//max	
						if(!empty($EBL_max)){
							
							$f_d_conditions[] =  ' `dna_trees`.`EBL_MAX` <= ? ';
							$f_d_parameters[] =  $EBL_max;
							
						}
						//mean (min)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`EBL_MEAN` >= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `dna_trees`.`EBL_MEAN` <= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
						
				
				
				//Proteins Trees
			}else {
				
								
			$f_d_query .= " `aa_trees` INNER JOIN `aa_alignments` USING (`ALI_ID`) "; 
			$f_d_query .= " WHERE `aa_trees`.`TREE_TYPE` =  'ml' ";
			$f_d_query .= " AND `aa_trees`.`KEEP_IDENT` = 0 ";
			
					//Min
						if(!empty($Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` >= ? ';
							$f_d_parameters[] =  $Nr_Seq;
						
						}
						
						//Max
						if(!empty($Max_Nr_Seq)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`SEQUENCES` <= ? ';
							$f_d_parameters[] =  $Max_Nr_Seq;
							
						}
						
						
						//Min	
						if(!empty($Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` >= ? ';
							$f_d_parameters[] =  $Nr_sites;
							
						}
						//Max
						if(!empty($Max_Nr_sites)){
							
							$f_d_conditions[] =  ' `aa_alignments`.`COLUMNS` <= ? ';
							$f_d_parameters[] =  $Max_Nr_sites;
							
						}
						
						//min
						if(!empty($tree_len)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $tree_len;
							
							}
						//min	
						if(!empty($tree_dia)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_DIAMETER` <= ? ';
							$f_d_parameters[] =  $tree_dia;
							
						}
						//Branch length
						
						//min
						if(!empty($BL_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`BL_MIN` >= ? ';
							$f_d_parameters[] =  $BL_min;
							
							}
						//max	
						if(!empty($BL_max)){
							
							$f_d_conditions[] =  ' `aa_trees`.`BL_MAX` <= ? ';
							$f_d_parameters[] =  $BL_max;
							
						}
						//mean (min)
						if(!empty($BL_mean_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` >= ? ';
							$f_d_parameters[] =  $BL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($BL_mean_max)){
							
							$f_d_conditions[] =  ' `aa_trees`.`TREE_LENGTH` <= ? ';
							$f_d_parameters[] =  $BL_mean_max;
							
							}
							
							//Internal Branch
							
							
							//min
						if(!empty($IBL_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`IBL_MIN` >= ? ';
							$f_d_parameters[] =  $IBL_min;
							
							}
						//max	
						if(!empty($IBL_max)){
							
							$f_d_conditions[] =  ' `aa_trees`.`IBL_MAX` <= ? ';
							$f_d_parameters[] =  $IBL_max;
							
						}
						//mean (min)
						if(!empty($IBL_mean_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`IBL_MEAN` >= ? ';
							$f_d_parameters[] =  $IBL_mean_min;
							
							}
							
						//mean (max)
						if(!empty($IBL_mean_max)){
							
							$f_d_conditions[] =  ' `aa_trees`.`IBL_MEAN` <= ? ';
							$f_d_parameters[] =  $IBL_mean_max;
							
							}
							
							
							
							
							
						//External Branch
						
						//min
						if(!empty($EBL_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`EBL_MIN` >= ? ';
							$f_d_parameters[] =  $EBL_min;
							
							}
						//max	
						if(!empty($EBL_max)){
							
							$f_d_conditions[] =  ' `aa_trees`.`EBL_MAX` <= ? ';
							$f_d_parameters[] =  $EBL_max;
							
						}
						//mean (min)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`EBL_MEAN` >= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
							
							//mean (max)
						if(!empty($EBL_mean_min)){
							
							$f_d_conditions[] =  ' `aa_trees`.`EBL_MEAN` <= ? ';
							$f_d_parameters[] =  $EBL_mean_min;
							
							}
			}
			
			//Fuze Conditions
			
			if($f_d_conditions)
				$f_d_query .= " AND ".implode(" AND ", $f_d_conditions);
			//echo $f_d_query;
		
			
			
						
			}catch(PDOException $e) {
				
			echo "Connection Stable Query wrong " . $e->getMessage(). $f_d_query;
			}
			
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);

			
		$filter_query_result = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
			
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=trees.txt');
		$output_file = fopen("php://output", "w"); 
		
		$headers_printed = false; 
		$output = " ";
		//$fasta = ">";
		
		foreach ($filter_query_result as $list) {
			 
			
			///download me 	
			if(!$headers_printed){
				
			
			fwrite($output_file,"\n");
			fputcsv($output_file,array("Alignment ID","Newick String","Prop Invar"),"\t");
			$headers_printed = true;
			
			
			
			
			
		}
		// Write Results in Document 
		fwrite($output_file,"\n");
		fputcsv($output_file,$list,"\t");
		fpassthru($output_file);
			
			
			
			
		
		}
			
			
			
		
		}
		
		
		$connect = null;
			



















?>