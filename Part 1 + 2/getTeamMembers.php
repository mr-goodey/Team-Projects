<?php 
//This file is for getting the data from the database that will allow the Manager to 
//view everyone in a particular team after clicking on dashboard.php

   session_start();
   $email = $_SESSION['email'];

   $teamNo = $_GET['teamId'];

   include "databaseConnect.php";
   $conn = mysqli_connect($servername, $username, $password, $dbname);
   
   if(! $conn ) {
      die('Could not connect: ' . mysqli_connect_error());
   }

   $sql = "SELECT email 
            FROM teamAlloc
            WHERE teamNo = {$teamNo}";
      

   $sqlQueryData = array();

   $result = mysqli_query($conn,$sql);
   if (mysqli_num_rows($result) > 0) { 
    while($row = mysqli_fetch_array($result)) {
        $sqlQueryData[] = $row;
        }
   }
   echo json_encode($sqlQueryData);
   
   mysqli_close($conn);
?>