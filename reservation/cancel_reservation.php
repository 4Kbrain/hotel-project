<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservation_id = $_POST['reservation_id'];

// Query untuk membatalkan reservasi berdasarkan ID reservasi
$sql = "DELETE FROM reservation WHERE id = $reservation_id";

if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Reservation canceled successfully.");</script>';
    echo '<script>window.location.replace("myreservation.php");</script>';
} else {
    echo '<script>alert("Error canceling reservation.");</script>';
    echo '<script>window.location.replace("myreservation.php");</script>';
}

$conn->close();
?>
