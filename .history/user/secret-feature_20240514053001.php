<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Get list of all tables
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        $conn->begin_transaction();
        try {
            while ($row = $result->fetch_array()) {
                $table = $row[0];
                $conn->query("TRUNCATE TABLE $table");
            }
            $conn->commit();
            $message = "All data has been deleted successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "Failed to delete all data: " . $e->getMessage();
        }
    } else {
        $message = "Failed to retrieve table list: " . $conn->error;
    }
}

$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete All Data</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
        }
        .btn-delete {
            background-color: #ff4d4d;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-delete:hover {
            background-color: #ff3333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <p>Click the button below to delete all data from the database.</p>
        <form method="post" action="">
            <button type="submit" name="delete" class="btn-delete">Delete All Data</button>
        </form>
        <?php
        if (isset($message)) {
            echo "<p>$message</p>";
        }
        ?>
    </div>
</body>
</html>
