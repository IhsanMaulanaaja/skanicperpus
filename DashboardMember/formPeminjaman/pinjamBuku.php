<?php 
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

// Tangkap id buku dari URL (GET)
$idBuku = $_GET["id"] ?? "";
$query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");

if(empty($query)) {
  header("Location: ../buku/daftarBuku.php");
  exit;
}

$bukuItem = $query[0];

// Menampilkan data siswa yg sedang login
$nisnSiswa = $_SESSION["member"]["nisn"];
$dataSiswa = queryReadData("SELECT * FROM member WHERE nisn = '$nisnSiswa'")[0] ?? $_SESSION["member"];
$admin = queryReadData("SELECT * FROM admin");

$successMsg = "";
$errorMsg = "";

// Peminjaman 
if(isset($_POST["pinjam"])) {
  $res = pinjamBuku($_POST);
  if($res > 0) {
    echo "<script>
    alert('Buku berhasil dipinjam! Silakan periksa daftar pinjaman Anda.');
    document.location.href = 'TransaksiPeminjaman.php';
    </script>";
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Peminjaman Buku - SkanicPerpus</title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="../buku/detailBuku.php?id=<?= urlencode($idBuku); ?>">
            <i class="fa-solid fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container" style="max-width: 850px;">
        
        <div class="form-card">
          <div class="text-center mb-4">
            <span class="badge-status warning mb-2"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman Buku</span>
            <h3 class="fw-bold text-dark">Formulir Peminjaman Buku</h3>
            <p class="text-muted small">Pastikan data diri dan buku yang ingin dipinjam sudah sesuai</p>
          </div>

          <!-- Book & Student Summary Row -->
          <div class="row g-3 mb-4">
            <!-- Book Summary -->
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3 border h-100 d-flex gap-3 align-items-center">
                <img src="../../imgDB/<?= htmlspecialchars($bukuItem["cover"]); ?>" alt="Cover" class="rounded-2 shadow-sm" style="width: 70px; height: 90px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
                <div>
                  <span class="badge-status info py-0 px-2 mb-1" style="font-size: 0.7rem;"><?= htmlspecialchars($bukuItem["kategori"]); ?></span>
                  <h6 class="fw-bold text-dark mb-1" style="line-height: 1.3;"><?= htmlspecialchars($bukuItem["judul"]); ?></h6>
                  <small class="text-muted d-block">Penulis: <?= htmlspecialchars($bukuItem["pengarang"]); ?></small>
                  <small class="text-secondary fw-semibold">ID: <?= htmlspecialchars($bukuItem["id_buku"]); ?></small>
                </div>
              </div>
            </div>

            <!-- Student Summary -->
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-3 border h-100">
                <span class="badge-status primary py-0 px-2 mb-1" style="font-size: 0.7rem;">Peminjam</span>
                <h6 class="fw-bold text-dark text-capitalize mb-1"><?= htmlspecialchars($dataSiswa["nama"]); ?></h6>
                <small class="text-muted d-block">NISN: <?= htmlspecialchars($dataSiswa["nisn"]); ?> • Kode: <?= htmlspecialchars($dataSiswa["kode_member"]); ?></small>
                <small class="text-muted d-block"><?= htmlspecialchars($dataSiswa["kelas"]); ?> - <?= htmlspecialchars($dataSiswa["jurusan"]); ?></small>
              </div>
            </div>
          </div>

          <form action="" method="post" class="needs-validation" novalidate>
            <!-- Hidden required inputs -->
            <input type="hidden" name="id_buku" value="<?= htmlspecialchars($bukuItem["id_buku"]); ?>">
            <input type="hidden" name="nisn" value="<?= htmlspecialchars($dataSiswa["nisn"]); ?>">

            <div class="row g-3">
              <!-- Petugas Admin Selector -->
              <div class="col-12">
                <label for="id_admin" class="form-label">Pilih Petugas Admin Perpustakaan</label>
                <select name="id" id="id_admin" class="form-select" required>
                  <option value="" selected disabled>-- Pilih Petugas Admin yang Bertugas --</option>
                  <?php foreach ($admin as $item) : ?>
                    <option value="<?= htmlspecialchars($item["id"]); ?>">
                      Admin: <?= htmlspecialchars($item["nama_admin"]); ?> (Kode: <?= htmlspecialchars($item["kode_admin"]); ?>)
                    </option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Pilih petugas admin perpustakaan!</div>
              </div>

              <!-- Tanggal Pinjam & Tanggal Kembali -->
              <div class="col-md-6">
                <label for="tgl_peminjaman" class="form-label">Tanggal Pinjam</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                  <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control" value="<?= date('Y-m-d'); ?>" onchange="setReturnDate()" required>
                  <div class="invalid-feedback">Tentukan tanggal peminjaman!</div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="tgl_pengembalian" class="form-label">Tenggat Pengembalian (Otomatis 7 Hari)</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-check"></i></span>
                  <input type="date" name="tgl_pengembalian" id="tgl_pengembalian" class="form-control bg-light" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" readonly>
                </div>
              </div>
            </div>

            <!-- Notes Alert -->
            <div class="alert alert-warning d-flex align-items-center gap-2 p-3 rounded-3 mt-4 mb-3" role="alert">
              <i class="fa-solid fa-triangle-exclamation fs-5 flex-shrink-0"></i>
              <div class="small">
                <strong>Ketentuan Peminjaman:</strong> Maksimal peminjaman adalah 7 hari. Keterlambatan pengembalian buku akan dikenakan denda sesuai peraturan perpustakaan.
              </div>
            </div>

            <div class="d-flex gap-2 pt-2">
              <button type="submit" class="btn btn-modern-primary flex-grow-1 py-2" name="pinjam">
                <i class="fa-solid fa-check me-1"></i> Konfirmasi & Pinjam Buku
              </button>
              <a class="btn btn-modern-secondary py-2" href="../buku/detailBuku.php?id=<?= urlencode($idBuku); ?>">Batal</a>
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
      // Run once on load
      setReturnDate();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
