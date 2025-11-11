<?php
// TUGAS UTAMA SISWA:
// 1. Mulai session (session_start())
// 2. Buat pengecekan (if-else)
//    Jika TIDAK ADA session 'username' (atau 'user_id'),
//    maka redirect (paksa) pengguna kembali ke halaman index.php
//

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit(); // Penting untuk menghentikan eksekusi script
}
// Tambahkan header untuk mencegah caching oleh browser sehingga tombol back tidak menampilkan halaman cache
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Dashboard Siswa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="alert alert-success">
            <h4 class="alert-heading">Selamat Datang!</h4>
            <p>
                Halo,
                <strong>
                    <?php
                    // TUGAS SISWA: Tampilkan nama pengguna yang login dari data SESSION
                    // Escape output untuk menghindari XSS
                    echo isset($_SESSION['nama_lengkap']) ? htmlspecialchars($_SESSION['nama_lengkap'], ENT_QUOTES, 'UTF-8') : '';
                    ?>
                </strong>
                Anda telah berhasil login. Ini adalah halaman rahasia Anda.
            </p>
            <hr>
            <p class="mb-0">Halaman ini adalah bukti bahwa Anda berhasil menerapkan sistem session di PHP.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Jika halaman dipulihkan dari bfcache, reload untuk memastikan session validasi berjalan ulang
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
