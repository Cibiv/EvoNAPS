<?php
/*
	Before uploading change here to make it work on Sanger server
		$hostname = xxxxx;
		$dbname= xxxxx;
		$username=xxxxx;
		$password=xxxxx;
		$dsn= "mysql:host=$hostname;dbname=$dbname";
		
		*/ 
		
		// For testing purposes this credentials are going to be deleted
		$hostname = 'localhost';
		$dbname= 'evonaps_try_2';
		$username='root';
		$password='';
		$dsn= "mysql:host=$hostname;dbname=$dbname";

			
	try {
		$connect = new PDO($dsn, $username, $password);
			}catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			}

?>
