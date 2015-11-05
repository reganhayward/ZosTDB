<?php
	//connect to the database
	$dbname ="";
	$dbuser ="";
	$dbpass ="";
	$dbhost ="";
	$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

	//Test if the the connection occured
	if(mysqli_connect_errno()) {
		die("The database connection failed: " . 
				mysqli_connect_error() . 							//Print the actual error
				" (" . mysqli_connect_errno() . ")"		//Print the actual error number
		 	 );
	}

?>