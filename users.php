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

    

    
    

   


    



    if('UPDATE' == $action){
        $uid = $_POST['uid'];
        $username = $_POST['username'];
        $first = $_POST['first'];
        $last = $_POST['last'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];   
        $password = md5($_POST['password']); 
        $status = $_POST['status'];

        $sql = "UPDATE $table SET username = '$username', first = '$first', last = '$last', email = '$email', phone = '$phone', password = '$password', status = '$status' WHERE uid = '$uid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE_PASS' == $action){
        $uid = $_POST['uid'];
        $password = md5($_POST['password']); 
       
        $sql = "UPDATE $table SET  password = '$password' WHERE uid = '$uid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE_TOKEN' == $action){
        $uid = $_POST['uid'];
        $token = $_POST['token']; 
       
        $sql = "UPDATE $table SET  token = '$token' WHERE uid = '$uid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
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