<?php

$taskId = ($_POST["taskId"]);
$newDesc = ($_POST["newDesc"]);

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE tasks
SET taskDesc = '{$newDesc}'
WHERE taskId = {$taskId}";

if(mysqli_query($conn,$sql)){
    echo "Task Marked as completed ";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

mysqli_close($conn);
?>