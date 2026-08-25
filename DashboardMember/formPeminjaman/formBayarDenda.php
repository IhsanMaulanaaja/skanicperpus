<?php 
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

if(isset($_POST["bayar"])) {
  if(bayarDenda($_POST) > 0) {
    echo "<script>
    alert('Pembayaran denda berhasil diproses!');
    document.location.href = 'TransaksiDenda.php';
    </script>";
    exit;
  } else {
    echo "<script>
    alert('Pembayaran denda gagal diproses!');
    </script>";
  }
}

$dendaSiswa = $_GET["id"] ?? "";
$query = queryReadData("SELECT pengembalian.id_pengembalian, buku.judul, buku.cover, member.nama, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.nisn = member.nisn
WHERE pengembalian.id_pengembalian = '$dendaSiswa'");

if(empty($query)) {
  header("Location: TransaksiDenda.php");
  exit;
}

$item = $query[0];
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bayar Denda - SkanicPerpus</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../style/main.css">
    <link rel="icon" href="../../assets/logoPerpus.jpg" type="image/jpeg">
  </head>
  <body>
    
    <!-- Modern Member Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
      <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="../dashboardMember.php">
          <img src="../../assets/SkanicLogin.png" alt="SkanicPerpus" height="46" onerror="this.src='../../assets/logo_skanic.png'">
        </a>

        <div class="d-flex align-items-center gap-2 ms-auto">
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="TransaksiDenda.php">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Denda
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container" style="max-width: 650px;">
        
        <div class="form-card">
          <div class="text-center mb-4">
            <span class="badge-status danger mb-2"><i class="fa-solid fa-credit-card"></i> Pembayaran Denda</span>
            <h3 class="fw-bold text-dark">Form Pembayaran Denda</h3>
            <p class="text-muted small">Masukkan nominal pembayaran denda keterlambatan buku</p>
          </div>

          <!-- Fine Summary Card -->
          <div class="p-3 bg-light rounded-3 border mb-4 d-flex gap-3 align-items-center">
            <img src="../../imgDB/<?= htmlspecialchars($item["cover"]); ?>" alt="Cover" class="rounded-2 shadow-sm" style="width: 60px; height: 75px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
            <div>
              <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($item["judul"]); ?></h6>
              <small class="text-muted d-block">Peminjam: <strong class="text-dark text-capitalize"><?= htmlspecialchars($item["nama"]); ?></strong></small>
              <small class="text-muted d-block">Tgl Kembali: <?= htmlspecialchars($item["buku_kembali"]); ?> • <?= htmlspecialchars($item["keterlambatan"]); ?></small>
            </div>
          </div>

          <form action="" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id_pengembalian" value="<?= htmlspecialchars($item["id_pengembalian"]); ?>">
            <input type="hidden" name="denda" value="<?= htmlspecialchars($item["denda"]); ?>">

            <div class="mb-3">
              <label class="form-label">Total Tagihan Denda Saat Ini</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-receipt"></i></span>
                <input type="text" class="form-control bg-light fw-bold text-danger fs-5" value="Rp <?= number_format($item["denda"], 0, ',', '.'); ?>" readonly>
              </div>
            </div>

            <div class="mb-4">
              <label for="bayarDenda" class="form-label">Jumlah Uang yang Dibayarkan (Rp)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
                <input type="number" class="form-control fs-5 fw-bold" name="bayarDenda" id="bayarDenda" placeholder="Contoh: 10000" min="1" max="<?= htmlspecialchars($item["denda"]); ?>" value="<?= htmlspecialchars($item["denda"]); ?>" required>
                <div class="invalid-feedback">Masukkan nominal pembayaran yang valid!</div>
              </div>
              <small class="text-muted">Masukkan nominal (bisa bayar lunas langsung sebesar Rp <?= number_format($item["denda"], 0, ',', '.'); ?>).</small>
            </div>

            <div class="d-flex gap-2 pt-2">
              <button type="submit" class="btn btn-modern-primary flex-grow-1 py-2" name="bayar">
                <i class="fa-solid fa-check me-1"></i> Konfirmasi Pembayaran
              </button>
              <a class="btn btn-modern-secondary py-2" href="TransaksiDenda.php">Batal</a>
            </div>
          </form>
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

    <script>
      (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
          form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
      })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>