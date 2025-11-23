<?php
    // 1. NYALAKAN ERROR REPORTING (Supaya ketahuan kalau ada error)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    include "koneksi.php";

    // Cek apakah koneksi database nyambung
    if (!$koneksi) {
        die("FATAL ERROR: Gagal koneksi ke database. " . mysqli_connect_error());
    }

    // Ambil data keranjang
    $keranjang = @$_SESSION['keranjang'];

    // 2. PERBAIKAN PHP 8 (Cek dulu apakah keranjang ada isinya)
    // Kalau langsung count() pada data kosong, PHP 8 akan Crash!
    if (empty($keranjang)) {
        echo "<h3>Keranjang Belanja Kosong</h3>";
        echo "<a href='home.php'>Kembali Belanja</a>";
        exit; // Berhenti di sini
    }

    // Jika keranjang ada isinya, baru kita proses (Kodingan Aslimu)
    if (count($keranjang) > 0) {
        $tgl_transaksi = date('Y-m-d');
        
        // Insert Transaksi
        $query_transaksi = mysqli_query($koneksi, "INSERT INTO transaksi (id_pelanggan, tgl_transaksi)
        VALUES ('".$_SESSION['id_pelanggan']."', '".$tgl_transaksi."')");

        // Cek Error SQL Transaksi
        if (!$query_transaksi) {
            die("ERROR SQL TRANSAKSI: " . mysqli_error($koneksi) . "<br>Pastikan tabel 'transaksi' sudah ada di database Azure.");
        }

        if ($query_transaksi) {
            $id = mysqli_insert_id($koneksi);
            
            foreach ($keranjang as $key => $value) {
                $qty = $value['qty'];
                $harga = $value['harga'];
                $subtotal = $qty * $harga;
                
                // Insert Detail
                $query_detail = mysqli_query($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_produk, qty, subtotal)
                VALUES ('".$id."', '".$value['id_produk']."', '".$qty."', '".$subtotal."')");
                
                // Cek Error SQL Detail
                if (!$query_detail) {
                    die("ERROR SQL DETAIL: " . mysqli_error($koneksi));
                }
            }
            
            unset($_SESSION['keranjang']);
            
            // PENTING: Pastikan file transaksi.php benar-benar ada (huruf kecil)
            echo "<script>alert('Anda Berhasil Membeli Produk');location.href='transaksi.php'</script>";
        }
        else{
            echo "<script>alert('Gagal Membeli Produk');location.href='checkout.php'</script>";
        }
    }
?>