<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

// Include database connection configuration
require_once "../db.php";

// Initialize error variable to avoid undefined variable warning
$error = '';

// Define variables
$id = $payment = $total_cost = $kembalian = '';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $id = intval($_POST['id']);
    $payment = floatval($_POST['payment']);

    // Fetch total_cost from reservation table
    // Fetch total_cost from reservation table
    $stmt_fetch_total_cost = $con->prepare("SELECT total_cost FROM reservation WHERE id=?");
    $stmt_fetch_total_cost->bind_param("i", $id);
    $stmt_fetch_total_cost->execute();
    $stmt_fetch_total_cost->bind_result($total_cost);
    $stmt_fetch_total_cost->fetch();
    $stmt_fetch_total_cost->close();

    // Check if total_cost is fetched successfully
    if ($total_cost !== null) {
        // Display the fetched total_cost in the input field
        $total_cost = htmlspecialchars($total_cost);
    } else {
        // Handle the case where total_cost is not fetched
        // You can set a default value or handle the error as per your requirement
        $total_cost = "Total cost not available";
    }


    // Check if total_cost is fetched successfully
    if ($total_cost !== null) {
        // Check if payment is higher than total cost
        if ($payment >= $total_cost) {
            // Calculate kembalian
            $kembalian = $payment - $total_cost;

            // Start a transaction to ensure atomicity
            $con->begin_transaction();

            // Prepare and bind update statement for reservation table
            $stmt_reservation = $con->prepare("UPDATE reservation SET payment=?, kembalian=? WHERE id=?");
            $stmt_reservation->bind_param("ddi", $payment, $kembalian, $id);

            // Execute the update statement for reservation
            if ($stmt_reservation->execute()) {
                // Handle other database operations here...

                // Commit the transaction if all queries succeed
                $con->commit();
                echo "Transaction updated successfully";
            } else {
                // Rollback the transaction if any operation fails
                $con->rollback();
                $error = "Error updating reservation table: " . $stmt_reservation->error;
            }

            // Close prepared statement
            $stmt_reservation->close();
        } else {
            $error = "Payment must be higher than total cost.";
        }
    } else {
        $error = "Error fetching total cost from reservation table.";
    }
}


// Close the database connection
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

    <h1>Edit Reservation - <?php echo htmlspecialchars($id); ?></h1>

    <form method="post" action="edit-transaction.php" class="container">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <!-- Other input fields -->
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo htmlspecialchars($payment); ?>"
            oninput="calculateChange()"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost" value="<?php echo $total_cost; ?>"><br>

        <label for="kembalian">Kembalian:</label><br>
        <input type="text" id="kembalian" name="kembalian" value="<?php echo htmlspecialchars($kembalian); ?>"><br>
        <button type="submit">Submit</button>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
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