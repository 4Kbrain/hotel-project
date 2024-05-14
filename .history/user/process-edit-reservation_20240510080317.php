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
    $id = $_POST['id'];
    $payment = floatval($_POST['payment']);

    // Get the reservation details
    $reservation_sql = "SELECT * FROM reservation WHERE id='$id'";
    $reservation_result = $conn->query($reservation_sql);

    if ($reservation_result->num_rows > 0) {
        $row = $reservation_result->fetch_assoc();
        $total_cost = floatval($row['total_cost']);

        // Check if the payment is sufficient
        if ($payment >= $total_cost) {
            // Update reservation status to 'Available'
            $update_reservation_sql = "UPDATE reservation SET status='Paid' WHERE id='$id'";
            $conn->query($update_reservation_sql);

            // Update room_status in kamar table to 'In Use'
            $update_kamar_sql = "UPDATE kamar k
                                 INNER JOIN reservation r ON k.id_kamar = r.id_kamar
                                 SET k.room_status='In Use'
                                 WHERE r.id='$id'";
            $conn->query($update_kamar_sql);

            // Calculate change
            $change = $payment - $total_cost;

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
                $id = $_POST['id'];
                $payment = floatval($_POST['payment']);
            
                // Get the reservation details
                $reservation_sql = "SELECT * FROM reservation WHERE id='$id'";
                $reservation_result = $conn->query($reservation_sql);
            
                if ($reservation_result->num_rows > 0) {
                    $row = $reservation_result->fetch_assoc();
                    $total_cost = floatval($row['total_cost']);
            
                    // Check if the payment is sufficient
                    if ($payment >= $total_cost) {
                        // Update reservation status to 'Available'
                        $update_reservation_sql = "UPDATE reservation SET status='Paid' WHERE id='$id'";
                        $conn->query($update_reservation_sql);
            
                        // Update room_status in kamar table to 'In Use'
                        $update_kamar_sql = "UPDATE kamar k
                                             INNER JOIN reservation r ON k.id_kamar = r.id_kamar
                                             SET k.room_status='In Use'
                                             WHERE r.id='$id'";
                        $conn->query($update_kamar_sql);
            
                        // Calculate change
                        $change = $payment - $total_cost;
            
            // Insert transaction record with change
            $insert_transaction_sql = "INSERT INTO transactions (id_reservation, NIK, nama, cin, cout, payment, total_cost, `change`) 
            SELECT id, NIK, CONCAT(fname, ' ', lname) AS nama, cin, cout, '$payment', total_cost, '$change' 
            FROM reservation 
            WHERE id='$id'";
            
            $conn->query($insert_transaction_sql);
            
            $transaction_sql = "SELECT * FROM transactions WHERE id_reservation = '$id'";
            $transaction_result = $conn->query($transaction_sql);
            
            if ($transaction_result->num_rows > 0) {
                $transaction_row = $transaction_result->fetch_assoc();
                $transaction_id = $transaction_row['id'];
            
                // Redirect to print_invoice.php with the transaction ID
                header("Location: action/print_invoice.php?id=$transaction_id");
                exit();
            } else {
                // Handle case where transaction is not found
                echo "Transaction not found.";
            }
            }
        
        }
    }
}
