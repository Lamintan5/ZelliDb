<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "managers";

    $action = $_POST['action'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    $db = mysqli_connect('localhost','root','','zelli');
   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if('DELETE' == $action){
        $mid = $_POST['mid'];
        $sql = "DELETE FROM $table WHERE mid = '$mid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }    
?>
