
<?php 

$password = "password";

$hashpassword = password_hash($password, PASSWORD_DEFAULT);
?>
<h3>Tambah Data Karyawan</h3>
<form method="post" action="aksi/input-aksi-karyawan.php">
    <table>
        <tr>
            <td>First Name</td>
            <td><input type="text" name="prim" ></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" name="nama_depan"></td>
        </tr>
        <tr>
            <td>Email </td>
            <td><input type="text" name="nama_belakang"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr>
            <td>password</td>
            <td><input type="text" name="password"</td>
        </tr>
        <tr>
        <tr>
            <td><input type="submit" value="simpan"></td>
        </tr>
    </table>
</form>
