<?php
session_start();

if (!isset($_SESSION['NIK'])) {
    exit();
}
require_once('../db.php');
$reservation_id = $_POST['reservation_id'];

$sql_select = "SELECT * FROM reservation WHERE id = '$reservation_id'";
$result = $con->query($sql_select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    $NIK = $row['NIK'];
    $fname = $row['fname'];
    $lname = $row['lname'];
    $email = $row['email'];
    $phone = $row['phone'];
    $troom = $row['troom'];
    $bed = $row['bed'];
    $nroom = $row['nroom'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $nodays = $row['nodays'];
    $total_cost = $row['total_cost'];
    
    $sql_update_reservation = "UPDATE reservation SET status = 'Cancelled' WHERE id = '$reservation_id'";
    $con->query($sql_update_reservation);

    $sql_update_history = "UPDATE history SET status = 'Cancelled' WHERE id_reservation = '$reservation_id'";
    $con->query($sql_update_history);

    $sql_update_room = "UPDATE kamar SET room_status = 'Available', NIK = NULL WHERE id_kamar = '{$row['id_kamar']}'";
    $con->query($sql_update_room);

    echo "Reservation cancelled successfully.";
} else {
    echo "No reservation found with ID: $reservation_id";
}

$con->close();
?>
