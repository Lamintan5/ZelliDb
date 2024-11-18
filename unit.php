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
