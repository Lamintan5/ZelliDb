<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "payments";
    $db = mysqli_connect('localhost','root','','zelli');
    $action = $_POST['action'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    

    if('UPDATE' == $action){
        $payid = $_POST['payid'];
        $amount = $_POST['amount'];
        $type = $_POST['type'];
        $time = $_POST['time'];
        $sql = "UPDATE $table SET  amount = '$amount', type = '$type', time = '$time' WHERE payid = '$payid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error2";
        }
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $payid = $_POST['payid'];
        $sql = "DELETE FROM $table WHERE payid = '$payid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }
    
?>
