<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

// Include database conection configuration
require_once "../db.php";

// Define variables and initialize them to avoid undefined variable warnings
$row = [];
$payment = $total_cost = $kembalian = "";
$id = $error = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $payment = filter_input(INPUT_POST, 'payment', FILTER_SANITIZE_STRING);
    $total_cost = filter_input(INPUT_POST, 'total_cost', FILTER_SANITIZE_STRING);

    if ($id && $payment && $total_cost) {
        // Check if payment is higher than total cost
        if ($payment >= $total_cost) {
            // Calculate kembalian
            $kembalian = $payment - $total_cost;

            // Start a transaction
            $con->begin_transaction();

            // Prepare and bind update statement for reservation table
            $update_reservation_sql = "UPDATE reservation SET payment=?, total_cost=?, kembalian=? WHERE id=?";
            $stmt = $con->prepare($update_reservation_sql);
            $stmt->bind_param("dddi", $payment, $total_cost, $kembalian, $id);

            // Execute the update statement
            if ($stmt->execute()) {
                // Prepare and execute update statement for history table
                $update_history_sql = "UPDATE history SET payment=?, total_cost=?, kembalian=? WHERE id_reservation=?";
                $stmt_history = $con->prepare($update_history_sql);
                $stmt_history->bind_param("dddi", $payment, $total_cost, $kembalian, $id);
                if ($stmt_history->execute()) {
                    // Update room status in kamar table
                    $update_kamar_sql = "UPDATE kamar SET room_status='In Use', NIK=? WHERE id_kamar=?";
                    $stmt_kamar = $con->prepare($update_kamar_sql);
                    $stmt_kamar->bind_param("si", $NIK, $id_kamar);
                    if ($stmt_kamar->execute()) {
                        // Insert into transactions table
                        $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_transactions = $con->prepare($insert_transaction_sql);
                        $stmt_transactions->bind_param("iisssssssssdd", $id, $id_kamar, $NIK, $fname . ' ' . $lname, $troom, $bed, $nroom, $nodays, $cin, $cout, $payment, $total_cost, $kembalian);

                        if ($stmt_transactions->execute()) {
                            $con->commit();
                            echo "Transaction updated successfully";
                        } else {
                            $con->rollback();
                            $error = "Error inserting into transactions table: " . $stmt_transactions->error;
                        }
                    } else {
                        $con->rollback();
                        $error = "Error updating room status: " . $stmt_kamar->error;
                    }
                } else {
                    $con->rollback();
                    $error = "Error updating history table: " . $stmt_history->error;
                }
            } else {
                $con->rollback();
                $error = "Error updating transaction: " . $stmt->error;
            }

            // Close the prepared statements
            $stmt->close();
            $stmt_history->close();
            $stmt_kamar->close();
            $stmt_transactions->close();
        } else {
            $error = "Payment must be higher than total cost.";
        }
    } else {
        $error = "Invalid input data.";
    }
} else {
    // If the request method is not POST, retrieve the transaction data for editing
    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            // Prepare and execute SELECT statement to fetch transaction data
            $select_transaction_sql = "SELECT * FROM r WHERE id_reservation=?";
            $stmt_select = $con->prepare($select_transaction_sql);
            $stmt_select->bind_param("i", $id);

            // Execute the statement
            if ($stmt_select->execute()) {
                // Get result
                $result = $stmt_select->get_result();

                // Check if there is a row fetched
                if ($result->num_rows > 0) {
                    // Fetch the data into an associative array
                    $row = $result->fetch_assoc();

                    // Assign fetched values to variables for pre-populating form fields
                    $payment = $row['payment'];
                    $total_cost = $row['total_cost'];
                    $kembalian = $row['kembalian'];
                } else {
                    $error = "No transaction found with ID: " . $id;
                }
            } else {
                $error = "Error fetching transaction: " . $stmt_select->error;
            }

            // Close the prepared statement
            $stmt_select->close();
        } else {
            $error = "Invalid transaction ID provided.";
        }
    } else {
        $error = "Transaction ID is not provided.";
    }
}

// Close the database conection
$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff;">Admin</span></a>
            <a href="index.php"><span><b>Beranda</b></span></a>
            <a href="status.php"><b><span>Roombooking</b></span></a>
            <a href="payment.php"><span>Transaction</span></a>
        </div>
        <div class="profile">
            <span style="color: #fff;">Username</span>
            <div class="profile-menu">
                <a href="#">Profile</a>
                <hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <h1>Edit Reservation - <?php echo $id; ?></h1>

    <form method="post" action="edit-transaction.php" class="container">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <!-- Other input fields -->
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo htmlspecialchars($row['payment'] ?? ''); ?>" oninput="calculateChange()"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost" value="<?php echo htmlspecialchars($row['total_cost'] ?? ''); ?>"><br>
        <label for="kembalian">Kembalian:</label><br>
        <input type="text" id="kembalian" name="kembalian" value="<?php echo htmlspecialchars($row['kembalian'] ?? ''); ?>"><br>
        <button type="submit">Submit</button>
        <div style="color: red;"><?php echo $error; ?></div>
    </form>

    <script>
        function calculateChange() {
            var payment = parseFloat(document.getElementById('payment').value);
            var totalCost = parseFloat(document.getElementById('total_cost').value);
            var change = payment - totalCost;
            document.getElementById('kembalian').value = change.toFixed(2); // Limiting to two decimal places
        }
    </script>
</body>
</html>
