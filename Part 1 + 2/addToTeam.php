
<?php
session_start();
$email = $_SESSION['email'];
$_SESSION['email'] = $email;
include "databaseConnect.php";

$teamNo = $_POST["teamNo"];
$teamMember = $_POST["email"];

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT IGNORE INTO teamAlloc(teamNo, email)
VALUES ({$teamNo}, '{$teamMember}')";

$result = mysqli_query($conn, $sql);

  if(mysqli_query($conn,$sql)){
    echo mysqli_insert_id($conn);
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }

mysqli_close($conn);



?>