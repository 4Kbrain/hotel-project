<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

// Include database connection configuration
require_once ".php";

// Define variables and initialize them to avoid undefined variable warnings
$row = [];
$payment = $total_cost = $kembalian = "";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $payment = $_POST['payment'];
    $total_cost = $_POST['total_cost'];

    // Check if payment is higher than total cost
    if ($payment >= $total_cost) {
        // Calculate kembalian
        $kembalian = $payment - $total_cost;

        // Start a transaction to ensure atomicity
        $conn->begin_transaction();

        // Prepare and bind update statement for reservation table
        $update_reservation_sql = "UPDATE reservation SET payment=?, total_cost=?, kembalian=? WHERE id=?";
        $stmt_reservation = $conn->prepare($update_reservation_sql);
        $stmt_reservation->bind_param("dddi", $payment, $total_cost, $kembalian, $id);

        // Execute the update statement for reservation
        if ($stmt_reservation->execute()) {
            // Prepare and bind update statement for kamar table
            $update_kamar_sql = "UPDATE kamar SET room_status='In Use', NIK=? WHERE id_kamar=?";
            $stmt_kamar = $conn->prepare($update_kamar_sql);
            $stmt_kamar->bind_param("si", $_POST['NIK'], $_POST['id_kamar']);

            // Execute the update statement for kamar
            if ($stmt_kamar->execute()) {
                // Prepare and bind update statement for history table
                $update_history_sql = "UPDATE history SET id_kamar=?, NIK=?, fname=?, lname=?, email=?, phone=?, troom=?, bed=?, nroom=?, cin=?, cout=?, status=?, nodays=?, payment=?, total_cost=?, kembalian=? WHERE id_reservation=?";
                $stmt_history = $conn->prepare($update_history_sql);
                $stmt_history->bind_param("sssssssssssssssssi", $_POST['id_kamar'], $_POST['NIK'], $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone'], $_POST['troom'], $_POST['bed'], $_POST['nroom'], $_POST['cin'], $_POST['cout'], $_POST['status'], $_POST['nodays'], $payment, $total_cost, $kembalian, $id);

                // Execute the update statement for history
                if ($stmt_history->execute()) {
                    // Prepare and bind insert statement for transactions table
                    $insert_transaction_sql = "INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_transaction = $conn->prepare($insert_transaction_sql);
                    $stmt_transaction->bind_param("iisssssssssdd", $id, $_POST['id_kamar'], $_POST['NIK'], $_POST['fname'] . ' ' . $_POST['lname'], $_POST['troom'], $_POST['bed'], $_POST['nroom'], $_POST['nodays'], $_POST['cin'], $_POST['cout'], $payment, $total_cost, $kembalian);

                    // Execute the insert statement for transactions
                    if ($stmt_transaction->execute()) {
                        // Commit the transaction if all queries succeed
                        $conn->commit();
                        echo "Transaction updated successfully";
                    } else {
                        // Rollback the transaction if inserting into transactions table fails
                        $conn->rollback();
                        echo "Error inserting into transactions table: " . $stmt_transaction->error;
                    }
                } else {
                    // Rollback the transaction if updating history table fails
                    $conn->rollback();
                    echo "Error updating history table: " . $stmt_history->error;
                }
            } else {
                // Rollback the transaction if updating kamar table fails
                $conn->rollback();
                echo "Error updating kamar table: " . $stmt_kamar->error;
            }
        } else {
            // Rollback the transaction if updating reservation table fails
            $conn->rollback();
            echo "Error updating reservation table: " . $stmt_reservation->error;
        }

        // Close all prepared statements
        $stmt_reservation->close();
        $stmt_kamar->close();
        $stmt_history->close();
        $stmt_transaction->close();
    } else {
        echo "Payment must be higher than total cost.";
    }
}

// Close the database connection
$conn->close();
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
