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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $currency = $_POST['currency'];

    $update_sql = "UPDATE reservation SET status='Available' WHERE id='$id'";
    $conn->query($update_sql);

    $insert_sql = "INSERT INTO transactions (NIK, nama, total_cost, cin, cout, currency) SELECT NIK, CONCAT(fname, ' ', lname), total_cost, cin, cout, '$currency' FROM reservation WHERE id='$id'";
    $conn->query($insert_sql);

header("Location: status.php")
exit();

if (!isset($_GET['id'])) {
    header("Location: status.php");
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <style>
        
        body, html {
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

        h1{
            margin: 10px;
            padding: 20px;
            text-align:center;
            font-size: 28px;
            top: 100%;
            display: block;
            margin-left: 50px;
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

    
</body>
<h1>Edit Transaction - </h1>

<?php
	include '../koneksi.php';
	$id = $_GET['id'];
	$data = mysqli_query($koneksi,"select * from roombook where id='$id'");
	while($d = mysqli_fetch_array($data)){
		?>
		<form method="post" action="edit-transaction.php">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="currency">Money Currency:</label>
    <input type="text" id="currency" name="currency">
    <button type="submit">Submit</button>
</form>
<?php
$conn->close();
?>
</html>
    