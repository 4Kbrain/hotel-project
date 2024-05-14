<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Handle session not set
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservation_id = $_POST['reservation_id'];

// Update status to 'Cancelled' in the reservation table
$sql_update = "UPDATE reservation
               INNER JOIN history ON reservation.id = history.id_reservation
               SET reservation.status = 'Cancelled'
               WHERE reservation.id = '$reservation_id'";

if ($conn->query($sql_update) === TRUE) {
    // Redirect back to myreservation.php
    header("Location: myreservation.php");
    exit();
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
