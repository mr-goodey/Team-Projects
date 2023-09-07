<?php
	function endsWith($string, $endString)
	{
		$len = strlen($endString);
		if ($len == 0) {
			return true;
		}
		return (substr($string, -$len) === $endString);
	}
	$servername = "localhost";
	$username = "coeg4";
	$password = "qnZEzwODg0";
	$dbname = "coeg4";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
	}
	$email = $_POST["email5"];
	if (endsWith($email, '@make-it-all.co.uk')) {
		$sql = "SELECT * FROM invite WHERE email = '$email'";
		$result = mysqli_query($conn, $sql);
		$team = $_POST["team5"];
		if (mysqli_num_rows($result) == 1) {
			$sql = "SELECT * FROM teams2 WHERE email = '$email' AND team = '$team' AND leader = '1'";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 1) {
				$sql = "UPDATE teams2 SET leader = '0' WHERE email = '$email' AND team = '$team'";
				$result = mysqli_query($conn, $sql);
				$response_array['status'] = 'correct';
				header('Content-type: application/json');
				echo json_encode($response_array);
			} else {
				$response_array['status'] = 'incorrect3';
				header('Content-type: application/json');
				echo json_encode($response_array);
			}
		} else {
			$response_array['status'] = 'incorrect2';
			header('Content-type: application/json');
			echo json_encode($response_array);
		}
	} else {
		$response_array['status'] = 'incorrect1';
		header('Content-type: application/json');
		echo json_encode($response_array);
	}
?>