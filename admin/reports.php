<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   AJAX FILTER HANDLER
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $status = $_POST['status'] ?? '';
    $leave_type = $_POST['leave_type'] ?? '';
    $from = $_POST['from'] ?? '';
    $to = $_POST['to'] ?? '';

    $query = "
    SELECT lr.*, u.name, u.employee_id, u.department, lt.type_name
    FROM leave_requests lr
    JOIN users u ON lr.user_id = u.id
    JOIN leave_types lt ON lr.leave_type_id = lt.id
    WHERE 1=1
    ";

    if (!empty($name)) {
        $query .= " AND u.name LIKE '%$name%'";
    }

    if (!empty($department)) {
        $query .= " AND u.department LIKE '%$department%'";
    }

    if (!empty($status)) {
        $query .= " AND lr.status='$status'";
    }

    if (!empty($leave_type)) {
        $query .= " AND lt.type_name='$leave_type'";
    }

    if (!empty($from) && !empty($to)) {
        $query .= " AND lr.start_date BETWEEN '$from' AND '$to'";
    }

    $query .= " ORDER BY lr.id DESC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            echo "
            <tr>
                <td>{$row['name']}</td>
                <td>{$row['employee_id']}</td>
                <td>{$row['department']}</td>
                <td>{$row['type_name']}</td>
                <td>{$row['start_date']}</td>
                <td>{$row['end_date']}</td>
                <td>{$row['total_days']}</td>
                <td>{$row['status']}</td>
            </tr>
            ";
        }

    } else {
        echo "<tr><td colspan='8'>No records found</td></tr>";
    }

    exit();
}
?>

<h2>Leave Reports</h2>

<!-- FILTER UI -->
Name: <input type="text" id="name">
Department: <input type="text" id="department">
Leave Type: <input type="text" id="leave_type">

Status:
<select id="status">
    <option value="">All</option>
    <option value="pending">Pending</option>
    <option value="approved">Approved</option>
    <option value="rejected">Rejected</option>
</select>

From: <input type="date" id="from">
To: <input type="date" id="to">

<button onclick="loadReport()">Search</button>

<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Name</th>
    <th>ID</th>
    <th>Department</th>
    <th>Leave Type</th>
    <th>Start</th>
    <th>End</th>
    <th>Days</th>
    <th>Status</th>
</tr>

<tbody id="reportTable">
<!-- AJAX DATA LOAD HERE -->
</tbody>

</table>

<script>
function loadReport() {

    let formData = new FormData();

    formData.append("name", document.getElementById("name").value);
    formData.append("department", document.getElementById("department").value);
    formData.append("leave_type", document.getElementById("leave_type").value);
    formData.append("status", document.getElementById("status").value);
    formData.append("from", document.getElementById("from").value);
    formData.append("to", document.getElementById("to").value);

    fetch("reports.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("reportTable").innerHTML = data;
    });
}
</script>