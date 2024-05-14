<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo '<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; display: flex; justify-content: center; align-items: center;">';
    echo "Session variables set:<br>";
    foreach ($_SESSION as $key => $value) {
        echo "$key = $value<br>";
    }
    echo '</div>';

    exit();
}

// Ambil NIK dari sesi
$nik = $_SESSION['NIK'];
echo "Session Variables Set:<br>";
echo "User = " . $_SESSION['user'] . "<br>";
echo "NIK = " . $nik;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grand";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $NIK = $_SESSION['user']; // Incorrect, use $_SESSION['NIK']
    $NIK = $nik; // Use the NIK from the session
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
    $status = 'Waiting For Approval'; // Or any other value as needed
    $insert_reservation_sql = "INSERT INTO reservation (NIK, fname, lname, email, phone, troom, bed, nroom, cin, cout, status, nodays, total_cost)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_reservation_stmt = $conn->prepare($insert_reservation_sql);
    $insert_reservation_stmt->bind_param("ssssssssssssd", $NIK, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $totalCost);

    if ($insert_reservation_stmt->execute()) {
        // Check if the reservation was inserted successfully
        $check_reservation_sql = "SELECT * FROM reservation WHERE NIK = ?";
        $check_reservation_stmt = $conn->prepare($check_reservation_sql);
        $check_reservation_stmt->bind_param("s", $NIK);
        $check_reservation_stmt->execute();
        $check_reservation_result = $check_reservation_stmt->get_result();

        if ($check_reservation_result->num_rows > 0) {
            // Insert ke tabel payment
            $id_reservation = $insert_reservation_stmt->insert_id;
            $insert_payment_sql = "INSERT INTO payment (id_reservation, confirm, total_cost) VALUES (?, 'Not Confirmed', ?)";
            $insert_payment_stmt = $conn->prepare($insert_payment_sql);
            $insert_payment_stmt->bind_param("id", $id_reservation, $totalCost);
            if ($insert_payment_stmt->execute()) {
                echo '<script>alert("Your booking application has been sent!");</script>';
                // Remove the redirection for testing purposes
                // echo '<script>window.location.replace("myreservation.php");</script>';
            } else {
                // Display a generic success message
                echo '<script>alert("Your booking application has been sent!");</script>';
            }
            

}

$conn->close();
?>