<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$taskDescription = ($_POST["taskDesc"]);
$dueDate = ($_POST["dueDate"]);
$taskStatus = "backlog";
$teamNo = ($_POST["teamNo"]);
$teamEmail = ($_POST["email"]);
$manHours = ($_POST["manHours"]);

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query1 = "INSERT IGNORE INTO teamAlloc(teamNo, email) VALUES({$teamNo}, '{$teamEmail}')";
if(mysqli_query($conn, $query1)){
    $query2 = "INSERT INTO tasks (taskDesc, dueDate, taskStatus, email, teamNo, manHours)
    VALUES ('{$taskDescription}', '{$dueDate}', '{$taskStatus}', '{$teamEmail}', {$teamNo}, {$manHours});";
    if(mysqli_query($conn, $query2)){
        $insert_id = mysqli_insert_id($conn); // Return the insert ID of the second query
        echo $insert_id;
    } else {
        echo "Error1: " . $query2 . "<br>" . mysqli_error($conn);
    }
} else {
    echo "Error2: " . $query1 . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>
