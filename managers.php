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

    if('ADD' == $action){
        $pid = $_POST['pid'];
        $eid = $_POST['eid'];
        $mid = $_POST['mid'];
        $duties = $_POST['duties'];

        $sql = "SELECT mid, eid FROM $table WHERE BINARY mid = '".$mid."' AND eid = '".$eid."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $sql = "INSERT INTO $table (pid, eid, mid, duties) VALUES('$pid','$eid','$mid','$duties')";
    
            $query = mysqli_query($db,$sql);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
            return;
        }
    }

    if('GET_ALL' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $query = "SELECT * FROM $table";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_CURRENT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    

    if('UPDATE_UNIT' == $action){
        $mid = $_POST['mid'];
        $duties = $_POST['duties'];
    
        $sql = "UPDATE $table SET duties = '$duties' WHERE mid = '$mid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
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
