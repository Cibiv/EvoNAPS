<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <!--Bootstrap include -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<!-- Bootstrap Font Icon CSS include -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<div class = "title id" = "title" />
    <title>EvoNAPS</title>
 	
	
	
	<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	</script>
	
	
	

	 <script src="js/main.js"></script> 
	 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<style>
	 
	 .center {
		 margin-left: 130px;
    margin-right: 180px;
		margin-top: 30px;
		margin-bottom: 80px;
	}
	
	.nav-item{
		padding-right:40px;
		
		
	}
	

	.navbar-brand{
		
		padding-left: 50px;
		
	}
	 </style>




 </head>
  
  <div class = "fix">
  <nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
   
   <a class="navbar-brand" href="index.php">
	<img src="Logo_EvoNAPS_04.png" alt="Avatar Logo" style="width:350px;"> 
	</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="#"><h2>results</h2></a>
		 <li class="nav-item">
		 </li>
		
    </ul>
	
	<ul class ="navbar-nav ms-auto">
	
	 <li class="nav-item">
	 <a class="nav-link active" href="index.php"><h4>Home</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="index.php"><h4>Documentation</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="index.php"><h4>FAQ</h4></a>
	 </li>
	
	</ul>
	</div>

	 
	
      
</nav>







<?php
Include 'query_builder_tree.php';
echo '<div class ="center">';

