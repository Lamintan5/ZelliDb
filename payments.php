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

    
    if('ADD' == $action){
        $payid = $_POST['payid'];
        $pid = $_POST['pid'];
        $admin = $_POST['admin'];
        $tid = $_POST['tid'];
        $lid = $_POST['lid'];
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];
        $payerid = $_POST['payerid'];
        $amount = $_POST['amount'];
        $balance = $_POST['balance'];
        $method = $_POST['method'];
        $type = $_POST['type'];
        $time = $_POST['time'];
        $sql = "INSERT INTO $table (payid, pid,admin, tid,lid, eid, uid, payerid, amount, balance, method, type, time, checked) 
        VALUES ('$payid','$pid','$admin','$tid','$lid','$eid','$uid', '$payerid', '$amount', '$balance', '$method', '$type', '$time', 'true')";
        $query = mysqli_query($db,$sql);
        if($query){
            echo 'Success';
        } else {
            echo 'Failed';
        }
        return;
    }

    if('GET_CURRENT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $pid = $_POST['pid'];
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $pid . "', pid)";
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
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $uid . "', pid) OR FIND_IN_SET('" . $uid . "', tid) OR payerid = '".$uid."'";
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
