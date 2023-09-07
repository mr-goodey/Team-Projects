<?php

$teamId = $_GET["teamId"];

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "DELETE FROM teams 
        WHERE teamNo = {$teamId}
        AND teamNo NOT IN(SELECT teamNo 
        FROM tasks
        WHERE teamNo = {$teamId}
        AND taskStatus <> 'complete')";

if(mysqli_query($conn,$sql)){
    if(mysqli_affected_rows($conn)>=1){
      echo $teamId;
    }else{
      echo "No rows deleted";
    }
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }



mysqli_close($conn);


?>