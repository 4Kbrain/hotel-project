<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'aditgaming105@gmail.com') {
    header("Location: ../session/index.php");
    exit();
}

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
    $stmt_fetch_total_cost = $con->prepare("SELECT total_cost FROM reservation WHERE id=?");
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

// // Debug: Output the fetched reservation details
// echo "Fetched Reservation Details: <pre>";
// print_r($row);
// echo "</pre>";

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
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }

        .navbar {
            background-color: #4894FE;
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 0p;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .logo span {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .profile {
            position: relative;
            margin-right: 30px;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px 20px 10px 20px;
            display: none;
        }

        .profile:hover .profile-menu {
            display: block;
        }

        .profile-menu a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            transition: all 0.3s ease;
        }

        .profile-menu a:hover {
            background-color: #f0f0f0;
        }

        .sidebar {
            width: 180px;
            background-color: #f0f0f0;
            box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            height: calc(100vh - 40px);
            position: fixed;
            top: 40px;
            left: 0;
            overflow-y: auto;
        }

        .content {
            margin-top: 100px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .sidebar a.active {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        .sidebar a:hover {
            background-color: #d3d3d3;
            color: #0077b6;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back {
            margin-right: 20px;
            background-color: #0056b3;
            color: #fff;
            padding: 5px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .back:hover{
            background-color: #007bff;
        }

        button[type="submit"]:hover {
            background-color: #007bff;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        @media screen and (max-width: 600px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff;">Admin</span></a>
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

    
    <div class="sidebar">
        <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>>Beranda</a>
        <hr>
        <a href="status.php" <?php echo basename($_SERVER['PHP_SELF']) == 'status.php' ? 'class="active"' : ''; ?>>Roombooking</a>
        <hr>

        <a href="transaction.php" <?php echo basename($_SERVER['PHP_SELF']) == 'transaction.php' ? 'class="active"' : ''; ?>>Transactions</a>
        <!-- <a href="payment.php" <?php echo basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'class="active"' : ''; ?>>Payment</a> -->
        <hr>
        <a href="room.php" <?php echo basename($_SERVER['PHP_SELF']) == 'room.php' ? 'class="active"': '';?>>Room</a>
        <hr>

    </div>
<div class="content">
    <h1>Edit Reservation - <?php echo htmlspecialchars($id); ?></h1>

    <form method="post" action="edit-transaction.php" class="container">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <label for="payment">Payment:</label><br>
        <input type="text" id="payment" name="payment" value="<?php echo htmlspecialchars($payment); ?>"
            oninput="calculateChange()"><br>
        <label for="total_cost">Total Cost:</label><br>
        <input type="text" id="total_cost" name="total_cost"
            value="<?php echo htmlspecialchars($row['total_cost']); ?>"><br>
        <label for="kembalian">Kembalian:</label><br>
        <input type="text" id="kembalian" name="kembalian" value="<?php echo htmlspecialchars($kembalian); ?>"><br>
        <a href="status.php" class="back">Back</a>
        <button type="submit">Submit</button>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    </form>
    </div>
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