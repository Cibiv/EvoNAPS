<?php
/*
		$hostname = xxxxx;
		$dbname= xxxxx;
		$username=xxxxx;
		$password=xxxxx;
		$dsn= "mysql:host=$hostname;dbname=$dbname";
		
		*/ 
		
		$hostname = 'localhost';
		$dbname= 'evonaps_try_2';
		$username='root';
		$password='';
		$dsn= "mysql:host=$hostname;dbname=$dbname";
		try {
			
		$connect = new PDO($dsn, $username, $password);
		 //Close Connection
		 //$connect = null;
		// set the PDO error mode to exception
		// $connect->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false); //Standard in PHP V.8 or higher unbuffered query setting
		
			//echo "Connected successfully";
					
			}catch(PDOException $e) {
				
			echo "Connection failed: " . $e->getMessage();
			}

?>
