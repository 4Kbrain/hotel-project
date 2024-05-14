<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for editing reservation details
    $id = $_POST['id'];
    $id_kamar = $_POST['id_kamar'];
    $NIK = $_POST['NIK'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];
    $status = $_POST['status'];
    $nodays = $_POST['nodays'];
    $payment = $_POST['payment'];
    $total_cost = $_POST['total_cost'];

    // Update reservation details
    $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='$status', nodays='$nodays', payment='$payment', total_cost='$total_cost' WHERE id='$id'";
    if ($conn->query($update_sql) === TRUE) {
        // Update history table
        $update_history_sql = "UPDATE history SET status='Booked' WHERE id_reservation='$id'";
        if ($conn->query($update_history_sql) === TRUE) {
            // Perform payment assignment
            $remaining_payment = $total_cost - $payment;
            if ($remaining_payment == 0) {
                echo "Payment completed.";
            } else {
                echo "Remaining payment: $remaining_payment";
            }
            // Update kamar table to set status to "In Use"
            $update_kamar_sql = "UPDATE kamar SET NIK='$NIK', room_status='In Use' WHERE id_kamar='$id_kamar'";
            if ($conn->query($update_kamar_sql) === TRUE) {
                echo "Room status updated successfully.";
            } else {
                echo "Error updating room status: " . $conn->error;
            }
        } else {
            echo "Error updating history table: " . $conn->error;
        }
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
}

$conn->close();
?>