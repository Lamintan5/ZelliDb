<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zelli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$db = mysqli_connect($servername, $username, $password, $dbname);
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
