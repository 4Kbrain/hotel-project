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

    // Fetch the id_kamar based on the selected room type
    $fetch_kamar_sql = "SELECT id_kamar FROM kamar WHERE type_room = ?";
    $fetch_kamar_stmt = $conn->prepare($fetch_kamar_sql);

    // Check if the prepare() method failed
    if (!$fetch_kamar_stmt) {
        die('Error preparing query: ' . $conn->error);
    }

    // Fetch the id_kamar based on the selected room type
    $fetch_kamar_sql = "SELECT id_kamar FROM kamar WHERE type_room = ?";
    $fetch_kamar_stmt = $conn->prepare($fetch_kamar_sql);

    // Check if the prepare() method failed
    if (!$fetch_kamar_stmt) {
        die('Error preparing query: ' . $conn->error);
    }

    $fetch_kamar_stmt->bind_param("s", $troom);
    $fetch_kamar_stmt->execute();
    $fetch_kamar_result = $fetch_kamar_stmt->get_result();

    if ($fetch_kamar_result->num_rows > 0) {
        $row = $fetch_kamar_result->fetch_assoc();
        $id_kamar = $row['id_kamar'];

        // Insert the reservation data into the database
        $insert_reservation_sql = "INSERT INTO reservation (NIK, id_kamar, fname, lname, email, phone, troom, bed, nroom, cin, cout, status, nodays, payment, total_cost)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_reservation_stmt = $conn->prepare($insert_reservation_sql);
        $insert_reservation_stmt->bind_param("ssssssssssssddd", $NIK, $id_kamar, $fname, $lname, $email, $phone, $troom, $bed, $nroom, $cin, $cout, $status, $nodays, $totalCost, $payment);

        if ($insert_reservation_stmt->execute()) {
            // Get the id of the inserted reservation
            $id_reservation = $insert_reservation_stmt->insert_id;

            // Update the room status in kamar table to 'In Use'
            $update_kamar_sql = "UPDATE kamar SET room_status='In Use' WHERE id_kamar=?";
            $update_kamar_stmt = $conn->prepare($update_kamar_sql);
            $update_kamar_stmt->bind_param("s", $id_kamar);
            $update_kamar_stmt->execute();

            // Insert the transaction record into the transactions table
            $nama = $fname . ' ' . $lname;
            $insert_transaction_sql = "INSERT INTO transactions (id_reservation, NIK, nama, cin, cout, payment, total_cost, `change`) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_transaction_stmt = $conn->prepare($insert_transaction_sql);
            $insert_transaction_stmt->bind_param("isssssdd", $id_reservation, $NIK, $nama, $cin, $cout, $payment, $totalCost, $change);

            if ($insert_transaction_stmt->execute()) {
                echo '<script>alert("Your booking application has been sent!");</script>';
                // Redirect to myreservation.php
                header("Location: myreservation.php");
                exit();
            } else {
                echo "Error inserting transaction: " . $conn->error;
            }
        } else {
            echo "Error inserting reservation: " . $conn->error;
        }

    } else {
        // Handle case where no matching room is found
        echo '<script>alert("Selected room type is not available.");</script>';
        echo '<script>window.location.replace("reservation_form.php");</script>';
    }

}

$conn->close();
?>