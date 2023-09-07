<?php

$teamNo = $_POST["teamNo"];
$email = $_POST["email"];

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "DELETE FROM teamAlloc
        WHERE teamNo = {$teamNo}
        AND email NOT IN(SELECT email
        FROM tasks
        WHERE teamNo = {$teamNo}
        AND taskStatus <> 'complete')";

if(mysqli_query($conn,$sql)){
    if(mysqli_affected_rows($conn)>=1){
        echo $teamNo;
    }else{
      echo "Team member not deleted";
    }
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }



mysqli_close($conn);


?>