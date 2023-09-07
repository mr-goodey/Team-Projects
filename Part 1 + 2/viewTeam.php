
<?php
/*This module is for displaying the teams of team leaders and employees NOT MANAGERS.*/

session_start();
$email = $_SESSION['email'];

include "databaseConnect.php";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if(! $conn ) {
   die('Could not connect: ' . mysqli_connect_error());
}

$sql = "SELECT email, teamNo
        FROM teamAlloc
        WHERE teamNo IN (SELECT teamNo FROM teamAlloc WHERE email = '{$email}')";


$sqlQueryData = array();

$result = mysqli_query($conn,$sql);
if (mysqli_num_rows($result) > 0) { 
 while($row = mysqli_fetch_array($result)) {
     $sqlQueryData[] = $row;
     }
}
else {
        echo "No Data";
}
echo json_encode($sqlQueryData);
mysqli_close($conn);

?>