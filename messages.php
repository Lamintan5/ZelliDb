<?php
   include 'db_config.php';

   $table = "messages";

   $action = $_POST['action'];

    if('ADD' == $action){
        $image = $_FILES['image']['name'];
        $mid = $_POST['mid'];
        $uid = $_POST['uid'];
        $text = $_POST['text'];
        $type = $_POST['type'];
        $deleted = $_POST['deleted'];
        $seen = $_POST['seen'];
        $delivered = $_POST['delivered'];
        $checked = $_POST['checked'];
        $time = $_POST['time'];

        if (!empty($image)) {
            $imagePath = 'media/' . $image;
            $tmp_name = $_FILES['image']['tmp_name'];
            move_uploaded_file($tmp_name, $imagePath);
        }
        $insert = "INSERT INTO $table(mid,uid,text,image,type,deleted,seen,delivered,checked,time) 
        VALUES ('".$mid."','".$uid."','".$text."','".$image."','".$type."','".$deleted."','".$seen."','".$delivered."','".$checked."','".$time."')";
        $query = mysqli_query($db,$insert);
        if($query){
            echo 'Success';
        } else {
            echo 'Failed';
        }        
    }

    if('GET_CURRENT' == $action){
        if ($db->connect_errno) {
            die("Failed to connect to MySQL: " . $db->connect_error);
        }
        $uid = $_POST['uid'];
        $query = "SELECT * FROM $table WHERE FIND_IN_SET('" . $uid . "', uid)";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }


    if('UPDATE' == $action){
        $did = $_POST['did'];
        $duties = $_POST['duties'];  
        $sql = "UPDATE $table SET  duties = '$duties' WHERE did = '$did'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "failed";
        }
        $conn->close();
        return;
    }

    if('DELETE' == $action){
        $did = $_POST['did'];
        $sql = "DELETE FROM $table WHERE did = '$did'";
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
