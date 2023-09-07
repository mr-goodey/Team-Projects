<?php
	include "databaseConnect.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	$teamLeader = $_POST["teamLeader"];
	$teamNo = $_POST["teamNo"];
	session_start();
	$email = $_SESSION['email'];
			
	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());}
				

	$sql = "INSERT IGNORE INTO teams (teamNo, teamLeader) 
	VALUES ({$teamNo}, '{$teamLeader}')";
				
	if(mysqli_query($conn,$sql)){
		echo $teamNo;
		} else {
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>
