<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "stars";
    $db = mysqli_connect('localhost','root','','zelli');
    $action = $_POST['action'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    

    if('ADD_MNTNCE' == $action){
        $sid = $_POST['sid'];
        $pid = $_POST['pid'];
        $rid = $_POST['rid'];
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];
        $rate = $_POST['rate'];
        $type = $_POST['type'];

        $sql = "SELECT  sid FROM $table WHERE sid = '".$sid."' AND uid = '".$uid."'  AND type = 'MNTNCE' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $sql = "INSERT INTO $table (sid, pid, rid, eid, uid, rate, type) 
            VALUES ('$sid','$pid','$rid','$eid','$uid', '$rate', '$type')";
    
            $query = mysqli_query($db,$sql);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
            return;
        }        
    }

    if('GET_CURRENT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE  eid = '".$eid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_MY' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $uid = $_POST['uid'];
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $uid . "', pid) OR uid = '".$uid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }




    if('GET_BY_TENANT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $tid = $_POST['tid'];
        $query = "SELECT * FROM $table WHERE tid = '".$tid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
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
        $id = $_POST['payid'];
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
