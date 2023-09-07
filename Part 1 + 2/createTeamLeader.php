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
	$email = $_POST["email4"];
	if (endsWith($email, '@make-it-all.co.uk')) {
		$sql = "SELECT * FROM invite WHERE email = '$email'";
		$result = mysqli_query($conn, $sql);
		$team = $_POST["team4"];
		if (mysqli_num_rows($result) == 1) {
			$sql = "SELECT * FROM users WHERE email = '$email' AND type = 'emp'";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 1) {
				$sql = "SELECT * FROM teams2 WHERE email = '$email' AND team = '$team' AND leader = '1'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) == 0) {
					$sql = "SELECT * FROM teams2 WHERE team = '$team' AND leader = '1'";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) == 0) {
						$sql = "SELECT * FROM teams2 WHERE email = '$email' AND team = '$team'";
						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) == 1) {
							$sql = "UPDATE teams2 SET leader = '1' WHERE email = '$email' AND team = '$team'";
							$result = mysqli_query($conn, $sql);
							$response_array['status'] = 'complete';
							header('Content-type: application/json');
							echo json_encode($response_array);
						} else {
							$response_array['status'] = 'fault6';
							header('Content-type: application/json');
							echo json_encode($response_array);
						}
					} else {
						$response_array['status'] = 'fault5';
						header('Content-type: application/json');
						echo json_encode($response_array);
					}
				} else {
					$response_array['status'] = 'fault4';
					header('Content-type: application/json');
					echo json_encode($response_array);
				}
			} else {
				$response_array['status'] = 'fault3';
				header('Content-type: application/json');
				echo json_encode($response_array);
			}
		} else {
			$response_array['status'] = 'fault2';
			header('Content-type: application/json');
			echo json_encode($response_array);
		}
	} else {
		$response_array['status'] = 'fault1';
		header('Content-type: application/json');
		echo json_encode($response_array);
	}
?>