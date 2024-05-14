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
            $stmt_history->bind_param("di", $kembalian, $id); // Use kembalian instead of payment
            $stmt_history->execute();

            // Insert into transactions table
            $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, email, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_transactions = $conn->prepare($insert_transaction_sql);
            $stmt_transactions->bind_param("iisssssssssdd", $id, $id_kamar, $NIK, $fname . ' ' . $lname, $email, $troom, $bed, $nroom, $nodays, $cin, $cout, $payment, $total_cost, $kembalian);
            $stmt_transactions->execute();

            // Commit transaction
            $conn->commit();

            // Perform SQL query to select data from transactions table
            $select_transaction_sql = "SELECT * FROM transactions WHERE id_reservation=?";
            $stmt_select_transaction = $conn->prepare($select_transaction_sql);
            $stmt_select_transaction->bind_param("i", $id);
            $stmt_select_transaction->execute();
            $result = $stmt_select_transaction->get_result();

            // Check if any transaction record exists
            if ($result->num_rows > 0) {
                // Fetch the transaction data
                $transaction_data = $result->fetch_assoc();

                // Redirect to print_invoice.php with the transaction ID
                header("Location: http://localhost/hotel-project/user/action/print_invoice.php?id=" . $transaction_data['id']);
                exit();
            } else {
                // No transaction found for the given reservation ID
                echo "No transaction found for the reservation.";
            }
        } catch (Exception $e) {
            // Rollback transaction if any query fails
            $conn->rollback();
            echo "Failed to update reservation: " . $e->getMessage();
        }
    } else {
        echo "Payment must be higher than total cost.";
    }
}

$conn->close();
?>
