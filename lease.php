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
