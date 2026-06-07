<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   AJAX SEARCH HANDLER
========================= */
if (isset($_POST['search'])) {

    $search = $_POST['search'];

    $query = "SELECT * FROM users 
              WHERE name LIKE '%$search%' 
              OR employee_id LIKE '%$search%' 
              OR department LIKE '%$search%'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        echo "
        <tr>
            <td>{$row['id']}</td>
            <td>{$row['employee_id']}</td>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['department']}</td>
            <td>{$row['designation']}</td>
            <td>{$row['role']}</td>
            <td>{$row['status']}</td>
            <td>
                <a href='edit_user.php?id={$row['id']}'>Edit</a> | 
        ";

        if ($row['status'] == 'active') {
            echo "<a href='toggle_user.php?id={$row['id']}&status=inactive'>Deactivate</a>";
        } else {
            echo "<a href='toggle_user.php?id={$row['id']}&status=active'>Activate</a>";
        }

        echo "
            </td>
        </tr>
        ";
    }

    exit();
}
?>

<h2>All Users</h2>

<!-- AJAX SEARCH BOX -->
<input type="text" id="searchBox" placeholder="Search users...">
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Employee ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Department</th>
    <th>Designation</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<tbody id="userTable">
<?php
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['employee_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['department']; ?></td>
    <td><?php echo $row['designation']; ?></td>
    <td><?php echo $row['role']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> |

        <?php if ($row['status'] == 'active') { ?>
            <a href="toggle_user.php?id=<?php echo $row['id']; ?>&status=inactive">Deactivate</a>
        <?php } else { ?>
            <a href="toggle_user.php?id=<?php echo $row['id']; ?>&status=active">Activate</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</tbody>

</table>

<!-- =========================
     AJAX SCRIPT
========================= -->
<script>
document.getElementById("searchBox").addEventListener("keyup", function () {

    let formData = new FormData();
    formData.append("search", this.value);

    fetch("users.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("userTable").innerHTML = data;
    });
});
</script>