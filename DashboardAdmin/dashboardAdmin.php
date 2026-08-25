<?php
session_start();

// Proteksi akses: HANYA ADMIN yang boleh masuk ke dashboard admin
if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../sign/admin/sign_in.php");
  exit;
}

require "../config/config.php";

// Ambil data statistik ringkas untuk Dashboard
$countBuku = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM buku"))[0] ?? 0;
$countMember = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM member"))[0] ?? 0;
$countPinjam = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM peminjaman"))[0] ?? 0;
$countKembali = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM pengembalian"))[0] ?? 0;
$sumDenda = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(denda) FROM pengembalian WHERE denda > 0"))[0] ?? 0;
$countAdmin = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM admin"))[0] ?? 0;

$namaAdmin = $_SESSION["admin"]["nama_admin"] ?? "Admin";
$fotoAdmin = $_SESSION["admin"]["foto"] ?? "default_admin.png";
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - SkanicPerpus</title>
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
    
    <!-- Modern Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
      <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="dashboardAdmin.php">
          <img src="../assets/SkanicLogin.png" alt="SkanicPerpus" height="46" onerror="this.src='../assets/logo_skanic.png'">
        </a>

        <div class="d-flex align-items-center gap-3 ms-auto">
          <div class="dropdown">
            <button class="user-profile-btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../imgDB/avatar/<?= htmlspecialchars($fotoAdmin); ?>" alt="Admin Avatar" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" onerror="this.src='../assets/adminLogo.png'">
              <div class="d-none d-sm-block text-start">
                <div class="fw-bold text-dark text-capitalize" style="font-size: 0.85rem;"><?= htmlspecialchars($namaAdmin); ?></div>
                <div class="text-muted" style="font-size: 0.7rem;">Administrator</div>
              </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern mt-2">
              <li class="px-3 py-2 text-center border-bottom pb-2 mb-2">
                <span class="badge-status success"><i class="fa-solid fa-circle-check"></i> Admin Terverifikasi</span>
              </li>
              <li>
                <a class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2" href="profil/profilAdmin.php">
                  <i class="fa-solid fa-user-gear text-primary"></i> Profil Saya & Ganti Foto
                </a>
              </li>
              <li>
                <a class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2" href="admin/kelolaAdmin.php">
                  <i class="fa-solid fa-user-shield text-info"></i> Kelola Petugas Admin
                </a>
              </li>
              <li>
                <a class="dropdown-item rounded-3 py-2 fw-semibold d-flex align-items-center gap-2" href="admin/tambahAdmin.php">
                  <i class="fa-solid fa-user-plus text-success"></i> Tambah Admin Baru
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
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
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
              <div class="badge-status primary mb-2">
                <i class="fa-solid fa-calendar-day"></i> <?= date('l, d F Y'); ?>
              </div>
              <h2 class="fw-bold text-dark mb-1">
                Selamat Datang, <span class="text-capitalize" style="color: var(--primary);"><?= htmlspecialchars($namaAdmin); ?></span>! 👋
              </h2>
              <p class="text-muted mb-0">Panel kontrol utama pengelolaan dan layanan perpustakaan digital SkanicPerpus.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <a href="admin/tambahAdmin.php" class="btn btn-modern-secondary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Tambah Admin
              </a>
              <a href="buku/tambahBuku.php" class="btn btn-modern-primary d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Buku Baru
              </a>
            </div>
          </div>
        </div>

        <!-- Live Statistics Row -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl">
            <div class="stat-card">
              <div class="stat-icon primary">
                <i class="fa-solid fa-book"></i>
              </div>
              <div class="stat-info">
                <p>Total Koleksi</p>
                <h3><?= $countBuku; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl">
            <div class="stat-card">
              <div class="stat-icon success">
                <i class="fa-solid fa-users"></i>
              </div>
              <div class="stat-info">
                <p>Member Siswa</p>
                <h3><?= $countMember; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl">
            <div class="stat-card">
              <div class="stat-icon warning">
                <i class="fa-solid fa-hand-holding-hand"></i>
              </div>
              <div class="stat-info">
                <p>Buku Dipinjam</p>
                <h3><?= $countPinjam; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl">
            <div class="stat-card">
              <div class="stat-icon secondary">
                <i class="fa-solid fa-rotate-left"></i>
              </div>
              <div class="stat-info">
                <p>Pengembalian</p>
                <h3><?= $countKembali; ?></h3>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl">
            <div class="stat-card">
              <div class="stat-icon danger">
                <i class="fa-solid fa-money-bill-wave"></i>
              </div>
              <div class="stat-info">
                <p>Total Denda</p>
                <h3>Rp <?= number_format($sumDenda, 0, ',', '.'); ?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Grid Menu Cards -->
        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-shapes me-2 text-primary"></i> Menu Navigasi Admin</h5>
        <div class="row g-4 mb-4">
          
          <!-- Kelola Buku -->
          <div class="col-md-6 col-lg-4">
            <a href="buku/daftarBuku.php" class="menu-card">
              <div class="menu-icon primary">
                <i class="fa-solid fa-book-open"></i>
              </div>
              <h4>Kelola Data Buku</h4>
              <p>Lihat, cari, filter, tambah, perbarui, dan hapus data buku perpustakaan.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Data Member -->
          <div class="col-md-6 col-lg-4">
            <a href="member/member.php" class="menu-card">
              <div class="menu-icon success">
                <i class="fa-solid fa-users"></i>
              </div>
              <h4>Kelola Member Siswa</h4>
              <p>Daftar seluruh siswa terdaftar, info kelas, jurusan, serta manajemen akun.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Peminjaman Buku -->
          <div class="col-md-6 col-lg-4">
            <a href="peminjaman/peminjamanBuku.php" class="menu-card">
              <div class="menu-icon warning">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
              </div>
              <h4>Transaksi Peminjaman</h4>
              <p>Pantau seluruh transaksi peminjaman buku aktif dan tenggat waktu pengembalian.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Pengembalian Buku -->
          <div class="col-md-6 col-lg-4">
            <a href="pengembalian/pengembalianBuku.php" class="menu-card">
              <div class="menu-icon secondary">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <h4>Riwayat Pengembalian</h4>
              <p>Catatan riwayat buku yang telah dikembalikan beserta status keterlambatan.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Kelola Denda -->
          <div class="col-md-6 col-lg-4">
            <a href="denda/daftarDenda.php" class="menu-card">
              <div class="menu-icon danger">
                <i class="fa-solid fa-receipt"></i>
              </div>
              <h4>Kelola Denda Buku</h4>
              <p>Daftar keterlambatan pengembalian buku dan status pembayaran denda siswa.</p>
              <div class="menu-arrow"><i class="fa-solid fa-arrow-right"></i></div>
            </a>
          </div>

          <!-- Kelola Akun Admin (Baru) -->
          <div class="col-md-6 col-lg-4">
            <a href="admin/kelolaAdmin.php" class="menu-card">
              <div class="menu-icon info">
                <i class="fa-solid fa-user-shield"></i>
              </div>
              <h4>Kelola Petugas Admin</h4>
              <p>Tambah petugas admin baru, pantau <?= $countAdmin; ?> akun administrator aktif.</p>
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
        <p class="mb-0">SkanicPerpus Admin Panel • v2.0</p>
      </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../style/js/script.js"></script>
  </body>
</html>