<?php 
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$kategori = queryReadData("SELECT * FROM kategori_buku");

$success = false;
$error = false;

if(isset($_POST["tambah"])) {
  if(tambahBuku($_POST) > 0) {
    $success = true;
  } else {
    $error = true;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Buku Baru - Admin SkanicPerpus</title>
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
    
    <!-- Modern Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
      <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="../dashboardAdmin.php">
          <img src="../../assets/SkanicLogin.png" alt="SkanicPerpus" height="46" onerror="this.src='../../assets/logo_skanic.png'">
        </a>

        <div class="d-flex align-items-center gap-2 ms-auto">
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="daftarBuku.php">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container" style="max-width: 800px;">
        
        <div class="form-card">
          <div class="text-center mb-4">
            <span class="badge-status primary mb-2"><i class="fa-solid fa-plus-circle"></i> Tambah Koleksi</span>
            <h3 class="fw-bold text-dark">Form Tambah Buku Baru</h3>
            <p class="text-muted small">Lengkapi data buku dan unggah cover untuk menambahkan ke sistem katalog</p>
          </div>

          <?php if($success) : ?>
            <div class="alert alert-success d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" role="alert">
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-4"></i>
                <div>
                  <strong>Berhasil!</strong> Buku baru telah berhasil ditambahkan ke katalog.
                </div>
              </div>
              <a href="daftarBuku.php" class="btn btn-success btn-sm">Lihat Katalog</a>
            </div>
          <?php elseif($error) : ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-4" role="alert">
              <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
              <div>Gagal menambahkan buku! Pastikan cover diunggah dan format sesuai.</div>
            </div>
          <?php endif; ?>

          <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="row g-3">
              
              <!-- Cover & ID Buku -->
              <div class="col-md-6">
                <label for="cover" class="form-label">Cover Buku (JPG/PNG)</label>
                <div class="input-group">
                  <input class="form-control" type="file" name="cover" id="cover" accept="image/*" required>
                </div>
                <div class="invalid-feedback">Pilih gambar cover buku!</div>
              </div>

              <div class="col-md-6">
                <label for="id_buku" class="form-label">ID Buku / Kode Unik</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-barcode"></i></span>
                  <input type="text" class="form-control" name="id_buku" id="id_buku" placeholder="Contoh: inf01, bis02" required>
                  <div class="invalid-feedback">ID Buku wajib diisi!</div>
                </div>
              </div>

              <!-- Judul & Kategori -->
              <div class="col-md-8">
                <label for="judul" class="form-label">Judul Buku</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-book"></i></span>
                  <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukkan judul lengkap buku" required>
                  <div class="invalid-feedback">Judul buku wajib diisi!</div>
                </div>
              </div>

              <div class="col-md-4">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                  <option value="" selected disabled>Pilih Kategori</option>
                  <?php foreach ($kategori as $item) : ?>
                    <option value="<?= htmlspecialchars($item["kategori"]); ?>"><?= ucfirst(htmlspecialchars($item["kategori"])); ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Pilih kategori buku!</div>
              </div>

              <!-- Pengarang & Penerbit -->
              <div class="col-md-6">
                <label for="pengarang" class="form-label">Nama Pengarang / Penulis</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-pen-nib"></i></span>
                  <input type="text" class="form-control" name="pengarang" id="pengarang" placeholder="Nama penulis buku" required>
                  <div class="invalid-feedback">Pengarang wajib diisi!</div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="penerbit" class="form-label">Penerbit</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-building"></i></span>
                  <input type="text" class="form-control" name="penerbit" id="penerbit" placeholder="Nama penerbit" required>
                  <div class="invalid-feedback">Penerbit wajib diisi!</div>
                </div>
              </div>

              <!-- Tahun Terbit & Halaman -->
              <div class="col-md-6">
                <label for="tahun_terbit" class="form-label">Tanggal / Tahun Terbit</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                  <input type="date" class="form-control" name="tahun_terbit" id="tahun_terbit" required>
                  <div class="invalid-feedback">Tanggal terbit wajib diisi!</div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="jumlah_halaman" class="form-label">Jumlah Halaman</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-file-lines"></i></span>
                  <input type="number" class="form-control" name="jumlah_halaman" id="jumlah_halaman" placeholder="Contoh: 250" required min="1">
                  <div class="invalid-feedback">Jumlah halaman wajib diisi!</div>
                </div>
              </div>

              <!-- Deskripsi / Sinopsis -->
              <div class="col-12">
                <label for="buku_deskripsi" class="form-label">Sinopsis / Ringkasan Buku</label>
                <textarea class="form-control" name="buku_deskripsi" id="buku_deskripsi" rows="4" placeholder="Tulis sinopsis ringkas tentang buku ini..."></textarea>
              </div>

            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
              <button class="btn btn-modern-primary flex-grow-1 py-2" type="submit" name="tambah">
                <i class="fa-solid fa-check me-1"></i> Simpan Buku Baru
              </button>
              <a href="daftarBuku.php" class="btn btn-modern-secondary py-2">Batal</a>
            </div>
          </form>
        </div>

      </div>
    </main>

    <!-- Modern Footer -->
    <footer class="footer-modern py-3">
      <div class="container-fluid px-lg-4 d-flex flex-wrap justify-content-between align-items-center small">
        <p class="mb-0">Created by <span class="text-white fw-bold">Ihsan Maulana Ardianto</span> © 2025</p>
        <p class="mb-0">SkanicPerpus Admin • v2.0</p>
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