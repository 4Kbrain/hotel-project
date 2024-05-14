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
    $NIK = $_POST['NIK']);

    // Fetch total_cost from reservation table
    $stmt_fetch_total_cost = $con->prepare("SELECT total_cost AND NIK FROM reservation WHERE id=?");
    $stmt_fetch_total_cost->bind_param("i", $id);
    $stmt_fetch_total_cost->execute();
    $stmt_fetch_total_cost->bind_result($total_cost);
    $stmt_fetch_total_cost->fetch();
    $stmt_fetch_total_cost->close();

    // Check if total_cost is fetched successfully
    if ($total_cost !== null) {
        // Check if payment is higher than or equal to total cost
        if ($payment >= $total_cost) {
            // Calculate kembalian
            $kembalian = $payment - $total_cost;

            // Start a transaction to ensure atomicity
            $con->begin_transaction();

            // Prepare and bind update statement for reservation table
            $stmt_reservation = $con->prepare("UPDATE reservation SET payment=?, kembalian=?, status='Booked' WHERE id=?");
            $stmt_reservation->bind_param("ddi", $payment, $kembalian, $id);
            $stmt_reservation->execute();

            // Update kamar status to In Use and NIK taken from reservation
            $stmt_update_kamar = $con->prepare("UPDATE kamar SET room_status='In Use', NIK=(SELECT NIK FROM reservation WHERE id=?) WHERE id_kamar=(SELECT id_kamar FROM reservation WHERE id=?)");
            $stmt_update_kamar->bind_param("ii", $id, $id);
            $stmt_update_kamar->execute();


            // Update history status to Booked and calculate kembalian
            $stmt_update_history = $con->prepare("UPDATE history SET status='Booked', kembalian=kembalian-? WHERE NIK=(SELECT NIK FROM reservation WHERE id=?)");
            $stmt_update_history->bind_param("di", $payment, $id);
            $stmt_update_history->execute();


            // Insert into transactions
            $stmt_insert_transaction = $con->prepare("INSERT INTO transactions (id_reservation, id_kamar, NIK, nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian) SELECT id, id_kamar, NIK, CONCAT(fname, ' ', lname) AS nama, troom, bed, nroom, nodays, cin, cout, payment, total_cost, kembalian FROM reservation WHERE id=?");
            $stmt_insert_transaction->bind_param("i", $id);
            $stmt_insert_transaction->execute();

            // Commit the transaction if all queries succeed
            $con->commit();
            echo "Transaction updated successfully";
        } else {
            $error = "Payment must be higher than or equal to total cost.";
        }
    } else {
        $error = "Error fetching total cost from reservation table.";
    }
}

// Retrieve the reservation ID from the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    // If reservation ID is not provided, redirect back to status.php
    header("Location: status.php");
    exit();
}

// Fetch the data from the reservation table using the retrieved ID
$stmt_fetch_reservation = $con->prepare("SELECT * FROM reservation WHERE id=?");
$stmt_fetch_reservation->bind_param("i", $id);
$stmt_fetch_reservation->execute();
$result = $stmt_fetch_reservation->get_result();
$row = $result->fetch_assoc();
$stmt_fetch_reservation->close();

// Debug: Output the retrieved reservation ID
echo "Reservation ID: " . $id . "<br>";

// Debug: Check if reservation ID is provided
if (!$id) {
    echo "Reservation ID is not provided.";
    exit();
}

// Debug: Output the fetched reservation details
echo "Fetched Reservation Details: <pre>";
print_r($row);
echo "</pre>";

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
        <label for="NIK">NIK:</label><br>
        <input type="text" id="NIK" name="NIK" value="<?php echo $NIK ?>"><br>
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo htmlspecialchars($payment); ?>"
            oninput="calculateChange()"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost"
            value="<?php echo htmlspecialchars($row['total_cost']); ?>"><br>
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