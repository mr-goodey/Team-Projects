<?php 
    session_start();
    $email = $_SESSION['email'];
    $_SESSION['email'] = $email;

    include "databaseConnect.php";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    
    $sql = "SELECT teamNo
    FROM teamAlloc
    WHERE email = '{$email}'
    UNION
    SELECT teamNo
    FROM teams
    WHERE teamLeader = '{$email}'
    ORDER BY teamNo ASC";

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
