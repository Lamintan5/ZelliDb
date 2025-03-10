<?php
    include 'db_config.php';

    $table = "entity";

    $action = $_POST['action'];
   
    if('ADD' == $action){
        $image = $_FILES['image']['name'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $admin = $_POST['admin'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $due = $_POST['due'];
        $late = $_POST['late'];
        $utilities = $_POST['utilities'];
        $location = $_POST['location'];
        
        $sql = "SELECT eid FROM $table WHERE eid = '".$eid."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);
        if($count > 0){
            echo 'Exists';
        } else {
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $imagePath = 'logos/' . $image;
                move_uploaded_file($tmp_name, $imagePath);
            } else {
                $image = $_POST['image']; 
            }
            $insert = "INSERT INTO $table(eid,pid,admin,title,category,image,due,late,utilities,location,checked) 
            VALUES ('".$eid."','".$pid."','".$admin."','".$title."','".$category."','".$image."','".$due."','".$late."', '".$utilities."', '".$location."','true')";
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
        $eid = $_POST['eid'];
        $query = "SELECT * FROM $table WHERE eid = '".$eid."'";
        $result = $db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    }

    if('GET_CURRENT_PROP' == $action){
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

    if ('UPDATE_PID' == $action) {
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];
    
        $tables = ['entity', 'payments', 'lease', 'units', 's-+tars', 'reviews', 'duties']; 
        $sql = "SELECT `pid` FROM entity WHERE eid = '$eid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pidField = $row['pid'];
    
            if (empty($pidField)) {
                $newPidField = $uid;
            } else {
                $uidsArray = explode(',', $pidField);
                if (!in_array($uid, $uidsArray)) {
                    $newPidField = $pidField . ',' . $uid;
                } else {
                    $newPidField = $pidField;
                }
            }
    
            foreach ($tables as $table) {
                $updateSql = "UPDATE $table SET `pid` = '$newPidField' WHERE eid = '$eid'";
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

    if ('REMOVE_PID' == $action) {
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];
    
        $tables = ['entity', 'notifications', 'payments', 'lease', 'units', 'stars', 'reviews', 'duties']; 
    
        $sql = "SELECT `pid` FROM entity WHERE eid = '$eid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pidField = $row['pid'];
    
            if (!empty($pidField)) {
                $uidsArray = explode(',', $pidField);
                $uidsArray = array_filter($uidsArray, function($item) use ($uid) {
                    return $item != $uid;
                });
                $newPidField = implode(',', $uidsArray);
            } else {
                $newPidField = '';
            }
    
            foreach ($tables as $table) {
                $updateSql = "UPDATE $table SET `pid` = '$newPidField' WHERE eid = '$eid'";
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

    if ('UPDATE_ADMIN' == $action) {
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];

        $tables = ['entity', 'payments']; 
    
        $sql = "SELECT `admin` FROM entity WHERE eid = '$eid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $adminField = $row['admin'];
    
            if (empty($adminField)) {
                $newAdminField = $uid;
            } else {
                $uidsArray = explode(',', $adminField);
                if (!in_array($uid, $uidsArray)) {
                    $newAdminField = $adminField . ',' . $uid;
                } else {
                    $newAdminField = $adminField;
                }
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

    // if('UPDATE_UTIL' == $action){
    //     $eid = $_POST['eid'];
    //     $utilities = $_POST['utilities'];
    //     $sql = "UPDATE $table SET utilities = '$utilities' WHERE eid = '$eid'";
    //     if ($conn->query($sql) === TRUE) { 
    //         echo "success";
    //     } else {
    //         echo "error";
    //     }
    //     $conn->close();
    //     return;
    // }

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

    if ('ADD_UTIL' == $action) {
        $eid = $_POST['eid'];
        $utilities = $_POST['utilities'];
    
        $eid = $conn->real_escape_string($eid);
        $utilities = $conn->real_escape_string($utilities);
    
        $checkSql = "SELECT utilities FROM $table WHERE eid = '$eid'";
        $result = $conn->query($checkSql);
    
        if ($result->num_rows == 0) {
            echo "Does not exist";
        } else {
            $row = $result->fetch_assoc();
            $existingUtilities = $row['utilities'];
    
            $existingArray = !empty($existingUtilities) ? explode('&', $existingUtilities) : [];
    
            $newArray = explode('&', $utilities);
    
            $alreadyExists = array_intersect($newArray, $existingArray);
    
            if (!empty($alreadyExists)) {
                echo "Exist";
            } else {
                $updatedArray = array_merge($existingArray, $newArray);
    
                $updatedUtilities = implode('&', array_unique($updatedArray));
    
                $updateSql = "UPDATE $table SET utilities = '$updatedUtilities' WHERE eid = '$eid'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "success";
                } else {
                    echo "failed: " . $conn->error;
                }
            }
        }
    
        $conn->close();
        return;
    }
    
    if ('UPDATE_ADMIN' == $action) {
        $eid = $_POST['eid'];
        $uid = $_POST['uid'];

        $tables = ['entity', 'payments']; 
    
        $sql = "SELECT `admin` FROM entity WHERE eid = '$eid'";
        $result = $conn->query($sql);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $adminField = $row['admin'];
    
            if (empty($adminField)) {
                $newAdminField = $uid;
            } else {
                $uidsArray = explode(',', $adminField);
                if (!in_array($uid, $uidsArray)) {
                    $newAdminField = $adminField . ',' . $uid;
                } else {
                    $newAdminField = $adminField;
                }
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