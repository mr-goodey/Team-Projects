<?php

$taskId = $_POST["taskId"];

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "DELETE FROM tasks 
        WHERE taskId = {$taskId}
        AND taskStatus = 'complete'";

if(mysqli_query($conn,$sql)){
    if(mysqli_affected_rows($conn)>=1){
      echo $taskId;
    }else{
      echo "No rows deleted";
    }
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }



mysqli_close($conn);


?>