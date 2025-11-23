<?php
// 1. NYALAKAN PELAPORAN ERROR (Agar error PHP muncul di layar, bukan 404)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah file koneksi benar-benar ada
    if(!file_exists("koneksi.php")){
        die("Error: File koneksi.php tidak ditemukan!");
    }

    include "koneksi.php";

    // Cek apakah koneksi database terhubung
    if (!$koneksi) {
        die("Error Koneksi Database: " . mysqli_connect_error());
    }

    // 2. QUERY DENGAN ERROR HANDLING
    // Kita gunakan md5 sesuai kodingan awalmu
    $sql = "SELECT * FROM pelanggan WHERE username = '".$username."' AND password = '".md5($password)."'";
    
    $qry_login = mysqli_query($koneksi, $sql);

    // 3. CEK APAKAH QUERY GAGAL (Penyebab utama Crash/404)
    if (!$qry_login) {
        die("Error Query SQL: " . mysqli_error($koneksi) . "<br>Cek apakah nama tabel 'pelanggan' sudah benar di database?");
    }

    // 4. CEK JUMLAH DATA
    if(mysqli_num_rows($qry_login) > 0){
        $dt_login = mysqli_fetch_array($qry_login);
        
        session_start();
        $_SESSION['id_pelanggan'] = $dt_login['id_pelanggan'];
        $_SESSION['nama'] = $dt_login['nama'];
        $_SESSION['status_login'] = true;
        
        // Redirect dengan 'L' besar (standar yang benar)
        header("Location: home.php");
        exit; // Selalu pakai exit setelah header location
    } else {
        // Jika login gagal, kita tampilkan teks dulu (jangan redirect) untuk memastikan script jalan
        echo "<h1>Login Gagal!</h1>";
        echo "Username/Password tidak ditemukan di database.<br>";
        echo "Input: $username <br>";
        echo "MD5 Password: " . md5($password);
        // Matikan redirect otomatis dulu untuk debugging
        // echo "<script>alert('Username dan Password tidak benar');location.href='index.php';</script>";
    }
} else {
    echo "Akses dilarang (Bukan method POST)";
}
?>