<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "users";

    $action = $_POST['action'];
    $db = mysqli_connect('localhost','root','','zelli');
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    

    
    

   


    




    if('UPDATE_EMAIL' == $action){
        $uid = $_POST['uid'];
        $email = $_POST['email']; 
       
        $sql = "SELECT email FROM $table WHERE BINARY email = '".$email."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1) {
            echo 'Exists';
        } else {
            $sql = "UPDATE $table SET  email = '$email' WHERE uid = '$uid'";
            if ($conn->query($sql) === TRUE) { 
                echo "success";
            } else {
                echo "error";
            }
        }
        
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $uid = $_POST['uid'];
        $sql = "DELETE FROM $table WHERE uid = '$uid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }
    
?>