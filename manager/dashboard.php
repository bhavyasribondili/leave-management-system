<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header("Location: ../auth/login.php");
    exit();
}

// DASHBOARD STATS
$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM leave_requests")
)['total'];

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

<h1>Manager Dashboard</h1>

<!-- NAVIGATION -->

<a href="leave_requests.php">Manage Leave Requests</a> | <a href="../auth/logout.php">Logout</a>

<hr>

<!-- STATS -->

<h2>Leave Request Summary</h2>

<div style="display:flex; gap:20px; flex-wrap:wrap;">

```
<div style="padding:15px; border:1px solid black;">
    <h3>Total Requests</h3>
    <p><?php echo $total; ?></p>
</div>

<div style="padding:15px; border:1px solid orange;">
    <h3>Pending</h3>
    <p><?php echo $pending; ?></p>
</div>

<div style="padding:15px; border:1px solid green;">
    <h3>Approved</h3>
    <p><?php echo $approved; ?></p>
</div>

<div style="padding:15px; border:1px solid red;">
    <h3>Rejected</h3>
    <p><?php echo $rejected; ?></p>
</div>
```

</div>
