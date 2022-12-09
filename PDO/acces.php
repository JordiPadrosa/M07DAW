<?php
    $mysqli = new mysqli("localhost","u_gringottsDB","i","gringottsDB");

	if ($mysqli -> connect_errno) {
	  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	  exit();
	}

	/* Query and get the results */
	$user = "' OR 1=1 #";
	$pass = "patata";
	$query = "SELECT * FROM users WHERE user='$user' AND password='$pass'";
	echo $query;
	$result = $mysqli -> query($query);

	/* Check results */
	$row = $result -> fetch_array(MYSQLI_ASSOC);
	if (!$row){
		die("Error authenticating");
	}
?>