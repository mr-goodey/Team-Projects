<?php
	$action = $_POST["action"];

	include "databaseConnect.php";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	if ($action == "Register") {
		$email = $_POST["email1"];
		$password = $_POST["password1"];
		$code = $_POST["code"];
		$sql = "SELECT * FROM users INNER JOIN invite ON users.email = invite.email WHERE users.email = '$email' AND users.pass IS NULL AND invite.inviteCode = '$code'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 1) {
			$sql = "UPDATE users SET users.pass = '$password' WHERE users.email = '$email'";
			$result = mysqli_query($conn, $sql);
			if ($result == true) {
				$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'mgr'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) == 1) {
					session_start();
					$_SESSION['email'] = $email;
					header("Location: manager.php");
					exit;
				} else {
					$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'emp'";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) == 1) {
						session_start();
						$_SESSION['email'] = $email;
						header("Location: employee.php");
						exit;
					} else {
						$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'tl'";
						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) == 1) {
							session_start();
							$_SESSION['email'] = $email;
							header("Location: teamLeader.php");
							exit;
						}
					}
				}
			}
		} else {
			echo "You cannot register";
		}
	} elseif ($action == "Login") {
		$email = $_POST["email2"];
		$password = $_POST["password2"];
		$sql = "SELECT * FROM users WHERE users.email = '$email' AND users.pass IS NULL";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) == 0) {
			$sql = "SELECT * FROM users WHERE users.email = '$email' AND users.pass = '$password'";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) == 0) {
				echo "Invalid login";
			} else {
				$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'mgr'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) == 1) {
					session_start();
					$_SESSION['email'] = $email;
					header("Location: manager.php");
					exit;
				} else {
					$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'emp'";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) == 1) {
						session_start();
						$_SESSION['email'] = $email;
						header("Location: employee.php");
						exit;
					} else {
						$sql = "SELECT * FROM users WHERE email = '$email' AND role = 'tl'";
						$result = mysqli_query($conn, $sql);
						if (mysqli_num_rows($result) == 1) {
							session_start();
							$_SESSION['email'] = $email;
							header("Location: teamLeader.php");
							exit;
						}
					}
				}
			}
		} else {
			echo "You cannot login";
		}
	}
?>