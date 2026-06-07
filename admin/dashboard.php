<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// USERS
$total_users = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users")
)['total'];

$total_employees = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='employee'")
)['total'];

$total_managers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='manager'")
)['total'];

$active_users = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='active'")
)['total'];

$inactive_users = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='inactive'")
)['total'];

// LEAVES
$pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM leave_requests WHERE status='pending'")
)['total'];

$approved = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM leave_requests WHERE status='approved'")
)['total'];

$rejected = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM leave_requests WHERE status='rejected'")
)['total'];
?>

<h1>Admin Dashboard</h1>

<a href="users.php">View Users</a> | <a href="add_user.php">Add User</a> | <a href="../auth/logout.php">Logout</a>
<a href="leave_types.php">Leave Configuration</a><br><br>
<a href="reports.php">Reports</a><br><br>
<hr>

<h2>User Summary</h2>

<p>Total Users: <?php echo $total_users; ?></p>
<p>Employees: <?php echo $total_employees; ?></p>
<p>Managers: <?php echo $total_managers; ?></p>
<p>Active Users: <?php echo $active_users; ?></p>
<p>Inactive Users: <?php echo $inactive_users; ?></p>

<hr>

<h2>Leave Summary</h2>

<p>Pending Leaves: <?php echo $pending; ?></p>
<p>Approved Leaves: <?php echo $approved; ?></p>
<p>Rejected Leaves: <?php echo $rejected; ?></p>

<hr>

<hr>
<p><b>System Status: Fully Functional Leave Management System</b></p>