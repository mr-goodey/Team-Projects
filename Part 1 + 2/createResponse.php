
<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$postId = ($_POST["postId"]);
$message = ($_POST["message"]);

$response = array();

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO threads (postId, message, email)
VALUES ('{$postId}', '{$message}', '{$email}')";


if(mysqli_query($conn,$sql)){
  echo mysqli_insert_id($conn);
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


?>