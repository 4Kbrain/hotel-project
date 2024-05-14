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
    $currency = $_POST['payment'];

    $update_sql = "UPDATE reservation SET status='Available' WHERE id='$id'";
    $conn->query($update_sql);

    $insert_sql = "INSERT INTO transactions (NIK, nama, payment, total_cost, cin, cout) SELECT NIK, CONCAT(fname, ' ', lname), total_cost, cin, cout, '$currency' FROM reservation WHERE id='$id'";
    $conn->query($insert_sql);

    header("Location: status.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: status.php");
    exit();
}

$id = $_GET['id'];
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        /* Add CSS here */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            overflow-x: hidden;
        }

        /* Rest of the CSS for styling */
        /* ... */
    </style>
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
                <a href="#">Profile</a><hr>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <h1>Edit Transaction - <?php echo $id; ?></h1>

    <form method="post" action="edit-transaction.php" class="container">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="currency">Money Currency:</label>
        <input type="text" id="currency" name="currency">
        <button type="submit">Submit</button>
    </form>

</body>

</html>

<?php
$conn->close();
?>
