<?php
/*
File: logout.php
Tujuan: Menghancurkan session dan mengembalikan pengguna ke halaman login
*/

// 1. Mulai session
// Wajib ada, bahkan hanya untuk menghancurkannya
session_start();

// 2. Hapus semua variabel session
// Mengosongkan array $_SESSION
$_SESSION = array();

// 3. Hancurkan session
// Menghapus data session di server
session_destroy();

// 4. Hapus cookie sesi (jika ada) agar browser juga membuang session id
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// 5. Arahkan (redirect) pengguna kembali ke halaman login/index
header("Location: index.php?status=logout");
exit(); // Hentikan script