//Download Button 
		
		
		//Count 
		$filter_query = $connect->prepare($f_d_query);
		$filter_query->execute($f_d_parameters);
		//Preview
		$filter_query_1 = $connect->prepare($f_d_query_1);
		$filter_query_1->execute($f_d_parameters);
		
		$filter_query_result = $filter_query_1->fetchAll(PDO::FETCH_ASSOC);
		$filter_query_result_met = $filter_query->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($filter_query_result_met as $x){

			$count = $x["NR_HITS"];
			break;

		}
		
		
		//$count = $filter_query -> fetchColumn();
		
		
			echo "<br>";
			
			echo "<h1> Number of hits in the database: ". " ". '<span class="badge bg-dark">'.$count.'</span>'.'</h1>';
			
			echo " <br>";
	
			echo " <h4> If you are satisfied with your search you can downlod your result set down below.  </h4>";
			
			echo "<br>";


			echo " <br>";
			echo "<h2>Summary statistics of selected dataset</h2>";
			echo " <hr>";
			echo "<h3> Alignment / Tree statistics </h3>";
			echo "<br>";
			echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
				<th scope="col">TAXA</th>
				<th scope="col">SITES</th>
				<th scope="col">DISTINCT_PATTERNS</th>
				<th scope="col">PARSIMONY_INFORMATIVE_SITES</th>
				<th scope="col">FRAC_WILDCARDS_GAPS</th>
				<th scope="col">TREE_LENGTH</th>
				<th scope="col">TREE_DIAMETER</th>
			  </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result_met as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["AVG_TAXA"].' </th>';
				echo '<td>'.$y["AVG_SITES"].'</th>';
				echo '<td>'.$y["AVG_DISTINCT_PATTERNS"].'</th>';
				echo '<td>'.$y["AVG_PARSIMONY_INFORMATIVE_SITES"].'</th>';
				echo '<td>'.$y["AVG_FRAC_WILDCARDS_GAPS"].'</th>';
				echo '<td>'.$y["Tree_L"].'</th>';
				echo '<td>'.$y["Tree_D"].'</th>';
				
			}
			echo '</tr>';
			echo '</tbody>
				</table>';
			echo "<br>";

			echo "<h3> Rates / Alpha / Proportion of invariable sites</h3>";
			echo "<br>";

			echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>';
		
			  if($DNA_Prot == "dna"){
		echo '<th scope="col">FREQ_A</th>
			  <th scope="col">FREQ_C</th>
			  <th scope="col">FREQ_G</th>
			  <th scope="col">FREQ_T</th>
			  <th scope="col">RATE_AC</th>
			  <th scope="col">RATE_AG</th>
			  <th scope="col">RATE_AT</th>
			  <th scope="col">RATE_CG</th>
			  <th scope="col">RATE_CT</th>
			  <th scope="col">RATE_GT</th>';
		   }else{
			  echo '<th scope="col">FREQ_A</th>
			  <th scope="col">FREQ_D</th>
			  <th scope="col">FREQ_E</th>
			  <th scope="col">FREQ_T</th>
			  <th scope="col">FREQ_I</th>
			  <th scope="col">FREQ_M</th>
			  <th scope="col">FREQ_S</th>
			  <th scope="col">FREQ_Y</th>
			  <th scope="col">FREQ_R</th>
			  <th scope="col">FREQ_C</th>
			  <th scope="col">FREQ_G</th>
			  <th scope="col">FREQ_L</th>
			  <th scope="col">FREQ_F</th>
			  <th scope="col">FREQ_T</th>
			  <th scope="col">FREQ_V</th>
			  <th scope="col">FREQ_N</th>
			  <th scope="col">FREQ_Q</th>
			  <th scope="col">FREQ_H</th>
			  <th scope="col">FREQ_K</th>
			  <th scope="col">FREQ_P</th>
			  <th scope="col">FREQ_W</th>';
		   }
		   echo '<th scope="col">ALPHA</th>
		   <th scope="col">PROP_INVAR</th>';
			  
		   echo' </tr>';
		
			  echo '</thead>';
			echo '<tbody>';

			if($DNA_Prot == "dna"){ 
				foreach ($filter_query_result_met as $y){
					 
					echo '<tr>'; 
					echo '<td>'.$y["AVG_FREQ_A"].'</th>';
					echo '<td>'.$y["AVG_FREQ_C"].'</th>';
					echo '<td>'.$y["AVG_FREQ_G"].'</th>';
					echo '<td>'.$y["AVG_FREQ_T"].'</th>';
					echo '<td>'.$y["AVG_RATE_AC"].'</th>';
					echo '<td>'.$y["AVG_RATE_AG"].'</th>';
					echo '<td>'.$y["AVG_RATE_AT"].'</th>';
					echo '<td>'.$y["AVG_RATE_CG"].'</th>';
					echo '<td>'.$y["AVG_RATE_CT"].'</th>';
					echo '<td>'.$y["AVG_RATE_GT"].'</th>';
					echo '<td>'.$y["AVG_ALPHA"].'</th>';
					echo '<td>'.$y["AVG_PROP_INVAR"].'</th>';
					echo '</tr>';
					
				}
			}else{
				foreach ($filter_query_result_met as $y){
					 
					echo '<tr>'; 
					echo '<td>'.$y["AVG_FREQ_A"].'</th>';
					echo '<td>'.$y["AVG_FREQ_D"].'</th>';
					echo '<td>'.$y["AVG_FREQ_E"].'</th>';
					echo '<td>'.$y["AVG_FREQ_T"].'</th>';
					echo '<td>'.$y["AVG_FREQ_I"].'</th>';
					echo '<td>'.$y["AVG_FREQ_M"].'</th>';
					echo '<td>'.$y["AVG_FREQ_S"].'</th>';
					echo '<td>'.$y["AVG_FREQ_Y"].'</th>';
					echo '<td>'.$y["AVG_FREQ_R"].'</th>';
					echo '<td>'.$y["AVG_FREQ_C"].'</th>';
					echo '<td>'.$y["AVG_FREQ_G"].'</th>';
					echo '<td>'.$y["AVG_FREQ_L"].'</th>';
					echo '<td>'.$y["AVG_FREQ_F"].'</th>';
					echo '<td>'.$y["AVG_FREQ_T"].'</th>';
					echo '<td>'.$y["AVG_FREQ_V"].'</th>';
					echo '<td>'.$y["AVG_FREQ_N"].'</th>';
					echo '<td>'.$y["AVG_FREQ_Q"].'</th>';
					echo '<td>'.$y["AVG_FREQ_H"].'</th>';
					echo '<td>'.$y["AVG_FREQ_K"].'</th>';
					echo '<td>'.$y["AVG_FREQ_P"].'</th>';
					echo '<td>'.$y["AVG_FREQ_W"].'</th>';
					echo '<td>'.$y["AVG_ALPHA"].'</th>';
					echo '<td>'.$y["AVG_PROP_INVAR"].'</th>';
					echo '</tr>';
	
				}
			}
			echo '</tr>';
			echo '</tbody>
				</table>';

				echo "<br>";

				//here
			echo "<h3> Branch statistics </h3>";
			echo "<br>";
			echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
				<th scope="col"> max branch length (avg)</th>
				<th scope="col"> mean branch length (avg)</th>
				<th scope="col">max internal branch length (avg)</th>
				<th scope="col">mean internal branch length (avg)</th>
				<th scope="col">max external branch length (avg)</th>
				<th scope="col">mean external branch length (avg)</th>
			  </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result_met as $y){
				 
				echo '<tr>'; 
				echo '<td>'.$y["AVG_BL_MAX"].'</th>';
				echo '<td>'.$y["AVG_BL_MEAN"].'</th>';
				echo '<td>'.$y["AVG_IBL_MAX"].'</th>';
				echo '<td>'.$y["AVG_IBL_MEAN"].'</th>';
				echo '<td>'.$y["AVG_EBL_MAX"].'</th>';
				echo '<td>'.$y["AVG_EBL_MEAN"].'</th>';
				
			}
			echo '</tr>';
			echo '</tbody>
				</table>';













	 echo "<hr>";
	echo "<h3>Used search parameters: </h3>";
  
   echo "<br>";
  foreach($_POST as $names => $values){
	  
	  if(!empty($values)){
	  echo "<h4>".$names.": "." ". " ".$values." "."</h4>";
		//echo "<h3>".$values." "."</h3>";
		//echo "<br>";
	}  
	 // echo "<br>".""."<hr>";
  }
	echo "<br>";
	echo "<hr>";
	
	
	
	echo '<h3> Download tree dataset:  <a href ="download_button_trees.php" title = "Note!" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Here you can download your collected data of trees! "> Download</a></h3> ';
	echo '<h3> Download  branches dataset:  <a href ="download_button_trees_2.php" title = "Note!" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Here you can download your collected data of branches! "> Download</a></h3> ';
	echo "<br>";
	echo '<h3>Refine your search: <a href ="form_tree_refine.php" title = "Note!" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content=" Here you can go back to the orignal form with search parameters already filled in ">Refine </a> </h3> ';
	echo "<hr>";
	
	echo "<h3> Preview of selected dataset (max 20 results): </h3>";

	/* old
		foreach ($filter_query_result as $list) {		
		echo "<br>" ;
		
		echo implode("\n", $list);
		
		echo "<br>";
	
	
	}
	*/ 
	//new
	echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
			  	<th scope="col">Ali ID</th>
				<th scope="col">TAXA</th>
				<th scope="col">SITES</th>
				<th scope="col">DISTINCT_PATTERNS</th>
				<th scope="col">PARSIMONY_SITES</th>
				<th scope="col">FRAC_WILDCARDS_GAPS</th>
				<th scope="col">MODEL</th>
				<th scope="col">BASE_MODEL</th>
				<th scope="col">RHAS</th>
				<th scope="col">LOGL</th>
				 </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["TAXA"].'</th>';
				echo '<td>'.$y["SITES"].'</th>';
				echo '<td>'.$y["DISTINCT_PATTERNS"].'</th>';
				echo '<td>'.$y["PARSIMONY_INFORMATIVE_SITES"].'</th>';
				echo '<td>'.$y["FRAC_WILDCARDS_GAPS"].'</th>';
				echo '<td>'.$y["MODEL"].'</th>';
				echo '<td>'.$y["BASE_MODEL"].'</th>';
				echo '<td>'.$y["RHAS_MODEL"].'</th>';
				echo '<td>'.$y["LOGL"].'</th>';
				echo '</tr>';
				
			}
			
			echo '</tbody>
				</table>';


				echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>';
			 if($DNA_Prot == "dna"){
			  	echo '<th scope="col">Ali ID</th>
				<th scope="col">FREQ_A</th>
				<th scope="col">FREQ_C</th>
				<th scope="col">FREQ_G</th>
				<th scope="col">FREQ_T</th>
				<th scope="col">RATE_AC</th>
				<th scope="col">RATE_AG</th>
				<th scope="col">RATE_AT</th>
				<th scope="col">RATE_CG</th>
				<th scope="col">RATE_CT</th>
				<th scope="col">RATE_GT</th>';
			 }else{
				echo '<th scope="col">Ali ID</th>
				<th scope="col">FREQ_A</th>
				<th scope="col">FREQ_D</th>
				<th scope="col">FREQ_E</th>
				<th scope="col">FREQ_T</th>
				<th scope="col">FREQ_I</th>
				<th scope="col">FREQ_M</th>
				<th scope="col">FREQ_S</th>
				<th scope="col">FREQ_Y</th>
				<th scope="col">FREQ_R</th>
				<th scope="col">FREQ_C</th>
				<th scope="col">FREQ_G</th>
				<th scope="col">FREQ_L</th>
				<th scope="col">FREQ_F</th>
				<th scope="col">FREQ_T</th>
				<th scope="col">FREQ_V</th>
				<th scope="col">FREQ_N</th>
				<th scope="col">FREQ_Q</th>
				<th scope="col">FREQ_H</th>
				<th scope="col">FREQ_K</th>
				<th scope="col">FREQ_P</th>
				<th scope="col">FREQ_W</th>';
			 }
			 echo '<th scope="col">ALPHA</th>
			 <th scope="col">PROP_INVAR</th>';
				
			 echo' </tr>';
		
			  echo '</thead>';
			echo '<tbody>';

			if($DNA_Prot == "dna"){ 
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["FREQ_A"].'</th>';
				echo '<td>'.$y["FREQ_C"].'</th>';
				echo '<td>'.$y["FREQ_G"].'</th>';
				echo '<td>'.$y["FREQ_T"].'</th>';
				echo '<td>'.$y["RATE_AC"].'</th>';
				echo '<td>'.$y["RATE_AG"].'</th>';
				echo '<td>'.$y["RATE_AT"].'</th>';
				echo '<td>'.$y["RATE_CG"].'</th>';
				echo '<td>'.$y["RATE_CT"].'</th>';
				echo '<td>'.$y["RATE_GT"].'</th>';
				echo '<td>'.$y["ALPHA"].'</th>';
				echo '<td>'.$y["PROP_INVAR"].'</th>';
				echo '</tr>';
				
			}
		}else{
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["FREQ_A"].'</th>';
				echo '<td>'.$y["FREQ_D"].'</th>';
				echo '<td>'.$y["FREQ_E"].'</th>';
				echo '<td>'.$y["FREQ_T"].'</th>';
				echo '<td>'.$y["FREQ_I"].'</th>';
				echo '<td>'.$y["FREQ_M"].'</th>';
				echo '<td>'.$y["FREQ_S"].'</th>';
				echo '<td>'.$y["FREQ_Y"].'</th>';
				echo '<td>'.$y["FREQ_R"].'</th>';
				echo '<td>'.$y["FREQ_C"].'</th>';
				echo '<td>'.$y["FREQ_G"].'</th>';
				echo '<td>'.$y["FREQ_L"].'</th>';
				echo '<td>'.$y["FREQ_F"].'</th>';
				echo '<td>'.$y["FREQ_T"].'</th>';
				echo '<td>'.$y["FREQ_V"].'</th>';
				echo '<td>'.$y["FREQ_N"].'</th>';
				echo '<td>'.$y["FREQ_Q"].'</th>';
				echo '<td>'.$y["FREQ_H"].'</th>';
				echo '<td>'.$y["FREQ_K"].'</th>';
				echo '<td>'.$y["FREQ_P"].'</th>';
				echo '<td>'.$y["FREQ_W"].'</th>';
				echo '<td>'.$y["ALPHA"].'</th>';
				echo '<td>'.$y["PROP_INVAR"].'</th>';
				echo '</tr>';

			}
		}
			
			echo '</tbody>
				</table>';

				echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
			  	<th scope="col">Ali ID</th>
				<th scope="col">RATE_CAT_1</th>
				<th scope="col">PROP_CAT_1</th>
				<th scope="col">RATE_CAT_2</th>
				<th scope="col">PROP_CAT_2</th>
				<th scope="col">RATE_CAT_3</th>
				<th scope="col">PROP_CAT_3</th>
				<th scope="col">RATE_CAT_4</th>
				<th scope="col">PROP_CAT_4</th>
				<th scope="col">RATE_CAT_5</th>
				<th scope="col">PROP_CAT_5</th>
				
			  </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["RATE_CAT_1"].'</th>';
				echo '<td>'.$y["PROP_CAT_1"].'</th>';
				echo '<td>'.$y["RATE_CAT_2"].'</th>';
				echo '<td>'.$y["PROP_CAT_2"].'</th>';
				echo '<td>'.$y["RATE_CAT_3"].'</th>';
				echo '<td>'.$y["PROP_CAT_3"].'</th>';
				echo '<td>'.$y["RATE_CAT_4"].'</th>';
				echo '<td>'.$y["PROP_CAT_4"].'</th>';
				echo '<td>'.$y["RATE_CAT_5"].'</th>';
				echo '<td>'.$y["PROP_CAT_5"].'</th>';
				echo '</tr>';
				
			}
			
			echo '</tbody>
				</table>';

				
				echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
			  	<th scope="col">Ali ID</th>
				  <th scope="col">RATE_CAT_6</th>
				  <th scope="col">PROP_CAT_6</th>
				  <th scope="col">RATE_CAT_7</th>
				  <th scope="col">PROP_CAT_7</th>
				  <th scope="col">RATE_CAT_8</th>
				  <th scope="col">PROP_CAT_8</th>
				  <th scope="col">RATE_CAT_9</th>
				  <th scope="col">PROP_CAT_9</th>
				  <th scope="col">RATE_CAT_10</th>
				  <th scope="col">PROP_CAT_10</th>
				
			  </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["RATE_CAT_6"].'</th>';
				echo '<td>'.$y["PROP_CAT_6"].'</th>';
				echo '<td>'.$y["RATE_CAT_7"].'</th>';
				echo '<td>'.$y["PROP_CAT_7"].'</th>';
				echo '<td>'.$y["RATE_CAT_8"].'</th>';
				echo '<td>'.$y["PROP_CAT_8"].'</th>';
				echo '<td>'.$y["RATE_CAT_9"].'</th>';
				echo '<td>'.$y["PROP_CAT_9"].'</th>';
				echo '<td>'.$y["RATE_CAT_10"].'</th>';
				echo '<td>'.$y["PROP_CAT_10"].'</th>';
				echo '</tr>';
				
			}
			
			echo '</tbody>
				</table>';

				echo '<table class="table table-striped table-sm">
			<thead>
			  <tr>
			  	<th scope="col">Ali ID</th>
				  <th scope="col">BL_MAX</th>
				  <th scope="col">BL_MEAN</th>
				  <th scope="col">IBL_MAX</th>
				  <th scope="col">IBL_MEAN</th>
				  <th scope="col">EBL_MAX</th>
				  <th scope="col">EBL_MEAN</th>
			  </tr>';
		
			  echo '</thead>';
			echo '<tbody>';
			foreach ($filter_query_result as $y){
				 
				echo '<tr>'; 
				echo '<th scope="row"> '.$y["ALI_ID"].' </th>';
				echo '<td>'.$y["BL_MAX"].'</th>';
				echo '<td>'.$y["BL_MEAN"].'</th>';
				echo '<td>'.$y["IBL_MAX"].'</th>';
				echo '<td>'.$y["IBL_MEAN"].'</th>';
				echo '<td>'.$y["EBL_MAX"].'</th>';
				echo '<td>'.$y["EBL_MEAN"].'</th>';
				echo '</tr>';
				
			}
			
			echo '</tbody>
				</table>';
	

		
		
		// echo "$modeld";
		
		echo " <br>";
		echo' </div>';	
			
			
		
		
		
		
		
		////////////////////////////////////////
		
			
			
		
		
		
		//fclose(output_file);
		
		
		$connect = null;



?>
<script>
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})
</script>
<section class="damn">
  <!-- Footer -->
  <footer class="bg-secondary text-white text-center " >
    <!-- Grid container -->
    <div class="container p-4">
      <!--Grid row-->
      <div class="row">
        <!--Grid column-->
        <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
		
        


          
        </div>
        <!--Grid column-->
		 <h6 class="text-lowercase">impressum </h6>

        
      
      </div>
      <!--Grid row-->
    </div>
    <!-- Grid container -->

    <!-- Copyright -->
   
  </footer>
  <!-- Footer -->
</section>