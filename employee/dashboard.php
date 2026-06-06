<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../auth/login.php");
    exit();
}

echo "<h1>Employee Dashboard</h1>";
echo "<a href='../auth/logout.php'>Logout</a>";
?>