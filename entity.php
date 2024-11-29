<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "zelli";
    $table = "entity";

    $action = $_POST['action'];
    $db = mysqli_connect('localhost','root','','zelli');
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ('REMOVE_ADMIN' == $action) {
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];
    
        $tables = ['entity','payments']; 
    
        $sql = "SELECT `admin` FROM entity WHERE eid = '$eid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $adminField = $row['admin'];
    
            if (!empty($adminField)) {
                $uidsArray = explode(',', $adminField);
                $uidsArray = array_filter($uidsArray, function($item) use ($uid) {
                    return $item != $uid;
                });
                $newAdminField = implode(',', $uidsArray);
            } else {
                $newAdminField = '';
            }

            foreach ($tables as $table) {
                $updateSql = "UPDATE $table SET `admin` = '$newAdminField' WHERE eid = '$eid'";
                if ($conn->query($updateSql) !== TRUE) {
                    echo "failed";
                    $conn->close();
                    return;
                }
            }
        
            echo "success";
        } else {
            echo "Does not exist";
        }
    
        $conn->close();
        return;
    }
    
    

    if('UPDATE_UTIL' == $action){
        $eid = $_POST['eid'];
        $utilities = $_POST['utilities'];
        $sql = "UPDATE $table SET utilities = '$utilities' WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }
        $conn->close();
        return;
    }

    if('UPDATE' == $action){
        $image = $_FILES['image']['name'];
        $eid = $_POST['eid'];
        $title = $_POST['title'];
        $location = $_POST['location'];
        $category = $_POST['category'];
        $due = $_POST['due'];
        $late = $_POST['late'];        
    
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $imagePath = 'logos/' . $image;
            move_uploaded_file($tmp_name, $imagePath);
        } else {
            $image = $_POST['image']; 
        }
        $sql = "UPDATE $table SET title = '$title', location = '$location', category = '$category', due = '$due', late = '$late', image = '$image'";
       

        $sql .= " WHERE eid = '$eid'";
        if ($conn->query($sql) === TRUE) { 
            echo "success";
        } else {
            echo "error";
        }

        $conn->close();
        return;
    }


    if('UPDATE_UTIL' == $action){
        $eid = $_POST['eid'];
        $utilities = $_POST['utilities'];

        $checkSql = "SELECT COUNT(*) as count FROM $table WHERE eid = '$eid'";
        $result = $conn->query($checkSql);
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            echo "Does not exist";
        } else {
            $sql = "UPDATE $table SET utilities = '$utilities' WHERE eid = '$eid'";
            if ($conn->query($sql) === TRUE) { 
                echo "success";
            } else {
                echo "error";
            }
        }


        $conn->close();
        return;
    }



    if('DELETE' == $action){
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