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
            // Update history table
            $update_history_sql = "UPDATE history SET id_kamar=?, NIK=?, fname=?, lname=?, email=?, phone=?, troom=?, bed=?, nroom=?, cin=?, cout=?, status=?, nodays=?, payment=?, total_cost=?, kembalian=? WHERE id_reservation=?";
            $stmt_history = $conn->prepare($update_history_sql);
            $stmt_history->bind_param("sssssssssssssssssi", $id_kamar, $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $payment, $total_cost, $kembalian, $id);
            if ($stmt_history->execute()) {
                // Update room status in kamar table
                $update_kamar_sql = "UPDATE kamar SET room_status='In Use', NIK=? WHERE id_kamar=?";
                $stmt_kamar = $conn->prepare($update_kamar_sql);
                $stmt_kamar->bind_param("si", $NIK, $id_kamar);
                if ($stmt_kamar->execute()) {
                    // Insert into transactions table
                    $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_transactions = $conn->prepare($insert_transaction_sql);
                    $stmt_transactions->bind_param("iisssssssssdd", $id, $id_kamar, $NIK, $fname . ' ' . $lname, $troom, $bed, $nroom, $nodays, $cin, $cout, $payment, $total_cost, $kembalian);


                    if ($stmt_transactions->execute()) {
                        echo "Reservation updated successfully";
                    } else {
                        echo "Error inserting into transactions table: " . $stmt_transactions->error;
                    }
                } else {
                    echo "Error updating room status: " . $stmt_kamar->error;
                }
            } else {
                echo "Error updating history table: " . $stmt_history->error;
            }
        } else {
            echo "Error updating reservation: " . $stmt->error;
        }

        // Close all prepared statements
        $stmt->close();
        $stmt_history->close();
        $stmt_kamar->close();
        $stmt_transactions->close();
    } else {
        echo "Payment must be higher than total cost.";
    }
}

$conn->close();