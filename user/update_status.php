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

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "SELECT * FROM reservation WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$NIK = $row['NIK'];
$nama = $row['fname'] . " " . $row['lname'];
$total_cost = $row['total_cost'];

$cin = $row['cin'];
$cout = $row['cout'];


$sql = "UPDATE reservation SET status = '$status' WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    // Insert into transactions table
    $sql = "INSERT INTO transactions (NIK, nama, total_cost, cin, cout) 
            VALUES ('$NIK', '$nama', $total_cost, '$cin', '$cout')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Status updated successfully and transaction recorded"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error inserting into transactions table: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error updating status: " . $conn->error]);
}

$conn->close();
?>
