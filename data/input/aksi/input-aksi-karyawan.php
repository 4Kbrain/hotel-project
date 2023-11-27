<?php
include '../../../koneksi.php';

$id = $_POST['prim'];
$first_name = $_POST['nama_depan'];
$last_name= $_POST['nama_belakang'];
$email = $_POST['email'];
$pass =$_POST['password'];

mysqli_query($conn, "INSERT INTO orang values('$id','$first_name','$last_name','$email','$pass')");
header("location:../../karyawan.php");
?>
