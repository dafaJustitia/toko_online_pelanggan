<?php
    // Settingan Koneksi Azure Database
    // PENTING: Ganti "isi-nama-server-di-sini" dengan Server Name dari Azure
    $serverName = "tokoonline.mysql.database.azure.com"; 
    
    $userName = "toko_user";         // Username yang kita buat tadi
    $password = "Shofi05@";   // Password yang kita buat tadi
    $database = "tokoonline";       // Nama database di Azure (bukan toko_online2)

    // Buat koneksi
    // Catatan: Port 3306 biasanya default, tapi Azure kadang butuh SSL (opsional)
    // Untuk saat ini kita coba koneksi standar dulu.
    $koneksi = mysqli_connect($serverName, $userName, $password, $database);

    // Cek koneksi
    if(!$koneksi){
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
    // else {
    //    echo "Koneksi Berhasil";
    // }
?>