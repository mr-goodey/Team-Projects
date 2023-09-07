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
	$email = $_POST["email3"];
	if (endsWith($email,'@make-it-all.co.uk')) {
		$sql = "SELECT * FROM invite WHERE email = '$email'";
		$result = mysqli_query($conn, $sql);
		$team = $_POST["team3"];
		if (mysqli_num_rows($result) == 1) {
			$sql = "SELECT * FROM teams WHERE email = '$email' AND team = '$team'";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 1) {
				$sql = "SELECT * FROM users WHERE email = '$email' AND type = 'tl'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "DELETE FROM teams WHERE email = '$email' AND team = '$team'";
					$result = mysqli_query($conn, $sql);
					$response_array['status'] = 'suc';
					header('Content-type: application/json');
					echo json_encode($response_array);
				} else {
					$response_array['status'] = 'err4';
					header('Content-type: application/json');
					echo json_encode($response_array);
				}
			} else {
				$response_array['status'] = 'err3';
				header('Content-type: application/json');
				echo json_encode($response_array);
			}
		} else {
			$response_array['status'] = 'err2';
			header('Content-type: application/json');
			echo json_encode($response_array);
		}
	} else {
		$response_array['status'] = 'err1';
		header('Content-type: application/json');
		echo json_encode($response_array);
	}
?>