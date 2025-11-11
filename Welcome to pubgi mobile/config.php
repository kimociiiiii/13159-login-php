<?php
/*
File: config.php
Tujuan: Koneksi ke database MySQL
*/

// Variabel untuk koneksi database
$db_host = "localhost";   // Nama server (biasanya localhost)
$db_user = "root";        // Username database (default XAMPP/MAMP/)
$db_pass = "";            // Password database (default XAMPP/MAMP kosong)
$db_name = "db_sekolah";  // Nama database yang dibuat sebelumnya

// Membuat koneksi menggunakan MySQLi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (mysqli_connect_errno()) {
    // Jika koneksi gagal, tampilkan pesan error dan hentikan script
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika berhasil, script akan lanjut.
// Kita tidak perlu "echo" apapun di sini.