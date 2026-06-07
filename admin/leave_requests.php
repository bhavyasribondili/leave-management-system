<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$query = "
SELECT lr.*, u.name, lt.type_name
FROM leave_requests lr
JOIN users u ON lr.user_id = u.id
JOIN leave_types lt ON lr.leave_type_id = lt.id
ORDER BY lr.id DESC
";

$result = mysqli_query($conn, $query);
?>

<h2>All Leave Requests</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Employee</th>
    <th>Leave Type</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Total Days</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['type_name']; ?></td>
    <td><?php echo $row['start_date']; ?></td>
    <td><?php echo $row['end_date']; ?></td>
    <td><?php echo $row['total_days']; ?></td>
    <td><?php echo $row['status']; ?></td>
</tr>
<?php } ?>

</table>