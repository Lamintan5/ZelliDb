<?php
    

    if('ADD' == $action){
        $bid = $_POST['bid'];
        $eid = $_POST['eid'];
        $pid = $_POST['pid'];
        $bill = $_POST['bill'];
        $businessno = $_POST['businessno'];
        $account = $_POST['account'];
        $type = $_POST['type'];

        $sql = "SELECT bid FROM $table WHERE bid = '".$bid."'";
        $result = mysqli_query($db,$sql);
        $count = mysqli_num_rows($result);

        if($count == 1){
            echo 'Exists';
        } else {
            $insert = "INSERT INTO $table(bid,eid,pid,bill,businessno,account,type)
            VALUES ('".$bid."','".$eid."','".$pid."','".$bill."', '".$businessno."', '".$account."' ,'".$type."')";
            $query = mysqli_query($db,$insert);
            if($query){
                echo 'Success';
            } else {
                echo 'Failed';
            }
        }         
    }

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
