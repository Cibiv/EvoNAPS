<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=2.0" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<div class = "title id" = "title" />
    <title>EvoNAPS</title>
 	
	
	
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	 <script src="js/main.js"></script> 
	 
	 
	<style>
	
	div{
		
		
		
	
	
	}
	
	#Matrices_Prot{
		
		visibility:hidden;
	}
	
	#Matrices_DNA{
		
		
	}
	
	#Matrices_+F{
		
		
	}
	
	
	
	#Restrict {
		
		
	}
	
	#RHAS_Div {
		
		display: none;
		
		
		
	}
	
	.center {
		 margin-left: 180px;
    margin-right: 180px;
		margin-top: 30px;
		margin-bottom: 80px;
	}
	
	.body {
		
		
		
	}
	
	.nav-item{
		padding-right:40px;
		
		
	}
	

	.navbar-brand{
		
		padding-left: 50px;
		
	}
	
	
	
	
	
	</style>
	
	
	
	
	
	 
  </head>
  <body>
  
  
  <nav class="navbar navbar-expand-sm bg-secondary navbar-dark">
   
   <a class="navbar-brand" href="indexx.php">
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
	 <a class="nav-link active" href="indexx.php"><h4>Home</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="indexx.php"><h4>Documentation</h4></a>
	 </li>
	 <li class="nav-item">
	 <a class="nav-link active" href="indexx.php"><h4>FAQ</h4></a>
	 </li>
	
	</ul>

	 
	
      
