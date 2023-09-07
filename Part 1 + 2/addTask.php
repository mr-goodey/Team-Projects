
<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$taskDescription = ($_POST["taskDesc"]);
$dueDate = ($_POST["dueDate"]);
$taskStatus = "backlog";
$manHours = ($_POST["manHours"]);

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO tasks (taskDesc, dueDate, taskStatus, email, teamNo, manHours)
VALUES ('{$taskDescription}', '{$dueDate}', '{$taskStatus}', '{$email}', NULL, {$manHours})";


if(mysqli_query($conn,$sql)){
  echo mysqli_insert_id($conn);
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


?>