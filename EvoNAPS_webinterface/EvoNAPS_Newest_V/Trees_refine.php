<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	
	<div class = "title id" = "title" />
    <title>Trees Interface</title>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="js/main.js"></script>
	<style>
	
	.center {
		 margin-left: 180px;
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
	<nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
   
   <a class="navbar-brand" href="indexx.php">
	<img src="Logo_EvoNAPS_04.png" alt="Avatar Logo" style="width:350px;"> 
	</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="#"><h2>trees</h2></a>
		 <li class="nav-item">
		 </li>
		
    </ul>
	
	<ul class ="navbar-nav ms-auto">
	
	 <li class="nav-item">
	 <a class="nav-link active" href="indexx.php"><h4>Home</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="indexx.php"><h4>Documentation</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="indexx.php"><h4>FAQ</h4></a>
	 </li>
	
	</ul>
	</div>

	 
	
      
</nav>

	
	<body>
	<?php  session_start(); //echo "pls show the number:".$_SESSION['ALID']; ?>
	
	<div class = "center">
	
	<form action= "downloadme_Trees.php" method="post" >
	
	<br>
	<div class= "container-fluid">
	<div class = "Radio_DNA_Prot">
          <input type="radio" name="datatype" onclick = "show(0)" id="DNA_Radio" value="dna" <?php if($_SESSION['datatype']== "dna"){ echo "checked"; }?>>
		    <label for = "DNA_Radio"> <h2>DNA </h2> </label>
       
	   
		  <input type="radio" name="datatype" onclick = "show(1)" id="Prot_Radio" value="aa"<?php if($_SESSION['datatype']== "aa"){ echo "checked"; }?>>
		    <label for = "Prot_Radio"> <h2>Proteins</h2> </label>
			
			</div>
			
			
			<hr>
			<h4> allignment source </h4><br>
		   <label for = "all"> <h4> all </h4> </label> 
		  <input type="checkbox" name="selectAll"  id="all" onclick = "selectall(this)" value="checked" <?php if(isset($_SESSION['selectAll'])){ echo "checked"; }?>>
		  <label for = "PANDIT"> <h4> PANDIT </h4> </label>
		  <input type="checkbox" name="PANDIT"  id="PANDIT"  value="PANDIT" <?php if(isset($_SESSION['PANDIT'])){ echo "checked"; }?>>
		   <label for = "Lanfear"> <h4> Lanfear </h4> </label> 
		  <input type="checkbox" name="Lanfear"  id="Lanfear"  value="Lanfear" <?php if(isset($_SESSION['Lanfear'])){ echo "checked"; }?>>
		 <label for = "OrthoMaM"> <h4> OrthoMaM </h4> </label>
		  <input type="checkbox" name="OrthoMaM"  id="OrthoMaM"  value="OrthoMaM" <?php if(isset($_SESSION['OrthoMaM'])){ echo "checked"; }?>>
		 
			<hr>
		  
		  
		  
		   <section class="Tree_Specs_Check">
		
		
		<div class = "Trees_Specs_Check" >
		 <input type="checkbox" name="tree_features" id="Trees_Specs_Check" onclick = "show4()" value="Checked"<?php if(isset($_SESSION['tree_features'])){ echo "checked"; }?>>
          <label for="Trees_Specs_Check"><h3>tree features </h3> </label> <hr>
		  </div>
		</section>
		  
		  
		  <section class="Tree_Specs">
		<div class = "Tree_Specs" id = "Tree_Specs"<?php if(isset($_SESSION['tree_features'])){ echo "style = display : block"; }else { echo "style = display : none";} ?>>
		 
		  <label for="tree_len"><h4>tree length: min </h4></label> 
		  
          <input type="number" name="tree_length" id="tree_len" step = "any" <?php if(!empty($_SESSION['tree_length'])){ echo "value =".$_SESSION['tree_length'];} ?>> 
		  <label for="Max_tree_len"><h4>max</h4></label> 
          <input type="number" name="max_tree_length" id="Max_tree_len" step = "any" <?php if(!empty($_SESSION['max_tree_length'])){ echo "value =".$_SESSION['max_tree_length'];} ?> > 
		  
		 
		<br>		 
		  <label for="tree_dia"><h4>diameter: min</h4></label>  
		  <input type="number" name="tree_diameter" id="tree_dia" step = "any" <?php if(!empty($_SESSION['tree_diameter'])){ echo "value =".$_SESSION['tree_diameter'];} ?>>  	 
		  <label for="Max_tree_dia"><h4>max</h4></label>  
		  <input type="number" name="max_tree_diameter" id="Max_tree_dia" step = "any" <?php if(!empty($_SESSION['max_tree_diameter'])){ echo "value =".$_SESSION['max_tree_diameter'];} ?>> 
		  
		  <br>
		   <label for="min_branch_length"><h4>branch length</h4> </label>
			<label for="min_branch_length"><h4>min</h4> </label>  		   
		  <input type="number" name="min_branch_length" id="BL_min" step = "any" <?php if(!empty($_SESSION['min_branch_length'])){ echo "value =".$_SESSION['min_branch_length'];} ?>> 
		   <label for="BL_max"><h4>max</h4> </label>  
		  <input type="number" name="max_branch_length" id="BL_max" step = "any" <?php if(!empty($_SESSION['max_branch_length'])){ echo "value =".$_SESSION['max_branch_length'];} ?>> <br>
		  
		  
		  
		  
		  <label for="BL_mean_min"><h4>branch length mean: </h4> </label> 
		  <label for="BL_mean_min"><h4>min</h4> </label> 		  
		  <input type="number" name="min_mean_branch_length" id="BL_mean_min" step = "any" <?php if(!empty($_SESSION['min_branch_length'])){ echo "value =".$_SESSION['min_branch_length'];} ?>> 
		  
		  <label for="BL_mean_max"><h4>max </h4>  </label>  
		  <input type="number" name="max_mean_branch_length" id="BL_mean_max" step = "any" <?php if(!empty($_SESSION['max_branch_length'])){ echo "value =".$_SESSION['max_branch_length'];} ?>> 
		  <br><br>
		  
		 <h4>internal</h4> <hr>
		  
		  <label for="IBL_min"> <h4>internal branch length: </h4> </label>  
		  <label for="IBL_min"> <h4>min</h4></label>  
		  <input type="number" name="min_internal_branch_length" id="IBL_min" step = "any" <?php if(!empty($_SESSION['min_internal_branch_length'])){ echo "value =".$_SESSION['min_internal_branch_length'];} ?>> 
		   <label for="IBL_max"> <h4>max</h4> </label>  
		  <input type="number" name="max_internal_branch_length" id="IBL_max" step = "any" <?php if(!empty($_SESSION['max_internal_branch_length'])){ echo "value =".$_SESSION['max_internal_branch_length'];} ?>> <br>
		  
		  
		  
		   <label for="Amn"><h4>internal branch mean:</h4>  </label> 
		  <label for="IBL_mean_min"><h4>min</h4>  </label> 
		  <input type="number" name="min_mean_internal_branch_length" id="IBL_mean_min" step = "any" <?php if(!empty($_SESSION['min_mean_internal_branch_length'])){ echo "value =".$_SESSION['min_mean_internal_branch_length'];} ?>>

			<label for="IBL_mean_max"><h4>max</h4> </label> 
		  <input type="number" name="max_mean_internal_branch_length" id="IBL_mean_max" step = "any" <?php if(!empty($_SESSION['max_mean_internal_branch_length'])){ echo "value =".$_SESSION['max_mean_internal_branch_length'];} ?>> 		

			<br><br>
		  
		  <label for="amounxnt"> <h4>external</h4></label> <hr>
		  
		  <label for="EBL_min"> <h4>external branch length: </h4>  </label> 
		  <label for="EBL_min"> <h4>min</h4> </label>  
		  <input type="number" name="min_external_branch_length" id="EBL_min" step = "any" <?php if(!empty($_SESSION['min_external_branch_length'])){ echo "value =".$_SESSION['min_external_branch_length'];} ?>> 
		  
		   <label for="EBL_max"><h4>max</h4> </label>  
		  <input type="number" name="max_external_branch_length" id="EBL_max" step = "any" <?php if(!empty($_SESSION['max_external_branch_length'])){ echo "value =".$_SESSION['max_external_branch_length'];} ?>> <br>
		  
		  
		 <label for="Amn"><h4>external branch mean: </h4>  </label> 
		  <label for="EBL_mean_min"><h4>min</h4> </label> 
		  <input type="number" name="min_mean_external_branch_length" id="EBL_mean_min" step = "any" <?php if(!empty($_SESSION['min_mean_external_branch_length'])){ echo "value =".$_SESSION['min_mean_external_branch_length'];} ?>>

			<label for="EBL_mean_max"><h4>max</h4> </label> 
		  <input type="number" name="max_mean_external_branch_length" id="EBL_mean_max" step = "any" <?php if(!empty($_SESSION['max_mean_external_branch_length'])){ echo "value =".$_SESSION['max_mean_external_branch_length'];} ?>> 	
		  
		  </div>
		  </section>
		  <br>
		  
		  <section class="Alignment_Specs_Check">	

		<div class = "Alignment_Specs_Check" >
		 <input type="checkbox" name="alignment_features" id="Alignment_Specs_Check" onclick = "show3()" value="Checked"<?php if(isset($_SESSION['alignment_features'])){ echo "checked"; }?>>
          <label for="Alignment_Specs_Check"><h3>alignment features </h3> </label> <hr>
		  </div>
		  
		  </section>
		  
		  
		  <section class="Ali_Specs ">
		<div class = "Alignment_Specs" id = "Ali_Specs"<?php if(isset($_SESSION['alignment_features'])){ echo "style = display : block";}else { echo "style = display : none";}?>>
          
		  
		  <label for="Nr_Seq"><h4>number of sequences:</h4></label> 
		  <label for="Nr_Seq"><h4>min</h4></label> 
          <input type="number" name="number_of_sequences" id="Nr_Seq" step = "any" <?php if(!empty($_SESSION['number_of_sequences'])){ echo "value =".$_SESSION['number_of_sequences'];} ?> > 
		  
		   <label for="Max_Nr_Seq"><h4>max</h4></label> 
          <input type="number" name="max_number_of_sequences" id="Max_Nr_Seq" step = "any" <?php if(!empty($_SESSION['max_number_of_sequences'])){ echo "value =".$_SESSION['max_number_of_sequences'];} ?>> <br>
		  
		   <label for="Nr_sites"><h4>number of sites:</h4></label> 
		  <label for="Nr_sites"><h4>min</h4></label> 
		  <input type="number" name="number_of_sites" id="Nr_sites" step = "any" <?php if(!empty($_SESSION['number_of_sites'])){ echo "value =".$_SESSION['number_of_sites'];} ?>>
		  
		  <label for="Max_Nr_sitesNr_Seq"><h4>max</h4></label> 
		  <input type="number" name="max_number_of_sites" id="Max_Nr_sites" step = "any" <?php if(!empty($_SESSION['max_number_of_sites'])){ echo "value =".$_SESSION['max_number_of_sites'];} ?>> <br>
		  
		  </div>
		  </section>
		 
		  
		  <br> <br>
		   <section class = "Branches_Check" >
		
		
		</section>
		  
		  <br> <br>
		   
		  
		  
		  
		  <section class="filter_input">
		  <div class = "filter_input" id = "f1_input">
           <input class="btn btn-primary btn-lg" id= "submit" onclick= "loading()" type= "submit" value="Search database" >
		   <br>
		   <br>
		  </div>
		  
		  </div>
		  </div>
		 </form>
        </section>
		
		<br>
		<br>
		 <section class="damn">
  <!-- Footer -->
  <footer class="bg-secondary text-white text-center fixed-bottom" >
    <!-- Grid container -->
    <div class="container p-3">
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
	</body>
	
	
	</html>