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
