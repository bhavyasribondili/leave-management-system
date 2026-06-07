<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Total Requests
$total_requests = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM leave_requests WHERE user_id='$user_id'"
    )
);

// Approved Requests
$approved_requests = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM leave_requests
         WHERE user_id='$user_id'
         AND status='approved'"
    )
);

// Pending Requests
$pending_requests = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM leave_requests
         WHERE user_id='$user_id'
         AND status='pending'"
    )
);

// Rejected Requests
$rejected_requests = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT id FROM leave_requests
         WHERE user_id='$user_id'
         AND status='rejected'"
    )
);

// Total Available Balance
$balance_result = mysqli_query(
    $conn,
    "SELECT SUM(total_days - used_days) AS available_balance
     FROM leave_balance
     WHERE user_id='$user_id'"
);

$balance_data = mysqli_fetch_assoc($balance_result);
$available_balance = $balance_data['available_balance'];

// Leave Balance Table
$balance_query = mysqli_query(
    $conn,
    "SELECT lb.*, lt.type_name
     FROM leave_balance lb
     JOIN leave_types lt
     ON lb.leave_type_id = lt.id
     WHERE lb.user_id='$user_id'"
);
?>

<h1>Employee Dashboard</h1>

<a href="apply_leave.php">Apply Leave</a> <br><br>

<a href="leave_history.php">My Leave History</a> <br><br>

<a href="../auth/logout.php">Logout</a>

<hr>

<h2>Dashboard Summary</h2>

<p><strong>Total Requests:</strong> <?php echo $total_requests; ?></p>

<p><strong>Approved Requests:</strong> <?php echo $approved_requests; ?></p>

<p><strong>Pending Requests:</strong> <?php echo $pending_requests; ?></p>

<p><strong>Rejected Requests:</strong> <?php echo $rejected_requests; ?></p>

<p><strong>Available Leave Balance:</strong> <?php echo $available_balance; ?> Days</p>

<hr>

<h2>My Leave Balance</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Leave Type</th>
    <th>Total Days</th>
    <th>Used Days</th>
    <th>Available Days</th>
</tr>

<?php while($row = mysqli_fetch_assoc($balance_query)) { ?>

<tr>
    <td><?php echo $row['type_name']; ?></td>
    <td><?php echo $row['total_days']; ?></td>
    <td><?php echo $row['used_days']; ?></td>
    <td><?php echo $row['total_days'] - $row['used_days']; ?></td>
</tr>

<?php } ?>

</table>
