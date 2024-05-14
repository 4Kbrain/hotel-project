<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

// Check if user is an admin
if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. You are not authorized to access this page."]);
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

    // Retrieve NIK from the corresponding reservation record
    $reservation_sql = "SELECT NIK FROM reservation WHERE id='$id'";
    $reservation_result = $conn->query($reservation_sql);

    if ($reservation_result->num_rows > 0) {
        $reservation_row = $reservation_result->fetch_assoc();
        $reservation_NIK = $reservation_row['NIK'];

        // Update reservation details
        $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='$status', nodays='$nodays', payment='$payment', total_cost='$total_cost' WHERE id='$id'";
        if ($conn->query($update_sql) === TRUE) {
            // Update history table
            $update_history_sql = "UPDATE history SET status='Booked' WHERE id_reservation='$id'";
            if ($conn->query($update_history_sql) === TRUE) {
                // Perform payment assignment
                $remaining_payment = $total_cost - $payment;
                if ($remaining_payment == 0) {
                    echo json_encode(["success" => true, "message" => "Payment completed."]);
                } else {
                    echo json_encode(["success" => true, "message" => "Remaining payment: $remaining_payment"]);
                }
                // Update kamar table to set status to "In Use" and assign NIK from reservation
                $update_kamar_sql = "UPDATE kamar SET NIK='$reservation_NIK', room_status='In Use' WHERE id_kamar='$id_kamar'";
                if ($conn->query($update_kamar_sql) === TRUE) {
                    // If everything is successful, return success message
                    echo json_encode(["success" => true, "message" => "Room status updated successfully."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error updating room status: " . $conn->error]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Error updating history table: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error updating reservation: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Reservation not found."]);
    }
}

$conn->close();
?>
