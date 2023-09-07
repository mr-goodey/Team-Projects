<?php 
    session_start();
    $email = $_SESSION['email'];
    $_SESSION['email'] = $email;

    $topic=$_GET["topic"];

    include "databaseConnect.php";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if(! $conn ) {
        die('Could not connect: ' . mysqli_connect_error());
    }

    if($topic==""){
        $sql = "SELECT email, topic, post FROM messages";  
    }else{
        $sql = "SELECT email, topic, post FROM messages WHERE topic LIKE '{$topic}%'";
    }

    $sqlQueryData = array();

    $result = mysqli_query($conn,$sql);

    if (!$result) {
        $error_response = array(
            "error" => array(
                "code" => "QUERY_ERROR",
                "message" => mysqli_error($conn)
            )
        );
        echo json_encode($error_response);
        exit;
    }

    if (mysqli_num_rows($result) > 0) { 
        while($row = mysqli_fetch_array($result)) {
            $sqlQueryData[] = $row;
        }
    }

    echo json_encode($sqlQueryData);
    
    mysqli_close($conn);
?>
