<?php

//start session here in order to catch variables
session_start();

// include credentials data here 
include "DB_credentials.php";

		//Initalize variables 
		$Ali_ID = $_SESSION['Alignment_ID'];
		$DNA_Prot = $_SESSION['datatype'];
        //$OPT_uOPT = $_SESSION['query_type'];
        $usedna = false;

        if($DNA_Prot == "dna"){
			
			// DNA w ModelP
			//if( $OPT_uOPT == "modelparameters"){

                $select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
				`ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
				`mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
				ROUND(`mod`.LOGL,4) AS LOGL, 
				ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
				ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
				ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
				ROUND(`mod`.FREQ_A,4) AS FREQ_A, ROUND(`mod`.FREQ_C,4) AS FREQ_C, 
				ROUND(`mod`.FREQ_G,4) AS FREQ_G, ROUND(`mod`.FREQ_T,4) AS FREQ_T,
				ROUND(`mod`.RATE_AC,4) AS RATE_AC, ROUND(`mod`.RATE_AG,4) AS RATE_AG, ROUND(`mod`.RATE_AT,4) AS RATE_AT, 
				ROUND(`mod`.RATE_CG,4) AS RATE_CG, ROUND(`mod`.RATE_CT,4) AS RATE_CT, ROUND(`mod`.RATE_GT,4) AS RATE_GT,
				ROUND(`mod`.ALPHA,5) AS ALPHA, ROUND(`mod`.PROP_INVAR,5) AS PROP_INVAR,
				ROUND(`mod`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`mod`.PROP_CAT_1,5) AS PROP_CAT_1, 
				ROUND(`mod`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`mod`.PROP_CAT_2,5) AS PROP_CAT_2, 
				ROUND(`mod`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`mod`.PROP_CAT_3,5) AS PROP_CAT_3,
				ROUND(`mod`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`mod`.PROP_CAT_4,5) AS PROP_CAT_4, 
				ROUND(`mod`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`mod`.PROP_CAT_5,5) AS PROP_CAT_5, 
				ROUND(`mod`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`mod`.PROP_CAT_6,5) AS PROP_CAT_6,
				ROUND(`mod`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`mod`.PROP_CAT_7,5) AS PROP_CAT_7, 
				ROUND(`mod`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`mod`.PROP_CAT_8,5) AS PROP_CAT_8, 
				ROUND(`mod`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`mod`.PROP_CAT_9,5) AS PROP_CAT_9,
				ROUND(`mod`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`mod`.PROP_CAT_10,5) AS PROP_CAT_10,`tree`.TREE_TYPE";
                //DNA w Tree
            //}else{
                $select1 = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
                `ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
                `tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL,ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
                ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_T,4) AS FREQ_T,
                ROUND(`tree`.RATE_AC,4) AS RATE_AC, ROUND(`tree`.RATE_AG,4) AS RATE_AG, ROUND(`tree`.RATE_AT,4) AS RATE_AT, 
                ROUND(`tree`.RATE_CG,4) AS RATE_CG, ROUND(`tree`.RATE_CT,4) AS RATE_CT, ROUND(`tree`.RATE_GT,4) AS RATE_GT,
                ROUND(`tree`.ALPHA,5) AS ALPHA, ROUND(`tree`.PROP_INVAR,5) AS PROP_INVAR,
                ROUND(`tree`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`tree`.PROP_CAT_1,5) AS PROP_CAT_1, 
                ROUND(`tree`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`tree`.PROP_CAT_2,5) AS PROP_CAT_2, 
                ROUND(`tree`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`tree`.PROP_CAT_3,5) AS PROP_CAT_3,
                ROUND(`tree`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`tree`.PROP_CAT_4,5) AS PROP_CAT_4, 
                ROUND(`tree`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`tree`.PROP_CAT_5,5) AS PROP_CAT_5, 
                ROUND(`tree`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`tree`.PROP_CAT_6,5) AS PROP_CAT_6,
                ROUND(`tree`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`tree`.PROP_CAT_7,5) AS PROP_CAT_7, 
                ROUND(`tree`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`tree`.PROP_CAT_8,5) AS PROP_CAT_8, 
                ROUND(`tree`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`tree`.PROP_CAT_9,5) AS PROP_CAT_9,
                ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10,`tree`.TREE_TYPE";


            //deactivate here -> }

            //aa w modelp
        } else {

            //if( $OPT_uOPT == "modelparameters"){

                $select = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
                `ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
                `mod`.MODEL, `mod`.BASE_MODEL, `mod`.RHAS_MODEL, 
                ROUND(`mod`.LOGL,4) AS LOGL, 
                ROUND(`mod`.AIC,4) AS AIC, `mod`.AIC_WEIGHT, 
                ROUND(`mod`.AICC,4) AS AICC, `mod`.AICC_WEIGHT, 
                ROUND(`mod`.BIC,4) AS BIC, `mod`.BIC_WEIGHT,ROUND(`tree`.TREE_LENGTH,5) AS Tree_L , ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
                ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_R,4) AS FREQ_R, ROUND(`tree`.FREQ_N,4) AS FREQ_N, 
                ROUND(`tree`.FREQ_D,4) AS FREQ_D, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_Q,4) AS FREQ_Q, 
                ROUND(`tree`.FREQ_E,4) AS FREQ_E, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_H,4) AS FREQ_H, 
                ROUND(`tree`.FREQ_I,4) AS FREQ_I, ROUND(`tree`.FREQ_L,4) AS FREQ_L, ROUND(`tree`.FREQ_K,4) AS FREQ_K, 
                ROUND(`tree`.FREQ_M,4) AS FREQ_M, ROUND(`tree`.FREQ_F,4) AS FREQ_F, ROUND(`tree`.FREQ_P,4) AS FREQ_P, 
                ROUND(`tree`.FREQ_S,4) AS FREQ_S, ROUND(`tree`.FREQ_T,4) AS FREQ_T, ROUND(`tree`.FREQ_W,4) AS FREQ_W, 
                ROUND(`tree`.FREQ_Y,4) AS FREQ_Y, ROUND(`tree`.FREQ_V,4) AS FREQ_V,
                ROUND(`mod`.ALPHA,5) AS ALPHA, ROUND(`mod`.PROP_INVAR,5) AS PROP_INVAR,
                ROUND(`mod`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`mod`.PROP_CAT_1,5) AS PROP_CAT_1, 
                ROUND(`mod`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`mod`.PROP_CAT_2,5) AS PROP_CAT_2, 
                ROUND(`mod`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`mod`.PROP_CAT_3,5) AS PROP_CAT_3,
                ROUND(`mod`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`mod`.PROP_CAT_4,5) AS PROP_CAT_4, 
                ROUND(`mod`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`mod`.PROP_CAT_5,5) AS PROP_CAT_5, 
                ROUND(`mod`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`mod`.PROP_CAT_6,5) AS PROP_CAT_6,
                ROUND(`mod`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`mod`.PROP_CAT_7,5) AS PROP_CAT_7, 
                ROUND(`mod`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`mod`.PROP_CAT_8,5) AS PROP_CAT_8, 
                ROUND(`mod`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`mod`.PROP_CAT_9,5) AS PROP_CAT_9,
                ROUND(`mod`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`mod`.PROP_CAT_10,5) AS PROP_CAT_10,`tree`.TREE_TYPE";

                
                //Aa w tree
           // activate here }else{

                $select1 = "`ali`.ALI_ID, `ali`.TAXA, `ali`.SITES, `ali`.DISTINCT_PATTERNS, 
                `ali`.PARSIMONY_INFORMATIVE_SITES, `ali`.FRAC_WILDCARDS_GAPS, `ali`.DIST_MEAN,
                `tree`.MODEL, `tree`.BASE_MODEL, `tree`.RHAS_MODEL, ROUND(`tree`.LOGL,4) AS LOGL, ROUND(`tree`.TREE_LENGTH,5) AS Tree_L, ROUND(`tree`.TREE_DIAMETER,5) AS Tree_D,
                ROUND(`tree`.FREQ_A,4) AS FREQ_A, ROUND(`tree`.FREQ_R,4) AS FREQ_R, ROUND(`tree`.FREQ_N,4) AS FREQ_N, 
                ROUND(`tree`.FREQ_D,4) AS FREQ_D, ROUND(`tree`.FREQ_C,4) AS FREQ_C, ROUND(`tree`.FREQ_Q,4) AS FREQ_Q, 
                ROUND(`tree`.FREQ_E,4) AS FREQ_E, ROUND(`tree`.FREQ_G,4) AS FREQ_G, ROUND(`tree`.FREQ_H,4) AS FREQ_H, 
                ROUND(`tree`.FREQ_I,4) AS FREQ_I, ROUND(`tree`.FREQ_L,4) AS FREQ_L, ROUND(`tree`.FREQ_K,4) AS FREQ_K, 
                ROUND(`tree`.FREQ_M,4) AS FREQ_M, ROUND(`tree`.FREQ_F,4) AS FREQ_F, ROUND(`tree`.FREQ_P,4) AS FREQ_P, 
                ROUND(`tree`.FREQ_S,4) AS FREQ_S, ROUND(`tree`.FREQ_T,4) AS FREQ_T, ROUND(`tree`.FREQ_W,4) AS FREQ_W, 
                ROUND(`tree`.FREQ_Y,4) AS FREQ_Y, ROUND(`tree`.FREQ_V,4) AS FREQ_V,
                ROUND(`tree`.ALPHA,5) AS ALPHA, ROUND(`tree`.PROP_INVAR,5) AS PROP_INVAR,
                ROUND(`tree`.REL_RATE_CAT_1,5) AS RATE_CAT_1, ROUND(`tree`.PROP_CAT_1,5) AS PROP_CAT_1, 
                ROUND(`tree`.REL_RATE_CAT_2,5) AS RATE_CAT_2, ROUND(`tree`.PROP_CAT_2,5) AS PROP_CAT_2, 
                ROUND(`tree`.REL_RATE_CAT_3,5) AS RATE_CAT_3, ROUND(`tree`.PROP_CAT_3,5) AS PROP_CAT_3,
                ROUND(`tree`.REL_RATE_CAT_4,5) AS RATE_CAT_4, ROUND(`tree`.PROP_CAT_4,5) AS PROP_CAT_4, 
                ROUND(`tree`.REL_RATE_CAT_5,5) AS RATE_CAT_5, ROUND(`tree`.PROP_CAT_5,5) AS PROP_CAT_5, 
                ROUND(`tree`.REL_RATE_CAT_6,5) AS RATE_CAT_6, ROUND(`tree`.PROP_CAT_6,5) AS PROP_CAT_6,
                ROUND(`tree`.REL_RATE_CAT_7,5) AS RATE_CAT_7, ROUND(`tree`.PROP_CAT_7,5) AS PROP_CAT_7, 
                ROUND(`tree`.REL_RATE_CAT_8,5) AS RATE_CAT_8, ROUND(`tree`.PROP_CAT_8,5) AS PROP_CAT_8, 
                ROUND(`tree`.REL_RATE_CAT_9,5) AS RATE_CAT_9, ROUND(`tree`.PROP_CAT_9,5) AS PROP_CAT_9,
                ROUND(`tree`.REL_RATE_CAT_10,5) AS RATE_CAT_10, ROUND(`tree`.PROP_CAT_10,5) AS PROP_CAT_10,`tree`.TREE_TYPE";



            //}






        }




        
      // dna or aa modelp
        $query_select = " SELECT ".$select . " FROM ";
    // dna or aa tree

        $query_select1 = " SELECT ".$select1 . " FROM ";
        if($DNA_Prot == "dna"){
				
				
            //decide to search in modelparameters or trees
            
            //if( $OPT_uOPT == "modelparameters"){
                
                //Alignment join
                $query_select .= "`dna_alignments` as `ali`  INNER JOIN `dna_modelparameters` as `mod` USING (`ALI_ID`)";
                //Tree Join 
                $query_select .= " INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`) " ;
                $query_select .= "WHERE `mod`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';

           // }else {
                //Tree Join 
                $query_select1 .= "`dna_alignments` as `ali`  INNER JOIN `dna_trees` as `tree` USING (`ALI_ID`)";
                $query_select1 .= "WHERE `tree`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';
           // }
            //aa w modelp
        } else {

            //if( $OPT_uOPT == "modelparameters"){

                //Alignment join
                $query_select .= "`aa_alignments` as `ali` INNER JOIN `aa_modelparameters` as `mod`  USING (`ALI_ID`)";
                //Tree Join 
                $query_select .= " INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`) " ;
                $query_select .= "WHERE `mod`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';

                //aa w tree
            //}else{

                $query_select1 .= "`aa_alignments` as `ali`  INNER JOIN `aa_trees` as `tree` USING (`ALI_ID`)";
                $query_select1 .= "WHERE `tree`.`ORIGINAL_ALI` = 1"." AND `ali`.ALI_ID=".'"'.$Ali_ID.'"';
            //}
        }

        //first query dna or aa modelp

        $query = $connect->query($query_select);
        $result_query = $query->fetchAll(PDO::FETCH_ASSOC);

        //second query dna or aa tree
        $query1 = $connect->query($query_select1);
        $result_query1 = $query1->fetchAll(PDO::FETCH_ASSOC);

        /// set headers for donwnloading the data	
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=mopdelparameters_alignment.tsv');

    //created file
    $output_file = fopen("php://output", "w"); 
            
    $headers_printed = false; 
    $output = " ";

    fwrite($output_file,"\n");

    fwrite($output_file,"##Modelfinder model parameters");

    fwrite($output_file,"\n");

    fwrite($output_file,"##--------------------------------------------------------------------------------------------------------------------------------------------------------");

    fwrite($output_file,"\n");

    // loop through the fetched data
    foreach ($result_query as $list) {
			 	
			//check for headders 	
        if(!$headers_printed){


            if($DNA_Prot == "dna"){

                //if($OPT_uOPT=="modelparameters"){

                    fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','Tree_Type'),"\t");
				$headers_printed = true;
                    /*
                }else{

                    fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
				'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
				$headers_printed = true;
                }
                */
            }else{

                //if($OPT_uOPT=="modelparameters"){

                    fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
				'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
				'BIC', 'BIC_WEIGHT','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
				'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
				'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR',
				'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
				'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
				'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','Tree_Type'),"\t");
				$headers_printed = true;


                /*}else{

                    fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
                'FRAC_WILDCARDS_GAPS','MODEL','BASE_MODEL','RHAS','LOGL','FREQ_A', 'FREQ_R', 'FREQ_N', 
                'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
                'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR',
                'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
                'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
                'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','BL_MAX','BL_MEAN','IBL_MAX','IBL_MEAN','EBL_MAX','EBL_MEAN','NEWICK_STRING'),"\t");
                $headers_printed = true;
                }



                ROUND(`tree`.BL_MAX,5) AS , ROUND(`tree`.BL_MEAN,5) AS , 
			ROUND(`tree`.IBL_MAX,5) AS IBL_MAX, ROUND(`tree`.IBL_MEAN,5) AS IBL_MEAN,
			ROUND(`tree`.EBL_MAX,5) AS EBL_MAX, ROUND(`tree`.EBL_MEAN,5) AS EBL_MEAN,
                */
            }
			
		}
			
		// Write Results in Document 
		fputcsv($output_file,$list,"\t");
		
		fpassthru($output_file);
		
    }

    

    fwrite($output_file,"\n");

    fwrite($output_file,"## --------------------------------------------------------------------------------------------------------------------------------------------------------");

    fwrite($output_file,"\n");

    fwrite($output_file,"## ML tree model parameters");

    fwrite($output_file,"\n");

    fwrite($output_file,"## --------------------------------------------------------------------------------------------------------------------------------------------------------");

    fwrite($output_file,"\n");

    $headers_printed1 = false; 


    foreach ($result_query1 as $list) {
			 	
        //check for headders 	
    if(!$headers_printed1){


        if($DNA_Prot == "dna"){

             /*
            //if($OPT_uOPT=="modelparameters"){

                fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
            'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
            'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
            'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR','RATE_AC',
            'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
            'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
            'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
            $headers_printed = true;
            */
               
            //}else{

                fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
            'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL','Tree_L','Tree_D','FREQ_A','FREQ_C','FREQ_G','FREQ_T',
            'RATE_AC', 'RATE_AG','RATE_AT','RATE_CG','RATE_CT','RATE_GT','ALPHA','PROP_INVAR',
            'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
            'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
            'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','Tree_Type'),"\t");
            $headers_printed1 = true;
            }else{

            //if($OPT_uOPT=="modelparameters"){
            /*}else{
                fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
            'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL', 'AIC','AIC_WEIGHT','AICC','AICC_WEIGHT',
            'BIC', 'BIC_WEIGHT','AIC_WEIGHT','AICC','AICC_WEIGHT','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
            'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
            'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR','RATE_AC',
            'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
            'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
            'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10'),"\t");
            $headers_printed = true;
            */

            

                fputcsv($output_file,array('Alignment_ID', 'TAXA','SITES','Distinct_PATTERNS','PARSIMONY_INFORMATIVE_SITES',
            'FRAC_WILDCARDS_GAPS', 'DIST_MEAN','MODEL','BASE_MODEL','RHAS','LOGL','Tree_L','Tree_D','FREQ_A', 'FREQ_R', 'FREQ_N', 
            'FREQ_D', 'FREQ_C', 'FREQ_Q', 'FREQ_E', 'FREQ_G','FREQ_H','FREQ_I','FREQ_L','FREQ_K', 'FREQ_M','FREQ_F','FREQ_P', 
            'FREQ_S','FREQ_T', 'FREQ_W', 'FREQ_Y', 'FREQ_V','ALPHA','PROP_INVAR',
            'RATE_CAT_1','PROP_CAT_1','RATE_CAT_2','PROP_CAT_2','RATE_CAT_3','PROP_CAT_3','RATE_CAT_4','PROP_CAT_4',
            'RATE_CAT_5','PROP_CAT_5','RATE_CAT_6','PROP_CAT_6','RATE_CAT_7','PROP_CAT_7','RATE_CAT_8','PROP_CAT_8',
            'RATE_CAT_9','PROP_CAT_9','RATE_CAT_10','PROP_CAT_10','Tree_Type'),"\t");
            $headers_printed1 = true;
            }
            
        }
        
    
        
    // Write Results in Document 
    fputcsv($output_file,$list,"\t");
    
    fpassthru($output_file);
    
    }

		
		//close connection
		$connect = null;

        ?>