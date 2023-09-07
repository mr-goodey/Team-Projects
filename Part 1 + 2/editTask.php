<?php

$taskId = ($_POST["taskId"]);
$dueDate = ($_POST["dueDate"]);
$taskDesc = ($_POST["taskDesc"]);
$manHours = ($_POST["manHours"]);

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE tasks ";

if($taskDesc != ''){
  $sql.="SET taskDesc = '{$taskDesc}' ";
  if($dueDate !=''){
    $sql .= ", dueDate = '{$dueDate}'";
    if($manHours !=0){
      $sql.=", manHours = {$manHours} ";
    }
  }
}else{
  if($dueDate!= ''){
    $sql .="SET dueDate = '{$dueDate}' ";
    if($manHours!= ''){
      $sql.= ", manHours = {$manHours}";
    }
  }else{
    if($manHours != ''){
      $sql .= "SET manHours = {$manHours} ";
    }
  }
}

$sql.= "WHERE taskId = {$taskId}";

if(mysqli_query($conn,$sql)){
    echo "Task Marked as completed ".$sql;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

mysqli_close($conn);
?>