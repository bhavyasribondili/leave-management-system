<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   FILTER (OPTIONAL)
========================= */
$action = $_GET['action'] ?? '';

/* =========================
   BASE QUERY
========================= */
$query = "
SELECT al.*, u.name, u.employee_id
FROM audit_log al
JOIN users u ON al.user_id = u.id
WHERE 1=1
";

if ($action != '') {
    $query .= " AND al.action='$action'";
}

$query .= " ORDER BY al.id DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Audit Log - Leave Actions</h2>

<!-- NAV -->
<a href="dashboard.php">Dashboard</a> |
<a href="users.php">Users</a> |
<a href="leave_types.php">Leave Config</a> |
<a href="../auth/logout.php">Logout</a>

<hr>

<!-- FILTER -->
<form method="GET">
    Filter Action:
    <select name="action">
        <option value="">All</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>

    <button type="submit">Filter</button>
</form>

<br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Employee</th>
    <th>Employee ID</th>
    <th>Leave ID</th>
    <th>Action</th>
    <th>Remarks</th>
    <th>Timestamp</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['employee_id']; ?></td>
    <td><?php echo $row['leave_id']; ?></td>

    <td>
        <?php if ($row['action'] == 'approved') { ?>
            <span style="color:green; font-weight:bold;">Approved</span>
        <?php } else { ?>
            <span style="color:red; font-weight:bold;">Rejected</span>
        <?php } ?>
    </td>

    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
    <td><?php echo $row['created_at']; ?></td>
</tr>

<?php } ?>

</table>