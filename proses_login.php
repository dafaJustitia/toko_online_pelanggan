<?php
// Nyalakan error reporting untuk jaga-jaga
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_POST){
    $username = $_POST['username'];
    // UBAH NAMA VARIABEL INI AGAR TIDAK BENTROK DENGAN KONEKSI.PHP
    $pass_login = $_POST['password']; 

    include "koneksi.php";
    
    // Gunakan variabel baru ($pass_login) di dalam Query MD5
    $qry_login = mysqli_query($koneksi,"SELECT * FROM pelanggan WHERE username = '".$username."' AND password = '".md5($pass_login)."'");
    
    if(mysqli_num_rows($qry_login) > 0){
        $dt_login = mysqli_fetch_array($qry_login);
        session_start();
        $_SESSION['id_pelanggan'] = $dt_login['id_pelanggan'];
        $_SESSION['nama'] = $dt_login['nama'];
        $_SESSION['status_login'] = true;
        
        // Login Sukses
        header("Location: home.php");
        exit;
    } else {
        // Tampilkan error jika gagal
        echo "<h1>Login Gagal!</h1>";
        echo "Username/Password salah.<br>";
        echo "Input Password: " . $pass_login . "<br>"; // Cek apakah masih berubah?
        echo "MD5 Hash: " . md5($pass_login);
    }
}
?>