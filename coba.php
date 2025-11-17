<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>
<body>
    <?php
        include "navbar.php";
    ?>
    <br></br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>History Transaksi</h1>
            </div>
            <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">TANGGAL TRANSAKSI</th>
                    <th scope="col">PRODUK</th>
                    <th scope="col">HARGA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include "koneksi.php";
                    $query_transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi 
                    where id_pelanggan = '".$_SESSION['id_pelanggan']."' ORDER BY tgl_transaksi DESC");
                    $no=0;
                    while($data_peminjaman=mysqli_fetch_array($query_peminjaman)){
                        $no++;
                    ?>
                    <tr>
                        <td><?=$no?></td>
                        <td><?=$data_peminjaman['tgl_transaksi']?></td>
                        <td>
                            <ol>
                            <?php
                            $query_detail = mysqli_query($koneksi, "SELECT * FROM detail_peminjaman_produk d
                                            JOIN produk b ON b.id_produk = d.id_produk WHERE
                                            id_peminjaman_produk = '".$data_peminjaman['id_peminjaman_produk']."'");
                            while($data_detail = mysqli_fetch_array($query_detail)){
                                echo "<li>".$data_detail['nama_produk']."</li>";
                            }
                            ?>
                            </ol>
                        </td>
                        <?php
                        $query_cek_kembali = mysqli_query($koneksi, "SELECT * FROM pengembalian_produk
                        WHERE id_peminjaman_produk = '".$data_peminjaman['id_peminjaman_produk']."'");
                        if (mysqli_num_rows($query_cek_kembali) > 0) {
                            $data_kembali = mysqli_fetch_array($query_cek_kembali);
                            echo "<td>";
                            echo "<label class='alert alert-success'>
                                Sudah Kembali<br>
                                denda Rp.".$data_kembali['denda']."</label>";
                            echo "</td>";
                            echo "<td></td>";
                        }
                        else{
                            echo "<td><label class='alert alert-danger'>Belum Kembali<br></label></td>";
                            echo "<td><a href='kembali.php?id=".$data_peminjaman['id_peminjaman_produk']."' class='btn btn-warning'
                            onclick='return confirm('Apakah anda yakin mengembalikan produk ini?')'>Kembalikan</a></td>";
                        }
                        ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <?php
        include "footer.php";
    ?>


</body>
</html>