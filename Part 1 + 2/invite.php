<?php
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	function endsWith($string, $endString)
	{
		$len = strlen($endString);
		if ($len == 0) {
			return true;
		}
		return (substr($string, -$len) === $endString);
	}
	include "databaseConnect.php";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
	}
	$email = $_POST["email1"];
	if (endsWith($email,'@make-it-all.co.uk')) {
		$type = $_POST["type1"];
		$sql = "SELECT * FROM invite WHERE email = '$email'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
			$code = generateRandomString();
			$sql = "INSERT INTO invite (email, inviteCode) VALUES ('$email', '$code')";
			$result = mysqli_query($conn, $sql);
			$sql = "INSERT INTO users (email, role) VALUES ('$email', '$type')";
			$result = mysqli_query($conn, $sql);
			$response_array['status'] = 'correct'; 
		} else {
			$response_array['status'] = 'incorrect'; 
		}
		header('Content-type: application/json');
		echo json_encode($response_array);
	} else {
		$response_array['status'] = 'incorrect';
		header('Content-type: application/json');
		echo json_encode($response_array);
	}
?>