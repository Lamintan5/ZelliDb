<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "notifications";

    $action = $_POST['action'];
    $db = mysqli_connect('localhost','root','','zelli');
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if('GET_BY_ENTITY' == $action){
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

    if('GET_REQ' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND FIND_IN_SET('MNTNRQ', type)";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_MY_REQ' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $sid = $_POST['sid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND sid t= '".$sid."' AND FIND_IN_SET('MNTNRQ', type)";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }


    if ('UPDATE' == $action) {
        $nid = $_POST['nid'];
        $text = $_POST['text'];
        $type = $_POST['type'];
        $actions = $_POST['actions'];
        $sql = "UPDATE $table SET text = '$text', type = '$type', actions = '$actions', time = NOW() WHERE nid = '$nid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "failed";
        }
        $conn->close();
        return;
    }
    
    if('UPDATE_ACTION' == $action){
        $nid = $_POST['nid'];
        $actions = $_POST['actions'];
    
        $sql = "UPDATE $table SET actions = '$actions', time = NOW() WHERE nid = '$nid' ";
    
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "failed";
        }
        $conn->close();
        return;
    }

    if ('UPDATE_DELETE' == $action) {
        $nid = $_POST['nid'];
        $uid = $_POST['uid'];
    
        $sql = "SELECT `deleted` FROM $table WHERE nid = '$nid'";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $deleteField = $row['deleted'];
    
            if (empty($deleteField)) {
                $newDeleteField = $uid;
            } else {
                $uidsArray = explode(',', $deleteField);
                if (!in_array($uid, $uidsArray)) {
                    $newDeleteField = $deleteField . ',' . $uid;
                } else {
                    $newDeleteField = $deleteField;
                }
            }

            $updateSql = "UPDATE $table SET `deleted` = '$newDeleteField' WHERE nid = '$nid'";
            if ($conn->query($updateSql) === TRUE) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            echo "Does not exist";
        }
    
        $conn->close();
        return;
    }
    
    

    if ('UPDATE_SEEN' == $action) {
        $nid = $_POST['nid'];
        $uid = $_POST['uid'];
    
        $sql = "SELECT `seen` FROM $table WHERE nid = '$nid'";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $seenField = $row['seen'];
    
            if (empty($seenField)) {
                $newSeenField = $uid;
            } else {
                $uidsArray = explode(',', $seenField);
                if (!in_array($uid, $uidsArray)) {
                    $newSeenField = $seenField . ',' . $uid;
                } else {
                    $newSeenField = $seenField;
                }
            }

            $updateSql = "UPDATE $table SET `seen` = '$newSeenField' WHERE nid = '$nid'";
            if ($conn->query($updateSql) === TRUE) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            echo "Does not exist";
        }
    
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $nid = $_POST['nid'];
        $sql = "DELETE FROM $table WHERE nid = '$nid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "failed";
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
