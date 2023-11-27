<?php
include '../../koneksi.php';



$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM orang WHERE prim='$id'");
header("location:../../data/karyawan.php");
?>