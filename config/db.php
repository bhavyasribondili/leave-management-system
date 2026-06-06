<?php

$host = "localhost";
$user = "root";
$password = "";   // keep empty if you didn't set password
$db = "leave_management";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>