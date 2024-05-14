<?php
session_start();

if (!isset($_SESSION['NIK'])) {
    exit();
}

require_once('../db.php');

$reservation_id = $_POST['reservation_id'];

// Update reservation status to "Cancelled"
$sql_update_reservation = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
$con->query($sql_update_reservation);

// Update kamar table: set room_status to an empty string and NIK to NULL
$sql_update_kamar = "UPDATE kamar SET room_status = '', NIK = NULL WHERE id_kamar IN (SELECT id_kamar FROM reservation WHERE id = '$reservation_id')";
$con->query($sql_update_kamar);

// Update history table status to "Cancelled"
$sql_update_history = "UPDATE history SET status = 'Cancelled' WHERE id_reservation = '$reservation_id'";
$con->query($sql_update_history);

echo "Reservation cancelled successfully.";

$con->close();
?>
