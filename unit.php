<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "units";
    $action = $_POST['action'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    $db = mysqli_connect('localhost','root','','zelli');
   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



?>
