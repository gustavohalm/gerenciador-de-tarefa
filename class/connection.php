<?php
	$server = "localhost";
	$user   = "root";
	$password = "";
	$db = "tarefas";
	
	$conn  = mysqli_connect($server, $user, $password, $db);

	if (!$conn) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
	else
	{
		mysqli_query($conn, "SET NAMES 'utf8'");
        mysqli_query($conn, 'SET character_set_connection=utf8');
        mysqli_query($conn, 'SET character_set_client=utf8');
        mysqli_query($conn, 'SET character_set_results=utf8');
	}
?>