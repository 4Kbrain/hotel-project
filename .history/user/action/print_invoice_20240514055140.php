<?php
session_start();

require('../../db.php');

$id = $_GET['id']; // pass the ID through query parameter
$sql = "SELECT * FROM transactions WHERE id_transactions = $id";
$result = $con->query($sql);

$row = null;
if ($result !== false && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Invoice not found";
    exit();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Invoice</title>
    <style>
        <?php include 'styles.css'; ?>
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="logo">
            <img src="../../img/logohotel.png" alt="Hotel Logo" width="100px">
        </div>
        <div class="invoice-details">
            <?php if ($row): ?>
                <div class="details">
                    <strong>id Pembayaran</strong>
                    <?php echo $row["id_transactions"]; ?>
                </div>
                <div class="details">
                    <strong>No. Kamar</strong>
                    <?php echo $row["id_kamar"];?>
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
                    <strong>Pembayaran</strong>
                    <?php echo $row["payment"];?>
                </div>
                <div class="details">
                    <strong>Total Cost:</strong>
                    <?php echo $row["total_cost"]; ?>
                </div>
                <div class="details">
                    <strong>Kembalian</strong>
                    <?php echo $row["kembalian"];?>
                </div>
                <div class="details">
                    <strong>Check-in:</strong>
                    <?php echo $row["cin"]; ?>
                </div>
                <div class="details">
                    <strong>Check-out:</strong>
                    <?php echo $row["cout"]; ?>
                </div>
            <?php else: ?>
                <p>No invoice details found.</p>
            <?php endif; ?>
        </div>
        <div class="button-container">
            <button class="print-button" onclick="window.print()">Print Invoice</button>
        </div>
    </div>
</body>
</html>
