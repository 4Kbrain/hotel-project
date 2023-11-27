                        
                        
                        <table border="1">

                        <th>HARGA</th>
                        <th>KAPASITAS</th>
                        <th>Aksi</th>
                        <tr>
                            <td><?php echo $row['id_kamar']?></td>
                            <td><?php echo $row['nomor_kamar']?></td>
                            <td><?php echo $row['tipe_kamar']?></td>
                            <td><?php echo $row['harga']?></td>
                            <td><?php echo $row['kapasitas']?></td>
                            <td><a href="./hapus/hapus-kamar.php?id=<?php echo $row['id_kamar'];?>" style="margin-right:7px;">Hapus</a>
                                <a href="./edit/edit-siswa.php?id=<?php echo $row['id_kamar'];?>">Edit</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            
            ?>
        </div>
        <div class="tab-pane fade" id="guests" role="tabpanel" aria-labelledby="guests-tab">
            <?php
            include "koneksi.php";
            $data = mysqli_query($mysqli, "SELECT * FROM tamu");
            while ($row = mysqli_fetch_array($data)) {
                ?>
                <div>
                    <table class="table table-striped table-hover" style="text-align:center">
                        <th>ID</th>
                        <th>NAMA DEPAN</th>
                        <th>NAMA BELAKANG</th>
                        <th>ALAMAT</th>
                        <th>EMAIL</th>
                        <th>PASSWORD</th>
                        <th>Aksi</th>
                        <tr>
                            <td><?php echo $row['id_tamu']?></td>
                            <td><?php echo $row['nama_depan']?></td>
                            <td><?php echo $row['nama_belakang']?></td>
                            <td><?php echo $row['alamat']?></td>
                            <td><?php echo $row['email']?></td>
                            <td><?php echo $row['password']?></td>
                            <td><a href="./hapus/hapus-tamu.php?id=<?php echo $row['id_tamu'];?>" style="margin-right:7px;">Hapus</a>
                                <a href="./edit/edit-tamu.php?id=<?php echo $row['id_tamu'];?>">Edit</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="tab-pane fade" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
            <?php
            include "koneksi.php";
            $data = mysqli_query($mysqli, "SELECT * FROM pemesanan");
            while ($row = mysqli_fetch_array($data)) {
                ?>
                <div>
                    <table class="table table-striped table-hover" style="text-align:center">
                        <th>ID PEMESANAN</th>
                        <th>ID TAMU</th>
                        <th>ID KAMAR</th>
                        <th>TGL CHECK IN</th>
                        <th>TGL CHECK OUT</th>
                        <th>Aksi</th>
                        <tr>
                            <td><?php echo $row['id_pemesanan']?></td>
                            <td><?php echo $row['id_tamu']?></td>
                            <td><?php echo $row['id_kamar']?></td>
                            <td><?php echo $row['tanggal_checkin']?></td>
                            <td><?php echo $row['tanggal_checkout']?></td>
                            <td><a href="./hapus/hapus-pemesanan.php?id=<?php echo $row['id_pemesanan'];?>" style="margin-right:7px;">Hapus</a>
                                <a href="./edit/edit-pemesanan.php?id=<?php echo $row['id_pemesanan'];?>">Edit</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="tab-pane fade" id="facilities" role="tabpanel" aria-labelledby="facilities-tab">
            <!-- Isi dengan formulir atau tabel untuk data fasilitas -->
        </div>
    </div>
</div>
</body>
</html>
