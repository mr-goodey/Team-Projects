<?php 
//gets every user thats an employee

   session_start();
   $email = $_SESSION['email'];

   include "databaseConnect.php";
   $conn = mysqli_connect($servername, $username, $password, $dbname);
   
   if(! $conn ) {
      die('Could not connect: ' . mysqli_connect_error());
   }

   $sql = "SELECT email 
            FROM users
            WHERE role = 'emp'
            AND role <> '{$email}'
            AND pass IS NOT NULL";
      

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