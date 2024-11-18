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
        $tid = $_POST['tid'];

        $sql = "SELECT `tid` FROM $table WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tidField = $row['tid'];
    
            if (!empty($tidField)) {
                $tidsArray = explode(',', $tidField);
                $tidsArray = array_filter($tidsArray, function($item) use ($tid) {
                    return $item != $tid;
                });
                $newTidField = implode(',', $tidsArray);
            } else {
                $newTidField = '';
            }
    
            $updateSql = "UPDATE $table SET `tid` = '$newTidField' WHERE id = '$id'";
                if ($conn->query($updateSql) !== TRUE) {
                    echo "error";
                    $conn->close();
                    return;
                } else {
                    echo "success";
                }
        } else {
            echo "Does not exist";
        }

        $conn->close();
        return;
    }


    if (isset($action) && $action === 'UPDATE_TID') {
        $id = $_POST['id'];
        $tid = $_POST['tid'];
        $lid = $_POST['lid'];
    
        $id = $conn->real_escape_string($id);
        $tid = $conn->real_escape_string($tid);
        $lid = $conn->real_escape_string($lid);
    
        if ($tid === "" && $lid === "") {
            $updateSql = "UPDATE $table SET `tid` = '', `lid` = '' WHERE id = '$id'";
            if ($conn->query($updateSql) === TRUE) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            $sql = "SELECT `tid` FROM $table WHERE id = '$id'";
            $result = $conn->query($sql);
    
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tidField = $row['tid'];
    
                if (empty($tidField)) {
                    $newTidField = $tid;
                } else {
                    $tidsArray = explode(',', $tidField);
                    if (!in_array($tid, $tidsArray)) {
                        $newTidField = $tidField . ',' . $tid;
                    } else {
                        $newTidField = $tidField;
                    }
                }
    
                $updateSql = "UPDATE $table SET `tid` = '$newTidField', `lid` = '$lid' WHERE id = '$id'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "success";
                } else {
                    echo "error";
                }
            } else {
                echo "Does not exist";
            }
        }
    
        // Close the connection
        $conn->close();
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
