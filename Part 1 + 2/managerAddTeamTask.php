<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$taskDescription = ($_POST["taskDesc"]);
$dueDate = ($_POST["dueDate"]);
$taskStatus = "backlog";
$teamEmail = ($_POST["email"]);
$teamNo = ($_POST["team"]);
$manHours = ($_POST["manHours"]);

$response = array();


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT IGNORE INTO teams (teamNo, teamLeader)
VALUES ('{$teamNo}', '{$teamEmail}');";

if(mysqli_query($conn,$sql)){
    if(mysqli_affected_rows($conn)>0){ //theyre a team leader
        array_push($response,1);
    }else{ //theyre not a team leader
        array_push($response,0);
    }
}

$sql = "INSERT IGNORE INTO teamAlloc(teamNo ,email)
        VALUES ('{$teamNo}', '{$teamEmail}');";

$sql .= "INSERT INTO tasks (taskDesc, dueDate, taskStatus, email, teamNo, manHours)
        VALUES ('{$taskDescription}', '{$dueDate}', '{$taskStatus}', '{$teamEmail}', {$teamNo}, {$manHours});";

if(mysqli_multi_query($conn,$sql)){
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));
    
    $taskId = mysqli_insert_id($conn);
    array_push($response,$taskId);
    echo json_encode($response);
} else {
    array_push($response, 1);
    array_push($response, mysqli_error($conn));
    echo json_encode($response);
}

mysqli_close($conn);
?>
