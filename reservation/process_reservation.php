<?php
session_start();

if (!isset($_SESSION['user'])) {
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
    $NIK = $_SESSION['user'];
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $troom = mysqli_real_escape_string($conn, $_POST['troom']);
    $bed = mysqli_real_escape_string($conn, $_POST['bed']);
    $nroom = mysqli_real_escape_string($conn, $_POST['nroom']);
    $cin = mysqli_real_escape_string($conn, $_POST['cin']);
    $cout = mysqli_real_escape_string($conn, $_POST['cout']);

    // Hitung jumlah hari menginap
    $checkin = new DateTime($cin);
    $checkout = new DateTime($cout);
    $nodays = $checkin->diff($checkout)->days;

    // Hitung total biaya
    $roomTypeCosts = [
        'Superior Room' => 100,
        'Deluxe Room' => 150,
        'Guest House' => 200,
        'Single Room' => 80,
    ];

    $roomCost = $roomTypeCosts[$troom] ?? 0;
    $bedCost = $bed === 'None' ? 0 : 20; 
    $totalCost = ($roomCost + $bedCost) * $nroom * $nodays;

    // Check kalau email sudah ada di tabel reservation
    $check_email_sql = "SELECT * FROM reservation WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_result = $check_email_stmt->get_result();
    if ($check_email_result->num_rows > 0) {
        echo '<script>alert("Email already exists. Please use a different email.");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
        exit(); // Stop execution if email already exists
    }

    // Insert data ke dalam tabel reservation
    $status = 'Waiting For Approval'; // Atau nilai lain sesuai dengan kebutuhan
    $insert_reservation_sql = "INSERT INTO reservation (NIK, fname, lname, email, phone, troom, bed, nroom, cin, cout, status, nodays, total_cost)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_reservation_stmt = $conn->prepare($insert_reservation_sql);
    $insert_reservation_stmt->bind_param("ssssssssssssd", $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $totalCost);
    if ($insert_reservation_stmt->execute()) {
        // Insert ke tabel payment
        $id_reservation = $insert_reservation_stmt->insert_id;
        $insert_payment_sql = "INSERT INTO payment (id_reservation, confirm, total_cost) VALUES (?, 'Not Confirmed', ?)";
        $insert_payment_stmt = $conn->prepare($insert_payment_sql);
        $insert_payment_stmt->bind_param("id", $id_reservation, $totalCost);
        if ($insert_payment_stmt->execute()) {
            echo '<script>alert("Your booking application has been sent!");</script>';
            echo '<script>window.location.replace("../index.php");</script>';
        } else {
            echo '<script>alert("Error adding user to the database. Check your details and try again.");</script>';
            echo '<script>window.location.replace("reservation_form.php");</script>';
        }
    } else {
        echo '<script>alert("Error adding user to the database: ' . $conn->error . '");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
    }
}

$conn->close();
?>
