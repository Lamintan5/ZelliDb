<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "lease";

    $action = $_POST['action'];

    $db = mysqli_connect('localhost','root','','zelli');
    $conn = new mysqli($servername, $username, $password, $dbname);
   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
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

    if('GET_PREVIOUS_TENANTS' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $uid = $_POST['uid'];
        $query = "SELECT * FROM $table WHERE uid = '".$uid."' AND end != ''";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_CURRENT_BY_ENTITY' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND end = ''";
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
        $pid = $_POST['pid'];
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $pid . "', pid)";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);    
    }

    if('GET_PREVIOUS_BY_ENTITY' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND end != ''";
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
        $query = "SELECT * FROM $table WHERE eid = '".$eid."' AND end != ''";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_ONE' == $action){
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
        $lid = $_POST['lid'];
        $end = $_POST['end'];
        $sql = "UPDATE $table SET  end = '$end' WHERE lid = '$lid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE_DETAILS' == $action){
        $lid = $_POST['lid'];
        $rent = $_POST['rent'];
        $deposit = $_POST['deposit'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $sql = "UPDATE $table SET rent = '$rent', deposit = '$deposit', start = '$start',  end = '$end' WHERE lid = '$lid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE_ALL_TENANTS' == $action){
        $eid = $_POST['eid'];
        $end = $_POST['end'];
        $sql = "UPDATE $table SET end = '$end' WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error2";
        }
        $conn->close();
        return;
    }

    if (isset($action) && $action === 'UPDATE_COTID') {
        $lid = $_POST['lid'];
        $tid = $_POST['tid'];
    
        $lid = $conn->real_escape_string($lid);
        $tid = $conn->real_escape_string($tid);
    
        $sql = "SELECT `ctid` FROM $table WHERE lid = '$lid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ctidField = $row['ctid'];
    
            if (empty($ctidField)) {
                $newCtidField = $tid;
            } else {
                $ctidsArray = explode(',', $ctidField);

                if (!in_array($tid, $ctidsArray)) {
                    $newCtidField = $ctidField . ',' . $tid;
                } else {
                    $newCtidField = $ctidField;
                }
            }
    
            $updateSql = "UPDATE $table SET `ctid` = '$newCtidField' WHERE lid = '$lid'";
            if ($conn->query($updateSql) === TRUE) {
                $updSql = "UPDATE payments SET `tid` = '$newCtidField' WHERE lid = '$lid'";
                if ($conn->query($updSql) === TRUE) {
                    echo "success";
                } else {
                    echo "error" . $conn->error;
                }
            } else {
                echo "error" . $conn->error;
            }
        } else {
            echo "Does not exist";
        }
    
        $conn->close();
    }
    

    if (isset($action) && $action === 'REMOVE_COTID') {
        $lid = $_POST['lid'];
        $ctid = $_POST['tid'];
    
        $lid = $conn->real_escape_string($lid);
        $ctid = $conn->real_escape_string($ctid);
    
        $sql = "SELECT `ctid` FROM $table WHERE lid = '$lid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ctidField = $row['ctid'];
    
            if (!empty($ctidField)) {
                $ctidsArray = explode(',', $ctidField);
                $ctidsArray = array_filter($ctidsArray, function ($item) use ($ctid) {
                    return $item != $ctid;
                });
                $newCtidField = implode(',', $ctidsArray);
            } else {
                $newCtidField = ''; 
            }
    
            $updateSql = "UPDATE $table SET `ctid` = '$newCtidField' WHERE lid = '$lid'";
            if ($conn->query($updateSql) === TRUE) {
                $updSql = "UPDATE payments SET `tid` = '$newCtidField' WHERE lid = '$lid'";
                if ($conn->query($updSql) === TRUE) {
                    echo "success";
                } else {
                    echo "error" . $conn->error;
                }
            } else {
                echo "error" . $conn->error;
            }
        } else {
            echo "Does not exist";
        }
        $conn->close();
        return;
    }
    

    if('DELETE' == $action){
        $id = $_POST['tid'];
        $sql = "DELETE FROM $table WHERE tid = '$tid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
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
