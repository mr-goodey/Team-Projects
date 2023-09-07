<?php
include "databaseConnect.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	session_start();
	$email = $_SESSION['email'];
			
	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
	}
				

	$sql = "SELECT email, topic, postId FROM posts";
	$result = mysqli_query($conn, $sql);
			
	$sqlQueryData = array();

	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) { 
	 	while($row = mysqli_fetch_array($result)) {
			$sqlQueryData[] = $row;
		}
	}
	echo json_encode($sqlQueryData);
				
	mysqli_close($conn);
?>
