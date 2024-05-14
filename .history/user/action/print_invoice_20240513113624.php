<?php
session_start();

require('');

$id = $_GET['id']; // pass the ID through query parameter
$sql = "SELECT * FROM transactions WHERE id = $id";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Invoice not found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Invoice</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.logo {
    text-align: center;
    margin-bottom: 20px;
}

.logo img {
    max-width: 200px;
    height: auto;
}

.invoice-details {
    margin-bottom: 20px;
}

.details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.details strong {
    width: 120px;
    font-weight: bold;
}

.button-container {
    text-align: center;
    margin-top: 20px;
}

.print-button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.print-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="logo">
            <img src="path/to/hotel_logo.png" alt="Hotel Logo">
        </div>
        <div class="invoice-details">
            <div class="details">
                <strong>ID:</strong>
                <?php echo $row["id"]; ?>
            </div>
            <div class="details">
                <strong>NIK:</strong>
                <?php echo $row["NIK"]; ?>
            </div>
            <div class="details">
                <strong>Nama:</strong>
                <?php echo $row["nama"]; ?>
            </div>
            <div class="details">
                <strong>Total Cost:</strong>
                <?php echo $row["total_cost"]; ?>
            </div>
            <div class="details">
                <strong>Check-in:</strong>
                <?php echo $row["cin"]; ?>
            </div>
            <div class="details">
                <strong>Check-out:</strong>
                <?php echo $row["cout"]; ?>
            </div>
        </div>
        <div class="button-container">
            <button class="print-button" onclick="window.print()">Print Invoice</button>
        </div>
    </div>
</body>
</html>
