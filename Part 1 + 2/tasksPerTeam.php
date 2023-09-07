<?php 
   session_start();
   $email = $_SESSION['email'];

   include "databaseConnect.php";
   $conn = mysqli_connect($servername, $username, $password, $dbname);
   
   if(! $conn ) {
      die('Could not connect: ' . mysqli_connect_error());
   }

/*
   $sql = "SELECT team, u.email 
           FROM users u
           LEFT JOIN teams t
           ON u.email = t.email
           WHERE type = 'tl'
           ORDER BY team ASC";
*/

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