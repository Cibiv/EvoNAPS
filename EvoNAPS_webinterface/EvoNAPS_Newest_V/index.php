<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<!-- Bootstrap Font Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<div class = "title id" = "title" />
    <title>EvoNAPS</title>
 	
	
	
	
	 <script src="js/main.js"></script> 
	 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	 
	 
	<style>

	.card-group {
 
    margin-left: auto;
    margin-right: auto;
	margin-bottom: 250px;
  height: 600px;
  width: 1200px; 
	}
  
  
  .card{
    margin-right: 10px;
	
    margin-left: 10px;
}
	.center{
		margin-top: 30px;
		margin-bottom: 100px;
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
   
   <a class="navbar-brand" href="index.php">
	<img src="Logo_EvoNAPS_04.png" alt="Avatar Logo" style="width:350px;"> 
	</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="#"><h2>database interface</h2></a>
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
  
  
	
	<br>
	
	<br>
	<br>
	
	
	<div class = "center">
	

	
	<div class="card-group">
  <div class="card" >
  <a href="form_modelparameters.php">
   <img class="card-img-top" src="parameters.png" alt="Card image">
  </a>
    <div class="card-body">
      <h4 class="card-title">modelparameters</h4>
      <p class="card-text">Click conduct search down below in order to browse the EvoNAPS Database for parameters for sequence evolution. Filter for specific models, rate heterogenity, alginment/trees specifications and more.</p>
	  <p class="card-text">If your search was sucessfull you can download the collected data for further usage!</p>
       <div class="card-footer"> <a href="form_modelparameters.php" class="btn btn-outline-primary" ><span class="bi-search"></span> Start your search!</a></div>
   
    </div>
  </div>
  <div class="card">
  <a href="form_tree.php">
   <img class="card-img-top" src="tree_new.png" alt="Card image">
  </a>
    <div class="card-body">
      <h4 class="card-title">trees</h4>
      <p class="card-text">Click conduct search down below in order to browse the EvoNAPS Database for species trees and their branches. Filter for differnt branch, alignment and tree specifications.</p>
	  <p class="card-text">If your search was sucessfull you can download the collected data of trees and their associated branches for further usage. </p>
	<div class="card-footer"> <a href="form_tree.php" class="btn btn-outline-primary"><span class="bi-search"></span> Start your search!</a></div>
    </div>
  </div>
  <div class="card">
  <a href="form_alignment.php">
   <img class="card-img-top" src="alignments_final.png" alt="Card image" >
    </a>
    <div class="card-body">
      <h4 class="card-title">alignments</h4>
     <p class="card-text">Click conduct search down below in order to browse the EvoNAPS Database for DNA or Protein alignments. Restrict your search by filtering with the alignment ID. </p>
	  <p class="card-text">If your search was sucessfull you can download the alignment as a FASTA file and use it for further research.  </p> 
	 <div class="card-footer"> <a href="form_alignment.php" class="btn btn-outline-primary"><span class="bi-search"></span> Start your search!</a></div>
   
    </div>
	</div>
  </div>
</div>
	

	
	
	
	
	<section class="damn">
  <!-- Footer -->
  <footer class="bg-secondary text-white text-center fixed-bottom" >
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
	
  
  
  </body>
  
  