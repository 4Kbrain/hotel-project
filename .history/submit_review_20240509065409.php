<?php
include "db.php";

$nik = $_POST['nik'];
$existing_review = $conn->query("SELECT * FROM review WHERE NIK = $nik")->fetch_assoc();

if ($existing_review) {
   
    $stmt = $conn->prepare("UPDATE review SET bintang=?, review=? WHERE NIK=?");
    $stmt->bind_param("dsi", $_POST['star'], $_POST['review'], $nik);
} else {
    
    $stmt = $conn->prepare("INSERT INTO review (NIK, bintang, review) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $nik, $_POST['star'], $_POST['review']);
}


$stmt->execute();

echo "Review added successfully";

$stmt->close();
$conn->close();

header("Location: index.php");