<h3>Edit Data Karyawan</h3>

<?php

include '../../koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM orang WHERE prim = '$id'");

while($row = mysqli_fetch_array($data)){
?>

<form method="post" action="aksi/edit-aksi-karyawan.php">
    <table>
    <tr>
            <td>Id Karyawan</td>
            <td><input type="number" name="prim" ></td>
        </tr>
        <tr>
            <td>First name</td>
            <td><input type="text" name="nama_depan"></td>
        </tr>
        <tr>
            <td>Last name</td>
            <td><input type="text" name="nama_belakang"></td>
        </tr>
        <tr>
            <td>email</td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr>
            <td>passwd</td>
            <td><input type="text" name="password"></td>
        </tr>
        <tr>
           <td><input type="submit" value="simpan"></td> 
        </tr>
    </table>
</form>
<?php
}
