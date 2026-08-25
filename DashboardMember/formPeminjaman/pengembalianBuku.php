<?php 
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

$idPeminjaman = $_GET["id"] ?? "";
$query = queryReadData("SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, buku.cover, peminjaman.nisn, member.nama, peminjaman.id_admin, admin.nama_admin, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian
FROM peminjaman
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
INNER JOIN member ON peminjaman.nisn = member.nisn
LEFT JOIN admin ON peminjaman.id_admin = admin.id
WHERE peminjaman.id_peminjaman = '$idPeminjaman'");

if(empty($query)) {
  header("Location: TransaksiPeminjaman.php");
  exit;
}

$item = $query[0];

// Jika tombol submit kembalikan diklik
if(isset($_POST["kembalikan"])) {
  if(pengembalian($_POST) > 0) {
    echo "<script>
    alert('Terima kasih! Buku berhasil dikembalikan.');
    document.location.href = 'TransaksiPengembalian.php';
    </script>";
    exit;
  } else {
    echo "<script>
    alert('Buku gagal dikembalikan, silakan coba lagi.');
    </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengembalian Buku - SkanicPerpus</title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="TransaksiPeminjaman.php">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Pinjaman
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container" style="max-width: 800px;">
        
        <div class="form-card">
          <div class="text-center mb-4">
            <span class="badge-status success mb-2"><i class="fa-solid fa-rotate-left"></i> Pengembalian Buku</span>
            <h3 class="fw-bold text-dark">Form Pengembalian Buku</h3>
            <p class="text-muted small">Periksa tanggal pengembalian dan kalkulasi denda otomatis jika melewati batas tenggat</p>
          </div>

          <!-- Book & Loan Info -->
          <div class="p-3 bg-light rounded-3 border mb-4 d-flex gap-3 align-items-center">
            <img src="../../imgDB/<?= htmlspecialchars($item["cover"]); ?>" alt="Cover" class="rounded-2 shadow-sm" style="width: 70px; height: 90px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
            <div>
              <span class="badge-status primary py-0 px-2 mb-1" style="font-size: 0.7rem;">ID Pinjam: #<?= htmlspecialchars($item["id_peminjaman"]); ?></span>
              <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($item["judul"]); ?></h6>
              <small class="text-muted d-block">Peminjam: <strong class="text-dark text-capitalize"><?= htmlspecialchars($item["nama"]); ?></strong> (NISN: <?= htmlspecialchars($item["nisn"]); ?>)</small>
              <small class="text-muted d-block">Petugas Admin: <?= htmlspecialchars($item["nama_admin"] ?? "Admin"); ?></small>
            </div>
          </div>

          <form action="" method="post">
            <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($item["id_peminjaman"]); ?>">
            <input type="hidden" name="id_buku" value="<?= htmlspecialchars($item["id_buku"]); ?>">
            <input type="hidden" name="nisn" value="<?= htmlspecialchars($item["nisn"]); ?>">
            <input type="hidden" name="id_admin" value="<?= htmlspecialchars($item["id_admin"]); ?>">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Tanggal Peminjaman</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                  <input type="date" class="form-control bg-light" name="tgl_peminjaman" id="tgl_peminjaman" value="<?= htmlspecialchars($item["tgl_peminjaman"]); ?>" readonly>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Tenggat Waktu Pengembalian</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-xmark"></i></span>
                  <input type="date" class="form-control bg-light" name="tgl_pengembalian" id="tgl_pengembalian" value="<?= htmlspecialchars($item["tgl_pengembalian"]); ?>" readonly>
                </div>
              </div>

              <div class="col-md-4">
                <label for="buku_kembali" class="form-label">Tanggal Dikembalikan</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
                  <input type="date" class="form-control" name="buku_kembali" id="buku_kembali" value="<?= date('Y-m-d'); ?>" oninput="hitungDenda()" onchange="hitungDenda()" required>
                </div>
              </div>

              <div class="col-md-4">
                <label for="keterlambatan" class="form-label">Status Terlambat</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                  <input type="text" class="form-control bg-light fw-bold" name="keterlambatan" id="keterlambatan" readonly>
                </div>
              </div>

              <div class="col-md-4">
                <label for="denda" class="form-label">Besaran Denda (Rp)</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-rupiah-sign"></i></span>
                  <input type="number" class="form-control bg-light fw-bold text-danger" name="denda" id="denda" readonly>
                </div>
              </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
              <button type="submit" class="btn btn-modern-primary flex-grow-1 py-2" name="kembalikan">
                <i class="fa-solid fa-check me-1"></i> Konfirmasi Pengembalian Buku
              </button>
              <a class="btn btn-modern-secondary py-2" href="TransaksiPeminjaman.php">Batal</a>
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

    <!-- Helper JS -->
    <script src="../../style/js/script.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        hitungDenda();
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>