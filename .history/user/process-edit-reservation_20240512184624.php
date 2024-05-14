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

    // Calculate change
    $change = $payment - $total_cost;

    // Update reservation details
    $update_sql = "UPDATE reservation SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='$status', nodays='$nodays', payment='$payment', total_cost='$total_cost', change='$change' WHERE id='$id'";
    if ($conn->query($update_sql) === TRUE) {
        // Update history table
        $update_history_sql = "UPDATE history SET id_kamar='$id_kamar', NIK='$NIK', fname='$fname', lname='$lname', email='$email', phone='$phone', troom='$troom', bed='$bed', nroom='$nroom', cin='$cin', cout='$cout', status='$status', nodays='$nodays', payment='$payment', total_cost='$total_cost', change='$change' WHERE id_reservation='$id'";
        if ($conn->query($update_history_sql) === TRUE) {
            echo "Reservation updated successfully";
        } else {
            echo "Error updating history table: " . $conn->error;
        }
    } else {
        echo "Error updating reservation: " . $conn->error;
    }
}

$conn->close();
?>
