<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$teamNo = ($_GET["teamNo"]);

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT teamLeader
FROM teams
WHERE teamLeader = '{$email}'
AND teamNo = {$teamNo}";


$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

if(isset($row['teamLeader'])){
    echo $row['teamLeader'];
}else{
    echo "";
}

mysqli_close($conn);


?>