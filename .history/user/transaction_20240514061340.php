<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if search query is set
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = $con->real_escape_string($search);  // Prevent SQL injection

// Query to search transactions by Nama or ID Reservation if search is set, otherwise fetch all transactions
$sql = $search ? "SELECT * FROM transactions WHERE nama LIKE '%$search%' OR id_transactions = '$search'" : "SELECT * FROM transactions";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin-left: 200px;
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

        .container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        <hr>
        <a href="room.php" <?php echo basename($_SERVER['PHP_SELF']) == 'room.php' ? 'class="active"' : ''; ?>>Room</a>
        <hr>
    </div>

    <div class="container">
        <h1>Transaction Successful List</h1>
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search by Nama or ID Reservation">
            <button type="submit">Search</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>CIN</th>
                    <th>COUT</th>
                    <th>Payment</th>
                    <th>Total Cost</th>
                    <th>Kembalian</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id_transactions"] . "</td>";
                        echo "<td>" . $row["NIK"] . "</td>";
                        echo "<td>" . $row["nama"] . "</td>";
                        echo "<td>" . $row["cin"] . "</td>";
                        echo "<td>" . $row["cout"] . "</td>";
                        echo "<td>" . $row["payment"] . "</td>";
                        echo "<td>" . $row["total_cost"] . "</td>";
                        echo "<td>" . $row["kembalian"] . "</td>";
                        echo "<td><a class='action-link' href='action/print_invoice.php?id=" . $row["id_transactions"] . "' target='_blank'>Print Invoice</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No transactions found</td></tr>";
                }

                // Update room status setelah cout
                $updateSql = "UPDATE kamar SET room_status = 'Available' WHERE cout <= CURDATE()";
                $con->query($updateSql);

                $con->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>