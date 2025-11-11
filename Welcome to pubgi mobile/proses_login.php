<?php
/*
File: proses_login.php
Tujuan: Memverifikasi login pengguna dan membuat session
*/

// 1. Mulai Session
// Wajib ada di paling atas untuk bisa menggunakan $_SESSION
session_start();

// 2. Panggil file koneksi
include 'config.php';

// 3. Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password']; // Password mentah yang diinput pengguna

// 4. Keamanan: Gunakan Prepared Statements untuk mencegah SQL Injection
// Cari pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    // 5. Bind parameter username ke query
    mysqli_stmt_bind_param($stmt, "s", $username);

    // 6. Eksekusi query
    mysqli_stmt_execute($stmt);

    // 7. Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // 8. Cek apakah username ditemukan (harus ada 1 baris)
    if (mysqli_num_rows($result) == 1) {
        // Ambil data pengguna sebagai array asosiatif
        $user = mysqli_fetch_assoc($result);

        // 9. Verifikasi Password
        // Kita gunakan password_verify() untuk membandingkan
        // password yang diinput ($password) dengan hash di database ($user['password'])
        if (password_verify($password, $user['password'])) {
            // Jika password cocok

            // 10. Simpan data pengguna ke dalam SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['is_login'] = true; // Penanda bahwa sudah login

            // 11. Arahkan (redirect) pengguna ke halaman dashboard
            header("Location: dashboard.php");
            exit(); // Pastikan script berhenti setelah redirect

        } else {
            // Jika password salah
            echo "Login gagal! Password salah.";
            // Arahkan kembali ke login (index.php) setelah 2 detik
            header("refresh:2;url=index.php");
        }
    } else {
        // Jika username tidak ditemukan
        echo "Login gagal! Username tidak ditemukan.";
        // Arahkan kembali ke login (index.php) setelah 2 detik
        header("refresh:2;url=index.php");
    }

    // 12. Tutup statement
    mysqli_stmt_close($stmt);
} else {
    // Jika persiapan statement gagal
    echo "Error: Gagal menyiapkan statement SQL.";
}

// 13. Tutup koneksi
mysqli_close($koneksi);