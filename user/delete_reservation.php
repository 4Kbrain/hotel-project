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

// Check connect
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check id parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete  reservation  
    $sql = "DELETE FROM reservation WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Deleted";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
