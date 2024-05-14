<?php
include "db.php";

$star = $_POST['star'];
$review = $_POST['review'];

$stmt = $con->prepare("INSERT INTO review (bintang, review) VALUES (?, ?)");
$stmt->bind_param("ds", $star, $review); // Use 'd' for double/decimal type
$stmt->execute();

echo "Review added successfully";

$stmt->close();
$con->close();

