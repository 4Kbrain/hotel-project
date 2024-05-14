<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
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

    // Check if payment is higher than total cost
    if ($payment >= $total_cost) {
        // Calculate kembalian
        $kembalian = $payment - $total_cost;

        // Update reservation details
        $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='Booked', nodays='$nodays', payment='$payment', total_cost='$total_cost', kembalian='$kembalian' WHERE id='$id'";
        if ($conn->query($update_sql) === TRUE) {
            // Update history table
            $update_history_sql = "UPDATE history SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='Booked', nodays='$nodays', payment='$payment', total_cost='$total_cost', kembalian='$kembalian' WHERE id_reservation='$id'";
            if ($conn->query($update_history_sql) === TRUE) {
                // Update room status in kamar table
                $update_kamar_sql = "UPDATE kamar SET NIK='$NIK', room_status='In Use' WHERE id='$id_kamar'";
                if ($conn->query($update_kamar_sql) === TRUE) {
                    // Insert into transactions table
                    $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES ('$id', '$id_kamar', '$NIK', '$fname $lname', '$troom', '$bed', '$nroom', '$nodays', '$cin', '$cout', '$payment', '$total_cost', '$kembalian')";
                    if ($conn->query($insert_transaction_sql) === TRUE) {
                        echo "Reservation updated successfully";
                    } else {
                        echo "Error inserting into transactions table: " . $conn->error;
                    }
                } else {
                    echo "Error updating room status: " . $conn->error;
                }
            } else {
                echo "Error updating history table: " . $conn->error;
            }
        } else {
            echo "Error updating reservation: " . $conn->error;
        }
    } else {
        echo "Payment must be higher than total cost.";
    }
}

$conn->close();
?>
