<?php
    include 'db_config.php';

    $table = "reviews";

    $action = $_POST['action'];

    if ('ADD' == $action) {
        // Sanitize and validate input values
        $rid = mysqli_real_escape_string($db, $_POST['rid']);
        $eid = mysqli_real_escape_string($db, $_POST['eid']);
        $pid = mysqli_real_escape_string($db, $_POST['pid']);
        $sid = mysqli_real_escape_string($db, $_POST['sid']);
        $uid = mysqli_real_escape_string($db, $_POST['uid']);
        $message = mysqli_real_escape_string($db, $_POST['message']);
        $star = mysqli_real_escape_string($db, $_POST['star']);
    
        // Handle image upload if it exists
        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $imagePath = 'uploads/' . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        } else {
            $image = '';
        }
    
        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO $table (rid, eid, pid, sid, uid, message, image, star) 
                VALUES ('$rid', '$eid', '$pid', '$sid', '$uid', '$message', '$image', '$star')";
        
        $query = mysqli_query($db,$sql);
        if($query){
            echo 'Success';
        } else {
            echo 'Failed';
        }
    
        return;
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

    

    if('UPDATE_UNIT' == $action){
        $mid = $_POST['mid'];
        $duties = $_POST['duties'];
    
        $sql = "UPDATE $table SET duties = '$duties' WHERE mid = '$mid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }


    if('DELETE' == $action){
        $mid = $_POST['mid'];
        $sql = "DELETE FROM $table WHERE mid = '$mid'";
        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }    
?>
