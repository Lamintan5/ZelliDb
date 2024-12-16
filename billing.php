<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "billing";

    $action = $_POST['action'];
    $db = mysqli_connect('localhost','root','','zelli');
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if('ADD' == $action){
        $bid = $_POST['bid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $bill = $_POST['bill'];
        $businessno = $_POST['businessno'];
        $accountno = $_POST['accountno'];
        $type = $_POST['type'];
        $account = $_POST['account'];

        $sql = "SELECT bid FROM $table WHERE bid = '".$bid."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $insert = "INSERT INTO $table(bid,eid,pid,bill,businessno,accountno,type,account,checked)
            VALUES ('".$bid."','".$eid."','".$pid."','".$bill."', '".$businessno."', '".$accountno."' ,'".$type."','".$account."','true')";
            $query = mysqli_query($db,$insert);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }
    }

    if('GET_MY' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $uid = $_POST['uid'];
        $query = "SELECT * FROM $table WHERE  FIND_IN_SET('" . $uid . "', pid)";
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


    if (isset($action) && $action === 'UPDATE_ACCESS') {
        $bid = $_POST['bid'];
        $access = $_POST['access'];
    
        $bid = $conn->real_escape_string($bid);
        $access = $conn->real_escape_string($access);
    
        $sql = "SELECT `access` FROM $table WHERE bid = '$bid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $accField = $row['access'];
    
            if (empty($accField)) {
                $newAccField = $access;
            } else {
                $accsArray = explode('*', $accField);

                if (!in_array($access, $accsArray)) {
                    $newAccField = $accField . '*' . $access;
                } else {
                    $newAccField = $accField;
                }
            }
    
            $updateSql = "UPDATE $table SET `access` = '$newAccField' WHERE bid = '$bid'";
            if ($conn->query($updateSql) === TRUE) {
                echo "success";
            } else {
                echo "failed" . $conn->error;
            }
        } else {
            echo "Does not exist";
        }
    
        $conn->close();
    }

    

    if('UPDATE' == $action){
        $bid = $_POST['bid'];
        $businessno = $_POST['businessno'];  
        $accountno = $_POST['accountno'];
        $account = $_POST['account'];
        $type = $_POST['type'];    
        $sql = "UPDATE $table SET  businessno = '$businessno', accountno = '$accountno', account = '$account', type = '$type'  WHERE bid = '$bid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "failed";
        }
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $bid = $_POST['bid'];
        $sql = "DELETE FROM $table WHERE bid = '$bid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "failed";
        }
        $conn->close();
        return;
    }

?>
