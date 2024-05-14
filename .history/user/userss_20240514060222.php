<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE is_admin != 1";
$result = $conn->query($sql);

$users = array();
if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        /* styles.css */

/* Navbar */
.navbar {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar .logo a {
    color: #fff;
    text-decoration: none;
    font-size: 20px;
}

.navbar .profile {
    position: relative;
}

.navbar .profile .profile-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

.navbar .profile:hover .profile-menu {
    display: block;
}

.navbar .profile .profile-menu a {
    display: block;
    text-decoration: none;
    color: #333;
    padding: 5px 0;
}

.navbar .profile .profile-menu a:hover {
    background-color: #f0f0f0;
}

/* Sidebar */
.sidebar {
    width: 200px;
    background-color: #f0f0f0;
    padding: 20px 10px;
    position: fixed;
    top: 60px;
    left: 0;
    bottom: 0;
    overflow-y: auto;
    box-shadow: 1px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar a {
    display: block;
    color: #333;
    text-decoration: none;
    padding: 10px 20px;
    margin-bottom: 10px;
    transition: background-color 0.3s;
}

.sidebar a.active,
.sidebar a:hover {
    background-color: #ddd;
}

/* Container */
.container {
    margin-left: 220px; /* Adjust based on sidebar width */
    padding: 20px;
}

.container h1 {
    margin-bottom: 20px;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 10px;
}

table th {
    background-color: #f0f0f0;
}

/* Add New User Link */
.container a {
    display: inline-block;
    background-color: #333;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.container a:hover {
    background-color: #555;
}

    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff;">Admin</span></a>
        </div>
        <div class="profile">
            <span style="color: #fff;">Username</span>
            <div class="profile-menu">
                <a href="#">Profile</a>
                <hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <a href="user_management.php" class="active">User Management</a>
        <a href="other_page.php">Other Page</a>
    </div>

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
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> | 
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_user.php">Add New User</a>
    </div>
</body>
</html>
