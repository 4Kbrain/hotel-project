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

        // Start a transaction
        $conn->begin_transaction();

        try {
            // Update reservation table
            $update_reservation_sql = "UPDATE reservation SET status='Booked', payment=?, kembalian=? WHERE id=?";
            $stmt_reservation = $conn->prepare($update_reservation_sql);
            $stmt_reservation->bind_param("ddi", $payment, $kembalian, $id);
            $stmt_reservation->execute();

            // Update history table
            $update_history_sql = "UPDATE history SET status='Booked', kembalian=? WHERE id_reservation=?";
            $stmt_history = $conn->prepare($update_history_sql);
            $stmt_history->bind_param("di", $kembalian, $id); // Use kembalian, not payment, to match the field purpose
            $stmt_history->execute();

            // Insert into transactions table
            $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_transactions = $conn->prepare($insert_transaction_sql);
            $stmt_transactions->bind_param("iisssssssssdd", $id, $id_kamar, $NIK, $fname . ' ' . $lname, $troom, $bed, $nroom, $nodays, $cin, $cout, $payment, $total_cost, $kembalian);
            $stmt_transactions->execute();

            // Commit transaction
            $conn->commit();

            // Redirect to print_invoice.php
            header("Location: http://localhost/hotel-project/user/action/print_invoice.php?id=$id");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $conn->rollback();
            echo "Error updating reservation: " . $e->getMessage();
        }
    } else {
        echo "Payment must be higher than total cost.";
    }
}

$conn->close();
