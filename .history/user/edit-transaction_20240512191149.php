<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

// Include database connection configuration
require_once "db_config.php";

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

        // Prepare and bind update statement for reservation table
        $update_reservation_sql = "UPDATE reservation SET payment=?, total_cost=?, kembalian=? WHERE id=?";
        $stmt = $conn->prepare($update_reservation_sql);
        $stmt->bind_param("dddi", $payment, $total_cost, $kembalian, $id);

        // Execute the update statement
        if ($stmt->execute()) {
            echo "Transaction updated successfully";
        } else {
            echo "Error updating transaction: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Payment must be higher than total cost.";
    }
} else {
    // If the request method is not POST, retrieve the transaction data for editing
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Prepare and execute SELECT statement to fetch transaction data
        $select_transaction_sql = "SELECT * FROM transactions WHERE id_reservation=?";
        $stmt_select = $conn->prepare($select_transaction_sql);
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
                echo "No transaction found with ID: " . $id;
            }
        } else {
            echo "Error fetching transaction: " . $stmt_select->error;
        }

        // Close the prepared statement
        $stmt_select->close();
    } else {
        echo "Transaction ID is not provided.";
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

    <form method="post" action="process-edit-reservation.php" class="container">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <!-- Other input fields -->
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo $row['payment']; ?>" oninput="calculateChange()"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost" value="<?php echo $row['total_cost']; ?>"><br>
        <label for="kembalian">Kembalian:</label><br>
        <input type="text" id="kembalian" name="kembalian" value="<?php echo $row['kembalian']; ?>"><br>
        <button type="submit">Submit</button>
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
