<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$teamNo = $_GET["teamNo"];

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT taskDesc
FROM tasks
WHERE email = '{$email}'
AND teamNo = {$teamNo}
AND taskStatus <> 'complete'";

$result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) > 0) { 
    echo 1;
}else{
    echo 0;
}

mysqli_close($conn);


?>