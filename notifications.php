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
    if ('ADD' == $action) {
        $nid = $_POST['nid'];
        $sid = $_POST['sid'];
        $rid = $_POST['rid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $text = $_POST['text'];
        $message = $_POST['message'];
        $actions = $_POST['actions'];
        $type = $_POST['type'];
    
        // Check for existence of record
        $sql = "SELECT sid, rid, eid, text, actions, type FROM $table WHERE sid = '".$sid."' AND rid = '".$rid."' AND eid = '".$eid."' 
                AND text = '".$text."' AND actions = '".$actions."' AND type = '".$type."'";
        $result = mysqli_query($db, $sql);
        $count = mysqli_num_rows($result);
    
        if ($count == 1) {
            echo 'Exists';
        } else {
            // Handle image upload
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $imagePath = 'uploads/' . $image;
                move_uploaded_file($tmp_name, $imagePath);
            } else {
                // If no file was uploaded, set $image to an empty string or default value
                $image = "";
            }
    
            // Insert new record
            $insert = "INSERT INTO $table (nid, sid, rid, eid, pid, text, seen, message, actions, type, deleted, checked, image) 
                       VALUES ('".$nid."','".$sid."','".$rid."','".$eid."','".$pid."','".$text."','','".$message."','".$actions."','".$type."','','true', '".$image."')";
            $query = mysqli_query($db, $insert);
    
            if ($query) {
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }
    }
    

    if('ADD_MULTI' == $action){
        $nid = $_POST['nid'];
        $sid = $_POST['sid'];
        $rid = $_POST['rid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $text = $_POST['text'];
        $seen = $_POST['seen'];
        $actions = $_POST['actions'];
        $type = $_POST['type'];
        $message = $_POST['message'];
        $insert = "INSERT INTO $table(nid,sid,rid,eid,pid,text,seen,actions,type,message) 
        VALUES ('".$nid."','".$sid."','".$rid."','".$eid."','".$pid."','".$text."','".$seen."','".$actions."','".$type."','".$message."')";
        $query = mysqli_query($db,$insert);
        if($query){
            echo 'Success';
        } else {
            echo 'Failed';
        }        
    }


    if('REQUEST_TENANT' == $action){
        $nid = $_POST['nid'];
        $sid = $_POST['sid'];
        $rid = $_POST['rid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $text = $_POST['text'];
        $seen = $_POST['seen'];
        $actions = $_POST['actions'];
        $type = $_POST['type'];
        $message = $_POST['message'];

        $sql = "SELECT rid, eid, text FROM $table WHERE BINARY rid = '".$rid."' AND eid = '".$eid."' AND text = '".$text."' AND actions = '' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $insert = "INSERT INTO $table(nid,sid,rid,eid,pid,text,seen,actions,type,message) 
            VALUES ('".$nid."','".$sid."','".$rid."','".$eid."','".$pid."','".$text."','".$seen."','".$actions."','".$type."','".$message."')";
            $query = mysqli_query($db,$insert);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }         
    }


    if('TENANT_REQUEST' == $action){
        $nid = $_POST['nid'];
        $sid = $_POST['sid'];
        $rid = $_POST['rid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $text = $_POST['text'];
        $seen = $_POST['seen'];
        $actions = $_POST['actions'];
        $type = $_POST['type'];
        $message = $_POST['message'];

        $sql = "SELECT rid, eid, text FROM $table WHERE BINARY sid = '".$sid."' AND eid = '".$eid."' AND text = '".$text."' AND actions = '' ";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $insert = "INSERT INTO $table(nid,sid,rid,eid,pid,text,seen,actions,type,message) 
            VALUES ('".$nid."','".$sid."','".$rid."','".$eid."','".$pid."','".$text."','".$seen."','".$actions."','".$type."','".$message."')";
            $query = mysqli_query($db,$insert);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }         
    }


    if('GET' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $nid = $_POST['nid'];
        $query = "SELECT * FROM $table WHERE nid = '".$nid."'";
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
        $rid = $_POST['rid'];
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $rid . "', rid)";
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
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $uid . "', rid) OR FIND_IN_SET('" . $uid . "', pid) OR sid = '".$uid."' AND NOT FIND_IN_SET('" . $uid . "', deleted) ";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }


    if('GET_UNSEEN' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $rid = $_POST['rid'];
        $query = "SELECT * FROM $table WHERE rid = '".$rid."' AND seen = ''";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
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
