<?php
session_start();

if (!isset($_SESSION['NIK'])) {
    exit();
}

require_once('../db.php');

$reservation_id = $_POST['reservation_id'];

// Update reservation status to "Cancelled"
$sql_update_reservation = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
if ($con->query($sql_update_reservation) === TRUE) {
    // Reservation cancellation successful, proceed to update kamar table

    // Update kamar table: set room_status to an empty string and NIK to NULL
    $sql_update_kamar = "UPDATE kamar SET room_status = '', NIK = NULL WHERE id_kamar IN (SELECT id_kamar FROM reservation WHERE id = '$reservation_id')";
    if ($con->query($sql_update_kamar) === TRUE) {
        // Kamar table updated successfully, proceed to update history table

        // Update history table status to "Cancelled"
        $sql_update_history = "UPDATE history SET status = 'Cancelled' WHERE id_reservation = '$reservation_id'";
        if ($con->query($sql_update_history) === TRUE) {
            echo "Reservation cancelled successfully.";
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
