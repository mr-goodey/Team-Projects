<?php 
    include "databaseConnect.php";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $teamNo = $_GET['teamNo'];
    
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }

    $sql = "SELECT taskStatus, COUNT(taskStatus) AS 'noOfTasks'
    FROM tasks
    WHERE teamNo = {$teamNo}
    GROUP BY taskStatus
    ORDER BY CASE
        WHEN taskStatus = 'backlog' THEN 1
        WHEN taskStatus = 'pending' THEN 2
        WHEN taskStatus = 'progress' THEN 3
        WHEN taskStatus = 'complete' THEN 4
    END";
        

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