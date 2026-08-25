<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../sign/member/sign_in.php");
  exit;
}

require "../config/config.php";

$nisn = $_SESSION["member"]["nisn"];
$nama = $_SESSION["member"]["nama"];

// Ambil data siswa terbaru
$memberQuery = queryReadData("SELECT * FROM member WHERE nisn = '$nisn'");
$memberData = !empty($memberQuery) ? $memberQuery[0] : $_SESSION["member"];

// Statistik Member
$myPinjam = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM peminjaman WHERE nisn = '$nisn'"))[0] ?? 0;
$myKembali = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM pengembalian WHERE nisn = '$nisn'"))[0] ?? 0;
$myDenda = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(denda) FROM pengembalian WHERE nisn = '$nisn' AND denda > 0"))[0] ?? 0;
$fotoMember = !empty($memberData["foto"]) ? $memberData["foto"] : "default_member.png";
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Siswa - SkanicPerpus</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style/main.css">
    <link rel="icon" href="../assets/logoPerpus.jpg" type="image/jpeg">
  </head>
  <body>
    
    <!-- Modern Member Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
      <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="dashboardMember.php">
          <img src="../assets/SkanicLogin.png" alt="SkanicPerpus" height="46" onerror="this.src='../assets/logo_skanic.png'">
        </a>

        <div class="d-flex align-items-center gap-3 ms-auto">
          <div class="dropdown">
            <button class="user-profile-btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../imgDB/avatar/<?= htmlspecialchars($fotoMember); ?>" alt="Member Avatar" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" onerror="this.src='../assets/memberLogo.png'">
              <div class="d-none d-sm-block text-start">
                <div class="fw-bold text-dark text-capitalize" style="font-size: 0.85rem;"><?= htmlspecialchars($nama); ?></div>
                <div class="text-muted" style="font-size: 0.7rem;">Siswa • <?= htmlspecialchars($memberData["kelas"] ?? ""); ?></div>
              </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern mt-2">
              <li class="px-3 py-2 border-bottom">
                <div class="fw-bold text-dark text-capitalize"><?= htmlspecialchars($nama); ?></div>
                <small class="text-muted">NISN: <?= htmlspecialchars($nisn); ?></small>
                <div class="small text-secondary mt-1"><?= htmlspecialchars($memberData["jurusan"] ?? ""); ?></div>
              </li>
              <li>
                <a class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2" href="profil/profilMember.php">
                  <i class="fa-solid fa-user-gear text-primary"></i> Profil & Ganti Foto
                </a>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li>
                <a class="dropdown-item rounded-3 py-2 text-danger fw-semibold d-flex align-items-center gap-2" href="signOut.php">
                  <i class="fa-solid fa-right-from-bracket"></i> Keluar (Sign Out)
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container-fluid px-lg-4">
        
        <!-- Welcome Banner -->
        <div class="hero-section py-4 px-4 mb-4">
          <div class="row align-items-center g-3">
            <div class="col-lg-7">
              <div class="badge-status primary mb-2">
                <i class="fa-solid fa-graduation-cap"></i> Portal Siswa SMKN 1 Ciomas
              </div>
              <h2 class="fw-bold text-dark mb-1">
                Halo, <span class="text-capitalize" style="color: var(--primary);"><?= htmlspecialchars($nama); ?></span>! 👋
              </h2>
              <p class="text-muted mb-0">Selamat datang di perpustakaan digital SkanicPerpus. Cari buku favoritmu dan tingkatkan wawasan setiap hari!</p>
            </div>
            <div class="col-lg-5 text-lg-end d-flex flex-wrap gap-2 justify-content-lg-end">
              <a href="profil/profilMember.php" class="btn btn-modern-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-id-card"></i> Profil & Kartu Anggota
              </a>
              <a href="buku/daftarBuku.php" class="btn btn-modern-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i> Jelajahi Buku
              </a>
            </div>
          </div>
        </div>

        <!-- Student Summary Stats -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="stat-card">
              <div class="stat-icon warning">
                <i class="fa-solid fa-book-open-reader"></i>
              </div>
              <div class="stat-info">
                <p>Buku Sedang Dipinjam</p>
                <h3><?= $myPinjam; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="stat-card">
              <div class="stat-icon success">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <div class="stat-info">
                <p>Riwayat Pengembalian</p>
                <h3><?= $myKembali; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="stat-card">
              <div class="stat-icon danger">
                <i class="fa-solid fa-receipt"></i>
              </div>
              <div class="stat-info">
                <p>Tagihan Denda</p>
                <h3>Rp <?= number_format($myDenda, 0, ',', '.'); ?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Service Menu -->
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-shapes me-2 text-primary"></i> Layanan Perpustakaan</h5>
        <div class="row g-4 mb-4">
          
          <!-- Katalog Buku -->
          <div class="col-md-6 col-lg-3">
            <a href="buku/daftarBuku.php" class="menu-card">
              <div class="menu-icon primary">
                <i class="fa-solid fa-book-open"></i>
              </div>
              <h4>Katalog Buku</h4>
              <p>Jelajahi dan pinjam ribuan buku pelajaran, novel, informatika, dan sains.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Peminjaman -->
          <div class="col-md-6 col-lg-3">
            <a href="formPeminjaman/TransaksiPeminjaman.php" class="menu-card">
              <div class="menu-icon warning">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
              </div>
              <h4>Peminjaman Saya</h4>
              <p>Lihat buku yang sedang dipinjam, cek batas tenggat waktu, dan kembalikan.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Pengembalian -->
          <div class="col-md-6 col-lg-3">
            <a href="formPeminjaman/TransaksiPengembalian.php" class="menu-card">
              <div class="menu-icon secondary">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <h4>Riwayat Pengembalian</h4>
              <p>Catatan lengkap riwayat buku yang telah selesai Anda baca dan kembalikan.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Denda -->
          <div class="col-md-6 col-lg-3">
            <a href="formPeminjaman/TransaksiDenda.php" class="menu-card">
              <div class="menu-icon danger">
                <i class="fa-solid fa-receipt"></i>
              </div>
              <h4>Denda & Bayar</h4>
              <p>Cek rincian sanksi keterlambatan dan selesaikan pembayaran denda.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

        </div>

      </div>
    </main>

    <!-- Modern Footer -->
    <footer class="footer-modern py-3">
      <div class="container-fluid px-lg-4 d-flex flex-wrap justify-content-between align-items-center small">
        <p class="mb-0">Created by <span class="text-white fw-bold">Ihsan Maulana Ardianto</span> © 2025</p>
        <p class="mb-0">SkanicPerpus Member • v2.0</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../style/js/script.js"></script>
  </body>
</html>