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

        // Prepare and bind update statement for reservation table
        $update_reservation_sql = "UPDATE reservation SET id_kamar=?, NIK=?, fname=?, lname=?, email=?, phone=?, troom=?, bed=?, nroom=?, cin=?, cout=?, status=?, nodays=?, payment=?, total_cost=?, kembalian=? WHERE id=?";
        $stmt = $conn->prepare($update_reservation_sql);
        $stmt->bind_param("ssssssssssssssssi", $id_kamar, $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $payment, $total_cost, $kembalian, $id);

        if ($stmt->execute()) {
            // Handle other operations (updating history, kamar, and inserting into transactions) here
            echo "Reservation updated successfully";
        } else {
            echo "Error updating reservation: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Payment must be higher than total cost.";
    }
}