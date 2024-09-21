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


    if('GET_ALL' == $action){
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

    if('GET_CURRENT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $id = $_POST['id'];
        $query = "SELECT * FROM $table WHERE  id = '".$id."'";
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
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $uid . "', pid) OR tid = '".$uid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_CURRENT_PROP' == $action){
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

    if('GET_BY_TENANT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $tid = $_POST['tid'];
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE  tid = '".$tid."' AND eid = '".$eid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_BY_TENANT_ONLY' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $tid = $_POST['tid'];
        $query = "SELECT * FROM $table WHERE  tid = '".$tid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }


    if('GET_BY_ENTITY' == $action){
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

    if('GET_PROP_UNIT_FLOOR' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
    
        $eid = $_POST['eid'];
        $floor = $_POST['floor'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND floor = '".$floor."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('ADD' == $action){
        $id = $_POST['id'];
        $pid = $_POST['pid'];
        $eid = $_POST['eid'];
        $tid = $_POST['tid'];
        $lid = $_POST['lid'];
        $title = $_POST['title'];
        $room = $_POST['room'];
        $floor = $_POST['floor'];
        $price = $_POST['price'];
        $deposit = $_POST['deposit'];
        $status = $_POST['status'];

        $sql = "SELECT id FROM $table WHERE id = '".$id."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count > 0){
            echo 'Exists';
        } else {
            $sql = "INSERT INTO $table (id, pid, eid, tid, lid, title, room, floor,  price, deposit, status, checked) 
            VALUES('$id','$pid','$eid','$tid', '$lid', '$title','$room','$floor','$price','$deposit','$status', 'true')";
            $query = mysqli_query($db,$sql);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
            return;
        }
    }

    if('UPDATE_PID' == $action){
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $sql = "UPDATE $table SET  pid = '$pid' WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('REMOVE_TID' == $action){
        $id = $_POST['id'];
        $sql = "UPDATE $table SET  tid = '' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }


    if('UPDATE_TID' == $action){
        $id = $_POST['id'];
        $tid = $_POST['tid'];
        $lid = $_POST['lid'];
        $sql = "UPDATE $table SET  tid = '$tid', lid = '$lid' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE' == $action){
        $id = $_POST['id'];
        $title = $_POST['title'];
        $room = $_POST['room'];
        $price = $_POST['price'];
        $deposit = $_POST['deposit'];

        $checkSql = "SELECT COUNT(*) as count FROM $table WHERE id = '$id'";
        $result = $conn->query($checkSql);
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            echo "Does not exist";
        } else {
            $sql = "UPDATE $table SET title = '$title', room = '$room', price = '$price', deposit = '$deposit' WHERE id = '$id'";
            if ($conn->query($sql) === TRUE) { 
                echo "success";
            } else {
                echo "error";
            }
        }


        $conn->close();
        return;
    }

    if('UPDATE_ALL_UNIT' == $action){
        $eid = $_POST['eid'];
        $tid = $_POST['tid'];
        $sql = "UPDATE $table SET tid = '$tid' WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $id = $_POST['id'];

        $checkSql = "SELECT COUNT(*) as count FROM $table WHERE id = '$id'";
        $result = $conn->query($checkSql);
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            echo "Does not exist";
        } else {
            $sql = "DELETE FROM $table WHERE id = '$id'";
            if ($conn->query($sql) === TRUE) {
                echo "success";
            } else {
                echo "error";
            }
        }

        $conn->close();
        return;
    }

    if('DELETE_ALL' == $action){
        $eid = $_POST['eid'];
        $sql = "DELETE FROM $table WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    } 
?>