</nav>
  
  
   
		<div class = "center">
	<form action= "downloadme_2.php" method="post" class="was-validated">
	
          <br>
		  <div class= "container-fluid">
		  <div class="row g-3 align-items-center">
		<div class = "Radio_DNA_Prot">
          <input type="radio" name="datatype" onclick = "show(0)" id="DNA_Radio" value="dna" checked>
		    <label for = "DNA_Radio"> <h2>  DNA </h2></label>
       
	   
		  <input type="radio" name="datatype" onclick = "show(1)" id="Prot_Radio" value="aa">
		    <label for = "Prot_Radio"> <h2>Proteins</h2> </label>
			
			</div>
			</div>
			
			<section class="Choose_Model">
		<div class = "Choose_Matrices_DNA">	
		
		
		<br>
		<label for = "Matrices_D" id = "Matrices_DNA_L" >  <h3>Matrices for DNA </h3></label>
		 <select name="DNA_model" id="Matrices_DNA">		 
			<option value=""></option>
            <option value="JC">JC</option>
            <option value="K2P">K2P</option>
            <option value="K3P">K3P</option>
			<option value="TNe">TNe</option>
            <option value="TPM2">TPM2</option>
            <option value="TPM3">TPM3</option>
			<option value="F81+F">F81</option>
            <option value="TIM2e">TIM2e</option>
            <option value="TIM3e">TIM3e</option>
			<option value="TIMe">TIMe</option>
            <option value="TVMe">TVMe</option>
            <option value="K3Pu+F">K3Pu</option>
			<option value="SYM">SYM</option>
            <option value="TN+F">TN</option>
			 <option value="HKY+F">HKY</option>
            <option value="TPM2u+F">TPM2u</option>
			<option value="TPM3u+F">TPM3u</option>
            <option value="TIM+F">TIM</option>
            <option value="TIM2+F">TIM2</option>
			<option value="TIM3+F">TIM3</option>
            <option value="TVM+F">TVM</option>
            <option selected value="GTR+F">GTR</option> 
			
			</select>
			
			 </div>
			 
			 <br>
			 <div class = "Choose_Matrices_Prot">	
		
		<label for = "Matrices_P" id = "Matrices_Prot_L"> <h3>Matrices for proteins</h3></label> 
		 <select name="Protein_model" id="Matrices_Prot" > 
			<option value=""></option>
            <option value="cpREV">cpREV</option>
            <option value="Dayhoff">cpREV</option>
			<option value="DCMut">DCMut</option>
            <option value="FLAVI">FLAVI</option>
            <option value="FLU">FLU</option>
			<option value="HIVb">HIVb</option>
            <option value="HIVw">HIVw</option>
            <option value="JTT">JTT</option>
			<option value="JTTDCMut">JTTDCMut</option>
            <option value="LG">LG</option>
            <option value="mtART">mtART</option>
			<option value="mtMAM">mtMAM</option>
            <option value="mtREV">mtREV</option>
            <option value="mtZOA">mtZOA</option>
			<option value="mtMet">mtMet</option>
            <option value="mtVer">mtVer</option>
            <option value="mtIn">mtIn</option>
			<option value="PMB">PMB</option>
            <option value="Q.bird">Q.bird</option>
			<option value="Q.insect">Q.insect</option>
			<option value="Q.mammal">Q.mammal</option>
            <option value="Q.pfam">Q.pfam</option>
            <option value="Q.plant">Q.plant</option>
			<option value="Q.yeast">Q.yeast</option>
            <option value="VT">VT</option>
			<option value="WAG">WAG</option>
            <option value="Blosum62">Blosum62</option>
			
			</select>
			
			
			<input type="checkbox" name="+F" id="Matrices_+F" value="checked" sytle= "display:none">
				<label class="btn btn-outline-dark-lg" for="Matrices_+F"> <h4>+F</h4> </label>
			
			 </div>
			 
			  
			   
			  
			 
			 
			 <br> 
		 
		 
		 <section class = "RHAS_MATC" >
		
		<div class ="btn-group" role="group" aria-label="Basic checkbox toggle button group" "RHAS_MAT" id = "_RHAS_MAT"   >
		
		 <input type="checkbox" name="RHAS_uniform" id="RHAS_MAT_E" value="checked">
		 <label class="btn btn-outline-dark-lg" for="RHAS_MAT_E"> <h4>uniform</h4> </label>
		
		 <input type="checkbox" name="RHAS_I"  id="RHAS_MAT_I" value="+I">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_I"> <h4>+I</h4> </label> 
		 
		 <input type="checkbox" name="RHAS_IG4" id="RHAS_MAT_IG4" value="+I+G4" checked>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_IG4"> <h4>+I+G4</h4> </label> 
		
		 <input type="checkbox" name="RHAS_G4"  id="RHAS_MAT_G4" value="+G4"checked>
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_G4"><h4> +G4 </h4></label> 
		 
		 <input type="checkbox" name="RHAS_R2" id="RHAS_MAT_R2" value="+R2">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R2"><h4> +R2 </h4></label> 
		
		 <input type="checkbox" name="RHAS_R3"  id="RHAS_MAT_R3" value="+R3">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R3"> <h4>+R3 </h4></label>
		 
		 
		 <input type="checkbox" name="RHAS_R4"  id="RHAS_MAT_R4" value="+R4">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R4"> <h4>+R4 </h4></label> 
		 
		 <input type="checkbox" name="RHAS_R5" id="RHAS_MAT_R5" value="+R5">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R5"> <h4>+R5 </h4></label> 
		
		 <input type="checkbox" name="RHAS_R6"  id="RHAS_MAT_R6" value="+R6">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R6"><h4> +R6 </h4></label>
		 
		 
		 <input type="checkbox" name="RHAS_R7"  id="RHAS_MAT_R7" value="+R7">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R7"><h4> +R7 </h4></label> 
		 
		 <input type="checkbox" name="RHAS_R8" id="RHAS_MAT_R8" value="+R8">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R8"><h4> +R8 </h4></label> 
		
		 <input type="checkbox" name="RHAS_R9"  id="RHAS_MAT_R9" value="+R9">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R9"><h4> +R9 </h4></label>
		 
		 <input type="checkbox" name="RHAS_	R10"  id="RHAS_MAT_R10" value="+R10">
		 <label class="btn btn-outline-dark-lg" for = "RHAS_MAT_R10"><h4> +R10 </h4></label> 
		 
		
	
		 
		
		</div>
		</section>
		 
		
		 <hr>
			
			<div class = "Radio_OPT_uOPT">
			
		
          <input type="radio" name="query_type" onclick = "show2(1)" id="OPT_Radio" value="modelparameters" checked> 
		  
		    <label for = "OPT_Radio"> <h4> estimated on modelfinder </h4> </label>
			
			<br>
      
		  <input type="radio" name="query_type" onclick = "show2(0)" id="uOPT_Radio" value="trees">
		    <label for = "uOPT_Radio"><h4> estimated on ml tree</h4> </label> 
			
			</div>
   
			<br>
			
			
			
			<div class = "NeWick" > 
			 <input type="checkbox" name="Newick" id="NewWick"  value="TRUE">
			<label class="btn btn-outline-dark-lg" for="NewWick"> <h4> include trees (Newick format)</h4> </label> 
			
			</div> 
			
			
			<br>
			
		
		<section class="Restrictions">
		<div class = "Restrictions" id = "Restrict"  style = "display : block">
          <label for="amount"> <h4>restriction of model performance</h4></label> <br><hr>

		  <label for="BIC"><h4> min BIC: weight</h4></label> 
		  <input type="number" name="BIC" id="BIC" step = "any" value= "0.05">
		  <label for="AIC"> <h4>min AIC weight:</h4></label> 
          <input type="number" name="AIC" id="AIC" step="any">
		   <label for="AICC"><h4>min AICc weight:</h4></label> 
		  <input type="number" name="AICC" id="AICC" step="any">
		  
		   
		 </div>
        </section>
        <hr>
		
		
		<section class = "Tree_Alignment_Specs_Check " >
		
		<div class = "Alignment_Specs_Check" >
		
		 <input type="checkbox" name="alignment_features" id="Alignment_Specs_Check" onclick = "show3()" value="TRUE">
		 <label for = "Alignment_Specs_Check"><h4> alignment features </h4></label> <br> <hr>
	
		</div>
		
		
		
		 <section class="Alignment_Specs">
		<div class = "Alignment_Specs" id = "Ali_Specs" style = "display : none">
        
		  
		  <label for="Nr_Seq"><h4>min number of sequences</h4></label> 
          <input type="number" name="number_of_sequences" id="Nr_Seq"step = "any" > <br>
		  
		   <label for="Max_Nr_Seq"><h4>max number of sequences</h4></label> 
          <input type="number" name="max_number_of_sequences" id="Max_Nr_Seq"step = "any" > <br>
		  
		  <label for="Nr_sites"><h4>min number of sites</h4></label> 
		  <input type="number" name="number_of_sites" id="Nr_sites" step = "any"> <br>
		  
		  <label for="Max_Nr_sitesNr_Seq"><h4>max number of sites</h4></label> 
		  <input type="number" name="max_number_of_sites" id="Max_Nr_sites" step = "any" > <br>
		  
		  <label for="mean_dis"><h4>min mean distance</h4></label>  
		  <input type="number" name="mean_distance" id="mean_dis" step = "any"> <br>
		  
		  <label for="Max_mean_dis"><h4>max mean distance </h4></label>  
		  <input type="number" name="max_mean_distance" id="Max_mean_dis" step = "any">
		  
		  <br>
		  <br>
		  	<section class="Alignment_Specs">
		<div class = "Alignment_Specs" id = "Ali_Specs" >
           <hr>
		  
		  <label for="Nr_Seq"> <h4>fraction of wildcard gaps:</h4></label> 
          <input type="number" name="wildcard_gaps_fraction" id="Fr_WL_Gaps" step = "any" > <br> 
		  
		  
		  
		 
		  <label for="Fr_Dis_Pat"><h4>fraction of distinct patterns:</h4> </label> 
		  <input type="number" name="distinct_patterns_fraction" id="Fr_Dis_Pat" step = "any" > <br>
		  
		  <label for="Fr_Pars"><h4>fraction of parsimony sites:</h4>  </label> 
		  <input type="number" name="parsimony_sites_fraction" id="Fr_Pars" step = "any" > <br>
		  
		  
			
			
		  
		  </div>
		  </section>
		  
		 <h4> allignment source </h4><br>
		   <label for = "all"><h4> all </h4></label> 
		  <input type="checkbox" name="selectAll"  id="all" onclick = "selectall(this)" value="checked">
		  <label for = "PANDIT"><h4> PANDIT</h4> </label>
		  <input type="checkbox" name="PANDIT"  id="PANDIT"  value="PANDIT">
		   <label for = "Lanfear"><h4> Lanfear </h4></label> 
		  <input type="checkbox" name="Lanfear"  id="Lanfear"  value="Lanfear">
		 <label for = "OrthoMaM"> <h4>OrthoMaM</h4> </label>
		  <input type="checkbox" name="OrthoMaM"  id="OrthoMaM"  value="OrthoMaM">
		  
		  </div>
		  </section>
		
		
		
		
		<hr>
		
		
		
		 <input type="checkbox" name="tree_features"  id="Trees_Specs_Check" onclick = "show4()" value="TRUE">
		 <label for = "Trees_Specs_Check"> <h4>tree features</h4> </label> <br> <hr>
		
		
		</section>
		
 
		  
		  
		  
		  
		  <section class="Tree_Specs">
		<div class = "Tree_Specs" id = "Tree_Specs" style = "display : none">
         
		  <label for="tree_len"><h4>tree length</h4></label> 
          <input type="number" name="tree_length" id="tree_len" step = "any" > <br>
		  <label for="tree_dia"><h4>tree diameter</h4></label>  
		  <input type="number" name="tree_diameter" id="tree_dia" step = "any"> <br> <br>
		  
		  
		  
		
		  
		  
		  
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