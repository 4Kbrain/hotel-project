<?php
session_start();

require('../../db.php');
if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

$sql = "SELECT * FROM users WHERE is_admin != 1";
$result = $con->query($sql);

$users = array();
if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <table>
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Gmail</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['NIK']; ?></td>
                        <td><?php echo $user['gmail']; ?></td>
                        <td><a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> | <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_user.php">Add New User</a>
        <br><br>
        <a href="../session/logout.php">Logout</a>
    </div>
</body>
</html>
