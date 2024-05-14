<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../session/index.php");
    exit();
}

if ($_SESSION['user'] !== 'aditgaming105@gmail.com') {
    echo json_encode(["success" => false, "message" => "Admin access only. Go Out"]);
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


$sql = "SELECT * FROM reservation WHERE status = 'Booked'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            overflow-x: hidden;
        }

        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .popup-buttons {
            margin-top: 20px;
        }

        .popup-buttons button {
            padding: 8px 16px;
            margin: 0 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .popup-buttons button.yes {
            background-color: #4CAF50;
            color: #fff;
        }

        .popup-buttons button.no {
            background-color: #f44336;
            color: #fff;
        }



        .navbar {
            background-color: #4894FE;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 40px;
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

        .content {
            padding: 20px;
            margin-top: 50px;
            margin-left: -20px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #content {
            font-family: Arial, sans-serif;
            font-size: 24px;
            color: #333;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    

form {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

input[type="text"] {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: all 0.3s ease;
    margin-right: 10px;
}

input[type="text"]:focus {
    border-color: #0077b6;
    box-shadow: 0 0 5px rgba(0, 119, 182, 0.5);
    outline: none;
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #0077b6;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #005b8c;
}


table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

table th {
    background-color: #f0f0f0;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
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

.action-link:hover {
    background-color: #005b8c;
}

@media screen and (max-width: 768px) {
    .sidebar {
        left: -200px;
    }

    .content {
        margin-left: 0;
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
        <a href="checkout.php" <?php echo basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'class=""active' : ''?>>Checkout</a>
        <hr>

    </div>


    <div class="content">
        <h1>Checkout Users</h1>
        <table class="checkout-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tanggal Check-In</th>
                    <th>Tanggal Check-Out</th>
                    <th>Room Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['</td>";
                    
                }
                ?>
            </tbody>
        </table>
    </div>



</body>

</html>

<?php
$conn->close();
?>