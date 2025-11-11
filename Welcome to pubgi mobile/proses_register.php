<?php
/*
File: proses_register.php
Tujuan: Menerima data dari form register.php dan menyimpannya ke database
*/

// 1. Memanggil file koneksi (config.php)
include 'config.php';

// 2. Menerima data yang dikirim dari form
// Kita menggunakan $_POST karena method form adalah "POST"
$nama_lengkap = $_POST['nama_lengkap'];
$username = $_POST['username'];
$password = $_POST['password']; // Ini adalah password mentah (plaintext)

// 3. Keamanan: Hashing Password
// WAJIB! Jangan pernah menyimpan password plaintext ke database.
// Kita gunakan password_hash()
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 4. Keamanan: Mencegah SQL Injection dengan Prepared Statements
// Buat query SQL dengan placeholder (?)
$sql = "INSERT INTO users (nama_lengkap, username, password) VALUES (?, ?, ?)";

// 5. Sebelum melakukan INSERT, kita cek dulu apakah username sudah ada
$cek_sql = "SELECT username FROM users WHERE username = ?";
$cek_stmt = mysqli_prepare($koneksi, $cek_sql);
mysqli_stmt_bind_param($cek_stmt, "s", $username);
mysqli_stmt_execute($cek_stmt);
mysqli_stmt_store_result($cek_stmt);

if (mysqli_stmt_num_rows($cek_stmt) > 0) {
    // Jika username sudah ada di database
    echo "<div style='text-align:center; padding: 20px; font-family: Arial, sans-serif;'>";
    echo "<h2>Registrasi Gagal!</h2>";
    echo "<p>Username <strong>" . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . "</strong> sudah digunakan. Silakan pilih username lain.</p>";
    echo "<a href='register.php'>Coba lagi</a>";
    echo "</div>";

    // Tutup statement pengecekan dan koneksi
    mysqli_stmt_close($cek_stmt);
    mysqli_close($koneksi);
    exit(); // Hentikan eksekusi agar tidak lanjut ke proses insert
}

// Tutup statement pengecekan sebelum lanjut insert
mysqli_stmt_close($cek_stmt);

// 5. Siapkan statement
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    // 6. Bind parameter ke placeholder
    // "sss" berarti kita mengirim tiga data bertipe String
    mysqli_stmt_bind_param($stmt, "sss", $nama_lengkap, $username, $hashed_password);

    // 7. Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika registrasi berhasil
        echo "<div style='text-align:center; padding: 20px; font-family: Arial, sans-serif;'>";
        echo "<h2>Registrasi Berhasil!</h2>";
        echo "<p>Akun Anda telah dibuat. Anda akan diarahkan ke halaman Login dalam 3 detik...</p>";
        echo "</div>";

        // Redirect ke halaman login (index.php) setelah 3 detik
        header("refresh:3;url=index.php");
    } else {
        // Jika eksekusi gagal (misal: error lain di database)
        echo "<div style='text-align:center; padding: 20px; font-family: Arial, sans-serif;'>";
        echo "<h2>Registrasi Gagal!</h2>";
        echo "<p>Error: " . mysqli_error($koneksi) . "</p>";
        echo "<a href='register.php'>Coba lagi</a>";
        echo "</div>";
    }

    // 8. Tutup statement
    mysqli_stmt_close($stmt);
} else {
    // Jika persiapan statement gagal
    echo "Error: Gagal menyiapkan statement SQL.";
}

// 9. Tutup koneksi database
mysqli_close($koneksi);
?>
