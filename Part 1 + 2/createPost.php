<?php
	include "databaseConnect.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	$message = $_POST["message"];
	$topic = $_POST["topic"];
	session_start();
	$email = $_SESSION['email'];
			
	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());}
				

	$sql = "INSERT INTO messages (topic, post, email) 
	VALUES ('{$topic}', '{$message}', '{$email}')";
				
	if(mysqli_query($conn,$sql)){
		echo mysqli_insert_id($conn);
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>
