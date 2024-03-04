<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    
if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
    exit();
}

function getUserPayments($conn)
{
    $sql = "SELECT p.id_payment,p.id_reservation, p.confirm, r.total_cost
            FROM payment p
            JOIN roombook r ON p.id_reservation = r.id_reservation";
    $result = $conn->query($sql);
    $payments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }

    return $payments;
}

function changePaymentStatus($conn, $payment_id, $new_status)
{
    $update_sql = "UPDATE payment SET confirm = '$new_status' WHERE id_payment = $payment_id";
    $conn->query($update_sql);
}

$payments = getUserPayments($conn);

// Handle Action from Change
if (isset($_GET['change_id'])) {
    $change_id = $_GET['change_id'];
    changePaymentStatus($conn, $change_id, 'Paid');
    header("Location: payment.php");
    exit();
}

// Payment paginations

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 10;
$totalPayments = count($payments);
$totalPages = ceil($totalPayments / $perPage);
$start = ($page - 1) * $perPage;
$end = $start + $perPage;
$entriesPerPage = 10;
$paymentsToShow = array_slice($payments, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
           ::-webkit-scrollbar {
            display: none;
        }
        
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background-color: #4894FE;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-right: 30px;
            position: relative;
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

        .content {
            margin-left: 180px;
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            overflow-x: hidden;
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

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #555;
            font-weight: bold;
            font-size: 
            18px;
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

        .payment-container {
            margin-left: 200px;
            padding: 20px;
            text-align: center;
        }

        .payment-table {
            width: 99%;
            margin-left: 10px;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .payment-table th {
            background-color: #f0f0f0;
        }

        .action-column {
            width: 100px;
        }

        .action-link {
            display: block;
            text-align: center;
            padding: 5px;
            text-decoration: none;
            background-color: #0077b6;
            color: white;
            border-radius: 5px;
            margin: 5px auto;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination-link {
            text-decoration: none;
            color: #333;
            padding: 5px 10px;
            border: 1px solid #ddd;
            margin-right: 5px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pagination-link.active {
            background-color: #0077b6;
            color: white;
        }

        .pagination-link:hover {
            background-color: #f0f0f0;
        }

        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 5px;
        text-align: center;
    }

    button {
        padding: 10px 20px;
        margin: 10px;
        cursor: pointer;
        border-radius: 5px;
        border: none;
    }

    #confirm-btn {
        background-color: #4CAF50;
        color: white;
    }

    #cancel-btn {
        background-color: #f44336;
        color: white;
    }
    </style>
</head>

<body>

    <div class="top-navbar">
        <div class="logo">
            <a href="#"><span style="color:#fff">Admin</span></a>
        </div>
        <div class="profile">
            <span style="color:#fff;">Username</span>
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
        <a href="payment.php" <?php echo basename($_SERVER['PHP_SELF']) == 'payment.php' ? 'class="active"' : ''; ?>>Payment</a>
        <!-- <hr> -->
        <!-- <a href="room.php" <?php echo basename($_SERVER['PHP_SELF']) == 'room.php' ? 'class="active"' : ''; ?>>Room</a> -->
        <hr>
        <a href="transaction.php" <?php echo basename($_SERVER['PHP_SELF']) == 'transaction.php' ? 'class="active"' : '';?>>Transaction</a>
    
    </div>

    <br>
    <div class="payment-container">
        <h2>Data Payment</h2>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>ID Payment</th>
                    <th>ID Reservation</th>
                    <th>Amount Paid</th>
                    <th>Description</th>
                    <th class="action-column">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) : ?>
                    <tr>
                        <td><?php echo $payment['id_payment']; ?></td>

                        <td><?php echo $payment['id_reservation']; ?></td>

                        <td><?php echo isset($payment['total_cost']) ? $payment['total_cost'] : 'Error :)'; ?></td>
                    
                        <td><?php echo $payment['confirm']; ?></td>
                        <td class="action-column">
    <?php if (isset($payment['confirm']) && $payment['confirm'] === 'Not Confirmed') : ?>
        <a class="action-link" href="#" onclick="showConfirmationModal(<?php echo $payment['id_payment']; ?>)">Change</a>
    <?php endif; ?>
    <a class="action-link" href="action/print_payment.php?id=<?php echo $payment['id_payment']; ?>">Print</a>
</td>

<div id="confirmation-modal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to change the payment status to 'Paid'?</p>
        <button id="confirm-btn">Confirm</button>
        <button id="cancel-btn">Cancel</button>
    </div>
</div>

<script>
    function showConfirmationModal(paymentId) {
        const modal = document.getElementById('confirmation-modal');
        modal.style.display = 'block';

        const confirmBtn = document.getElementById('confirm-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        confirmBtn.onclick = function() {
            window.location.href = `payment.php?change_id=${paymentId}`;
        };

        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    }
</script>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
                <?php
                $totalEntries = mysqli_num_rows($conn->query("SELECT id_payment FROM payment"));
                $totalPages = ceil($totalEntries / $entriesPerPage);

                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='pagination-link";
                    echo ($i == $page) ? " active'" : "'";
                    echo ">$i</a> ";
                }
                ?>
            </div>
    </div>

</body>

</html>
