<?php
session_start();

require('../db.php');

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
    <style>
        /* styles.css */

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
}

a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
    color: #0056b3;
}

    </style>
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
