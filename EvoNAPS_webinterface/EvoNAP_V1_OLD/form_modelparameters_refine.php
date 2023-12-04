<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<div class = "title id" = "title" />
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>EvoNAPS</title>
	
	 
 	
	
	
	
	 <script src="js/main.js"></script> 
	  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	 
	 
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
	
	
	
	#RHAS_Div {
		
		display: none;
		
		
		
	}
	
	
	
	</style>
	
	 <nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
   
   <a class="navbar-brand" href="index.php">
	<img src="Logo_EvoNAPS_04.png" alt="Avatar Logo" style="width:350px;"> 
	</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="#"><h2> modelparameters</h2></a>
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

	 
	
      
</nav>
	
	
	
	 
  </head>
  <body>
	 
  <?php  session_start(); ?>
  <div class ="center">
  

   
  
	<form action= "results_modelparameters.php" method="post" >
	
          <br>
		<div class = "Radio_DNA_Prot">
          <input type="radio" name="datatype" onclick = "show(0)" id="DNA_Radio" value="dna" <?php if($_SESSION['datatype']== "dna"){ echo "checked"; }?>>
		    <label for = "DNA_Radio"> <h2>  DNA </h2> </label>
       
	   
		  <input type="radio" name="datatype" onclick = "show(1)" id="Prot_Radio" value="aa"<?php if($_SESSION['datatype']== "aa"){ echo "checked"; }?>>
		    <label for = "Prot_Radio"> <h2>Proteins</h2> </label>
			
			</div>
			<section class="Choose_Model">
		<div class = "Choose_Matrices_DNA">	
		
		<br>
		<br>
		<label for = "Matrices_D" id = "Matrices_DNA_L" >  <h3>Matrices for DNA </h3></label>
		 <select name="DNA_model" id="Matrices_DNA" <?php if($_SESSION['DNA_Prot']== "dna"){ echo "style = visibility : visible";}?> >		 
			<option value=""></option>
            <option value="JC" <?php if($_SESSION['DNA_model']=="JC"){ echo "selected";} ?>>JC</option>
            <option value="K2P"<?php if($_SESSION['DNA_model']=="K2P"){ echo "selected";} ?>>K2P</option>
            <option value="K3P" <?php if($_SESSION['DNA_model']=="K3P"){ echo "selected";} ?>>K3P</option>
			<option value="TNe"<?php if($_SESSION['DNA_model']=="TNe"){ echo "selected";} ?>>TNe</option>
            <option value="TPM2"<?php if($_SESSION['DNA_model']=="TPM2"){ echo "selected";} ?>>TPM2</option>
            <option value="TPM3"<?php if($_SESSION['DNA_model']=="TPM3"){ echo "selected";} ?>>TPM3</option>
			<option value="F81+F"<?php if($_SESSION['DNA_model']=="F81"){ echo "selected";} ?>>F81</option>
            <option value="TIM2e"<?php if($_SESSION['DNA_model']=="TIM2e"){ echo "selected";} ?>>TIM2e</option>
            <option value="TIM3e"<?php if($_SESSION['DNA_model']=="TIM3e"){ echo "selected";} ?>>TIM3e</option>
			<option value="TIMe"<?php if($_SESSION['DNA_model']=="TIMe"){ echo "selected";} ?>>TIMe</option>
            <option value="TVMe"<?php if($_SESSION['DNA_model']=="TVMe"){ echo "selected";} ?>>TVMe</option>
            <option value="K3Pu+F"<?php if($_SESSION['DNA_model']=="K3Pu+F"){ echo "selected";} ?>>K3Pu</option>
			<option value="SYM"<?php if($_SESSION['DNA_model']=="SYM"){ echo "selected";} ?>>SYM</option>
            <option value="TN+F"<?php if($_SESSION['DNA_model']=="TN+F"){ echo "selected";} ?>>TN</option>
			<option value="HKY+F"<?php if($_SESSION['DNA_model']=="HKY+F"){ echo "selected";} ?>>HKY</option>
            <option value="TPM2u+F"<?php if($_SESSION['DNA_model']=="TPM2u+F"){ echo "selected";} ?>>TPM2u</option>
			<option value="TPM3u+F"<?php if($_SESSION['DNA_model']=="TPM3u+F"){ echo "selected";} ?>>TPM3u</option>
            <option value="TIM+F"<?php if($_SESSION['DNA_model']=="TIM+F"){ echo "selected";} ?>>TIM</option>
            <option value="TIM2+F"<?php if($_SESSION['DNA_model']=="TIM2+F"){ echo "selected";} ?>>TIM2</option>
			<option value="TIM3+F"<?php if($_SESSION['DNA_model']=="TIM3+F"){ echo "selected";} ?>>TIM3</option>
            <option value="TVM+F"<?php if($_SESSION['DNA_model']=="TVM+F"){ echo "selected";} ?>>TVM</option>
            <option  value="GTR+F"<?php if($_SESSION['DNA_model']=="GTR+F"){ echo "selected";} ?>>GTR</option>
			
			</select>
			
			 </div>
			 
			 <br>
			 <div class = "Choose_Matrices_Prot">	
		
		<label for = "Matrices_P" id = "Matrices_Prot_L"> <h3>Matrices for proteins</h3></label>
		 <select name="Protein_model" id="Matrices_Prot"  <?php if($_SESSION['DNA_Prot']== "aa"){ echo "style = visibility : visible";}?>>
			<option value=""></option>
            <option  value="Blosum62"<?php if($_SESSION['Protein_model']=="Blosum62"){ echo "selected";} ?>>Blosum62</option>
            <option value="cpREV"<?php if($_SESSION['Protein_model']=="cpREV"){ echo "selected";} ?>>cpREV</option>
            <option value="Dayhoff"<?php if($_SESSION['Protein_model']=="Dayhoff"){ echo "selected";} ?>>Dayhoff</option>
			<option value="DCMut"<?php if($_SESSION['Protein_model']=="DCMut"){ echo "selected";} ?>>DCMut</option>
            <option value="FLAVI"<?php if($_SESSION['Protein_model']=="FLAVI"){ echo "selected";} ?>>FLAVI</option>
            <option value="FLU"<?php if($_SESSION['Protein_model']=="FLU"){ echo "selected";} ?>>FLU</option>
			<option value="HIVb"<?php if($_SESSION['Protein_model']=="HIVb"){ echo "selected";} ?>>HIVb</option>
            <option value="HIVw"<?php if($_SESSION['Protein_model']=="HIVw"){ echo "selected";} ?>>HIVw</option>
            <option value="JTT"<?php if($_SESSION['Protein_model']=="JTT"){ echo "selected";} ?>>JTT</option>
			<option value="JTTDCMut"<?php if($_SESSION['Protein_model']=="JTTDCMut"){ echo "selected";} ?>>JTTDCMut</option>
            <option value="LG"<?php if($_SESSION['Protein_model']=="LG"){ echo "selected";} ?>>LG</option>
            <option value="mtART"<?php if($_SESSION['Protein_model']=="mtART"){ echo "selected";} ?>>mtART</option>
			<option value="mtMAM"<?php if($_SESSION['Protein_model']=="mtMAM"){ echo "selected";} ?>>mtMAM</option>
            <option value="mtREV"<?php if($_SESSION['Protein_model']=="mtREV"){ echo "selected";} ?>>mtREV</option>
            <option value="mtZOA"<?php if($_SESSION['Protein_model']=="mtZOA"){ echo "selected";} ?>>mtZOA</option>
			<option value="mtMet"<?php if($_SESSION['Protein_model']=="mtMet"){ echo "selected";} ?>>mtMet</option>
            <option value="mtVer"<?php if($_SESSION['Protein_model']=="mtVer"){ echo "selected";} ?>>mtVer</option>
            <option value="mtIn"<?php if($_SESSION['Protein_model']=="mtIn"){ echo "selected";} ?>>mtIn</option>
			<option value="PMB"<?php if($_SESSION['Protein_model']=="PMB"){ echo "selected";} ?>>PMB</option>
            <option value="Q.bird"<?php if($_SESSION['Protein_model']=="Q.bird"){ echo "selected";} ?>>Q.bird</option>
			<option value="Q.insect"<?php if($_SESSION['Protein_model']=="Q.insect"){ echo "selected";} ?>>Q.insect</option>
			<option value="Q.mammal"<?php if($_SESSION['Protein_model']=="Q.mammal"){ echo "selected";} ?>>Q.mammal</option>
            <option value="Q.pfam"<?php if($_SESSION['Protein_model']=="Q.pfam"){ echo "selected";} ?>>Q.pfam</option>
            <option value="Q.plant"<?php if($_SESSION['Protein_model']=="Q.plant"){ echo "selected";} ?>>Q.plant</option>
			<option value="Q.yeast"<?php if($_SESSION['Protein_model']=="Q.yeast"){ echo "selected";} ?>>Q.yeast</option>
            <option value="VT"<?php if($_SESSION['Protein_model']=="VT"){ echo "selected";} ?>>VT</option>
			<option value="WAG"<?php if($_SESSION['Protein_model']=="WAG"){ echo "selected";} ?>>WAG</option>
           
			
			</select>
			
			<input type="checkbox" name="+F" id="Matrices_+F" value="checked"<?php if(isset($_SESSION['+F'])){ echo "checked"; }?>>
				<label class="btn btn-outline-dark-lg" for="Matrices_+F"> <h4>+F</h4> </label>
			
			 </div>
			
			
			 
			 
			 <br> 
		 
		 
		 <section class = "RHAS_MATC" >
		
		<div class ="btn-group" role="group" aria-label="Basic checkbox toggle button group" "RHAS_MAT" id = "_RHAS_MAT"  >
		
		 <input type="checkbox" name="RHAS_uniform" id="RHAS_MAT_E" value="checked" <?php if(isset($_SESSION['RHAS_uniform'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_E"><h4>uniform</h4> </label> 
		
		 <input type="checkbox" name="RHAS_I"  id="RHAS_MAT_I" value="+I" <?php if(isset($_SESSION['RHAS_I'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_I"> <h4>+I</h4></label> 
		 
		 <input type="checkbox" name="RHAS_IG4" id="RHAS_MAT_IG4" value="+I+G4"<?php if(isset($_SESSION['RHAS_IG4'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_IG4"> <h4>+I+G4</h4></label> 
		
		 <input type="checkbox" name="RHAS_G4"  id="RHAS_MAT_G4" value="+G4" <?php if(isset($_SESSION['RHAS_G4'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_G4"> <h4> +G4 </h4> </label> 
		 
		 <input type="checkbox" name="RHAS_R2" id="RHAS_MAT_R2" value="+R2"<?php if(isset($_SESSION['RHAS_R2'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R2"> <h4> +R2 </h4> </label> 
		
		 <input type="checkbox" name="RHAS_R3"  id="RHAS_MAT_R3" value="+R3"<?php if(isset($_SESSION['RHAS_R3'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R3"> <h4>+R3 </h4></label>
		 
		 
		 <input type="checkbox" name="RHAS_R4"  id="RHAS_MAT_R4" value="+R4"<?php if(isset($_SESSION['RHAS_R4'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R4"> <h4>+R4 </h4></label> 
		 
		 <input type="checkbox" name="RHAS_R5" id="RHAS_MAT_R5" value="+R5"<?php if(isset($_SESSION['RHAS_R5'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R5"> <h4>+R5 </h4> </label> 
		
		 <input type="checkbox" name="RHAS_R6"  id="RHAS_MAT_R6" value="+R6"<?php if(isset($_SESSION['RHAS_R6'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R6"> <h4> +R6 </h4></label>
		 
		 
		 <input type="checkbox" name="RHAS_R7"  id="RHAS_MAT_R7" value="+R7"<?php if(isset($_SESSION['RHAS_R7'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R7"> <h4> +R7 </h4> </label> 
		 
		 <input type="checkbox" name="RHAS_R8" id="RHAS_MAT_R8" value="+R8"<?php if(isset($_SESSION['RHAS_R8'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R8"> <h4> +R8 </h4> </label> 
		
		 <input type="checkbox" name="RHAS_R9"  id="RHAS_MAT_R9" value="+R9"<?php if(isset($_SESSION['RHAS_R9'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R9"> <h4> +R9 </h4> </label>
		 
		 <input type="checkbox" name="RHAS_R10"  id="RHAS_MAT_R10" value="+R10"<?php if(isset($_SESSION['RHAS_R10'])){ echo "checked"; }?>>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R10"> <h4> +R10 </h4> </label> 
		 
		
	
		 
		
		
		</section>

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
			
			<div class = "Radio_OPT_uOPT">
			
		
          <input type="radio" name="query_type" onclick = "show2(1)" id="OPT_Radio" value="modelparameters" <?php if($_SESSION['query_type']== "modelparameters"){ echo "checked"; }?>> 
		  
		    <label for = "OPT_Radio"> <h4> estimated on modelfinder </h4>  </label>
			
			<br>
      
		  <input type="radio" name="query_type" onclick = "show2(0)" id="uOPT_Radio" value="trees" <?php if($_SESSION['query_type']== "trees"){ echo "checked"; }?>>
		    <label for = "uOPT_Radio"> <h4> estimated on ml tree</h4> </label> 
			
			</div>
   
			<br>
			
			
			
			
			 <input type="checkbox" name="Newick" id="NewWick"  value="TRUE" <?php if(isset($_SESSION['Newick'])){ echo "checked"; }?>>
			<label for = "NewWick">  <h4> include trees (Newick format)</h4></label> 
			
			
			
			
			<br>
			
		
		<section class="Restrictions">
		<div class = "Restrictions" id = "Restrict"  style = <?php if($_SESSION['query_type']== "modelparameters"){ echo " display : block"; }else { echo " display : none";} ?>>
          <label for="amount"><h4>restriction of model performance</h4></label> <br><hr>
		  
		   <label for="BIC"> <h4> weighted BIC:</h4></label> 
		  <input type="number" name="BIC" id="BIC" step="any" value = <?php echo $_SESSION['BIC']; ?>>
		  <label for="AIC"> <h4>weighted AIC:</h4></label> 
          <input type="number" name="AIC" id="AIC" step="any" value = <?php echo $_SESSION['AIC']; ?>>
		   <label for="AICC"><h4>weighted AICc:</h4></label> 
		  <input type="number" name="AICC" id="AICC" step="any" value = <?php echo $_SESSION['AICC']; ?>>
		 
		   
		 </div>
        </section>
        <hr>
		
		
		<section class = "Tree_Alignment_Specs_Check " >
		
		<div class = "Alignment_Specs_Check" >
		
		 <input type="checkbox" name="alignment_features" id="Alignment_Specs_Check" onclick = "show3()" value="TRUE" <?php if(isset($_SESSION['alignment_features'])){ echo "checked"; }?> >
		 <label for = "Alignment_Specs_Check"> <h4> alignment features </h4> </label> <br> <hr>
	
		</div>
		
		<br>
		
		 <section class="Alignment_Specs">
		<div class = "Alignment_Specs" id = "Ali_Specs" <?php if(isset($_SESSION['alignment_features'])){ echo "style = display : block";}else { echo "style = display : none";}?>>
          
		  <label for="Nr_Seq"><h4>min number of sequences</h4></label> 
          <input type="number" name="number_of_sequences" id="Nr_Seq" step = "any" value = <?php echo $_SESSION['number_of_sequences']; ?>> <br>
		  
		   <label for="Max_Nr_Seq"><h4>max number of sequences</h4></label> 
          <input type="number" name="max_number_of_sequences" id="Max_Nr_Seq" step = "any" value = <?php echo $_SESSION['max_number_of_sequences']; ?> > <br>
		  
		  <label for="Nr_sites"><h4>min number of sites</h4></label> 
		  <input type="number" name="number_of_sites" id="Nr_sites" step = "any" value = <?php echo $_SESSION['number_of_sites']; ?>> <br>
		  
		  <label for="Max_Nr_sitesNr_Seq"><h4>max number of sites</h4></label> 
		  <input type="number" name="max_number_of_sites" id="Max_Nr_sites" step = "any" value = <?php echo $_SESSION['max_number_of_sites']; ?>> <br>
		  
		  <label for="mean_dis"><h4>min mean distance</h4></label>  
		  <input type="number" name="mean_distance" id="mean_dis" step = "any" value = <?php echo $_SESSION['mean_distance']; ?>> <br>
		  
		  <label for="Max_mean_dis"><h4>max mean distance </h4> </label>  
		  <input type="number" name="max_mean_distance" id="Max_mean_dis" step = "any" value = <?php echo $_SESSION['max_mean_distance']; ?>>
		  
		  <br>
		  <br>
		  	<section class="Alignment_Specs">
		<div class = "Alignment_Specs" id = "Ali_Specs" >
         
		  
		  <label for="Nr_Seq"> <h4>fraction of wildcard gaps:</h4></label> 
          <input type="number" name="wildcard_gaps_fraction" id="Fr_WL_Gaps" step = "any"  <?php if(!empty($_SESSION['wildcard_gaps_fraction'])){ echo "value =".$_SESSION['wildcard_gaps_fraction'];} ?>> <br> 
		  
		  
		  <label for="Fr_Dis_Pat"><h4>fraction of distinct patterns:</h4> </label> 
		  <input type="number" name="distinct_patterns_fraction" id="Fr_Dis_Pat" step = "any" <?php if(!empty($_SESSION['distinct_patterns_fraction'])){ echo "value =".$_SESSION['distinct_patterns_fraction'];} ?>> <br>
		  
		  <label for="Fr_Pars"><h4>fraction of parsimony sites:</h4>  </label> 
		  <input type="number" name="parsimony_sites_fraction" id="Fr_Pars" step = "any" <?php if(!empty($_SESSION['parsimony_sites_fraction'])){ echo "value =".$_SESSION['parsimony_sites_fraction'];} ?>> <br>
		  
		  
			<br>
			
		  
		  </div>
		  </section>
		  
		  
		  
		  </div>
		  </section>
		
		
		
		
		<hr>
		
		<div class = "Trees_Specs_Check" >
		
		 <input type="checkbox" name="tree_features"  id="Trees_Specs_Check" onclick = "show4()" value="TRUE" <?php if(isset($_SESSION['tree_features'])){ echo "checked"; }?>>
		 <label for = "Trees_Specs_Check"> <h4>tree features</h4> </label> <br> <hr>
		
		</div>
		</section>
		
 
		  
		  <br>
		  
		  
		  <section class="Tree_Specs">
		<div class = "Tree_Specs" id = "Tree_Specs" <?php if(isset($_SESSION['tree_features'])){ echo "style = display : block"; }else { echo "style = display : none";} ?>>
         
		  <label for="tree_len"><h4>tree length: min </h4></label> 
          <input type="number" name="tree_length" id="tree_len" step = "any" <?php if(!empty($_SESSION['tree_length'])){ echo "value =".$_SESSION['tree_length'];} ?>> 
		  <label for="Max_tree_len"><h4>max</h4></label> 
          <input type="number" name="max_tree_length" id="Max_tree_len" step = "any" <?php if(!empty($_SESSION['max_tree_length'])){ echo "value =".$_SESSION['max_tree_length'];} ?> > 
		   <br>
		  <label for="tree_dia"><h4>diameter: min</h4></label>  
		  <input type="number" name="tree_diameter" id="tree_dia" step = "any" <?php if(!empty($_SESSION['tree_diameter'])){ echo "value =".$_SESSION['tree_diameter'];} ?>>  	 
		  <label for="Max_tree_dia"><h4>max</h4></label>  
		  <input type="number" name="max_tree_diameter" id="Max_tree_dia" step = "any" <?php if(!empty($_SESSION['max_tree_diameter'])){ echo "value =".$_SESSION['max_tree_diameter'];} ?>> 
		  
		  
		  
		  
		
		  
		  
		  
		  </div>
		  </section>
		  
		  
		  
		  
		  
		  
		  
		    <section class="filter_input">
		  <div class = "filter_input" id = "f1_input">
		  		
          <input class="btn btn-primary btn-lg" id= "submit" onclick= "loading()" type= "submit" value="Search database" >
		  </div>
		  </div>
		  </div>
		 </form>
        </section>
		
		  <br>
		  <br>	
		<section class="damn">
  <!-- Footer -->
  <footer class="bg-secondary text-white text-center fixed-bottom " >
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
		
		
	 
		  
		  
	  
	