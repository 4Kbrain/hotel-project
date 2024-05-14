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

    // Check if payment is higher than total_cost
    if ($payment >= $total_cost) {
        // Payment is successful, calculate change
        $change = $payment - $total_cost;
        $payment_status = "Success";
    } else {
        // Payment failed
        $change = 0;
        $payment_status = "Failed";
    }

    // Update reservation details
    $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='Booked', payment='$payment', total_cost='$total_cost' WHERE id='$id'";
    if ($conn->query($update_sql) === TRUE) {
        // Update history table
        $update_history_sql = "UPDATE history SET status='Booked', payment='$payment', total_cost='$total_cost', change='$change' WHERE id_reservation='$id'";
        if ($conn->query($update_history_sql) === TRUE) {
            // Update kamar table to set status to "In Use" and assign NIK from reservation
            $update_kamar_sql = "UPDATE kamar SET NIK='$NIK', room_status='In Use' WHERE id_kamar='$id_kamar'";
            if ($conn->query($update_kamar_sql) === TRUE) {
                // Insert into transactions table
                $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, change) VALUES ('$id', '$id_kamar', '$NIK', '$fname $lname', '$troom', '$bed', '$nroom', '$nodays', '$cin', '$cout', '$payment', '$total_cost', '$change')";
                if ($conn->query($insert_transaction_sql) === TRUE) {
                    echo json_encode(["success" => true, "message" => "Reservation updated successfully.", "payment_status" => $payment_status]);
                } else {
                    echo json_encode(["success" => false, "message" => "Error inserting into transactions table: " . $conn->error]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Error updating kamar table: " . $conn->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error updating history table: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error updating reservation: " . $conn->error]);
    }
}

$conn->close();
?>
