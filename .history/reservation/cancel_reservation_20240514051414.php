<?php
session_start();

if (!isset($_SESSION['NIK'])) {
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

$reservation_id = $_POST['reservation_id'];

$sql_update_reservation = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
if ($con->query($sql_update_reservation) === TRUE) {
    $sql_update_kamar = "UPDATE kamar SET room_status = '', NIK = NULL WHERE id_kamar IN (SELECT id_kamar FROM reservation WHERE id = '$reservation_id')";
    if ($con->query($sql_update_kamar) === TRUE) {

        $sql_update_history = "UPDATE history SET status = 'Cancelled' WHERE id_reservation = '$reservation_id'";
        if ($con->query($sql_update_history) === TRUE) {
            echo "Reservation cancelled successfully.";
            header("Location: myreservation.php");
        } else {
            echo "Error updating history table: " . $con->error;
        }
    } else {
        echo "Error updating kamar table: " . $con->error;
    }
} else {
    echo "Error updating reservation table: " . $con->error;
}

$con->close();
