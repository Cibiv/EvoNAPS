<?php
/*
		$hostname = 'localhost';
		$dbname= 'try5';
		$username='root';
		$password='';
		$dsn= "mysql:host=$hostname;dbname=$dbname";

		
		*/ 
		
		// For testing purposes this credentials are going to be deleted
		
		$hostname = 'localhost';
		$dbname= 'try5';
		$username='root';
		$password='';
		$dsn= "mysql:host=$hostname;dbname=$dbname";

			
	try {
		$connect = new PDO($dsn, $username, $password);
			}catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			}

?>
