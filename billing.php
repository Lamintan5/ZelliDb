<?php
    

    if('UPDATE' == $action){
        $bid = $_POST['bid'];
        $businessno = $_POST['businessno'];  
        $account = $_POST['account'];
        $type = $_POST['type'];    
        $sql = "UPDATE $table SET  businessno = '$businessno' WHERE bid = '$bid'";
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
