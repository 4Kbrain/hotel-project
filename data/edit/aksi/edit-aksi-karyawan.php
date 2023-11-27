<?php

include '../../koneksi.php';

$id = $_POST['prim'];
$first_name = $_POST['nama_depan'];
$last_name = $_POST['nama_belakang'];
$email = $_POST['email'];
$pass = $_POST['password'];

mysqli_query($conn, "UPDATE orang SET prim='$id_petugas', nama_depan='$first_name', nama_belakang='$last_name',email='$email' password='$pass' WHERE prim='$id_petugas' ");

header("location:../../karyawan.php");

