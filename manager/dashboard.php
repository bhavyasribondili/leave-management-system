<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

echo "<h1>Manager Dashboard</h1>";
echo "<a href='../auth/logout.php'>Logout</a>";
?>