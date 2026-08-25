<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

// Ambil data dari url
$review = $_GET["idReview"] ?? "";
$reviewQuery = queryReadData("SELECT * FROM buku WHERE id_buku = '$review'");

if(empty($reviewQuery)) {
  header("Location: daftarBuku.php");
  exit;
}

$reviewData = $reviewQuery[0];
$kategori = queryReadData("SELECT * FROM kategori_buku"); 

$error = false;

if(isset($_POST["update"])) {
  if(updateBuku($_POST) > 0) {
    echo "<script>
    alert('Data buku berhasil diperbarui!');
    document.location.href = 'daftarBuku.php';
    </script>";
    exit;
  } else {
    // Bisa jadi tidak ada perubahan data atau update gagal
    echo "<script>
    alert('Data buku tidak ada perubahan atau gagal diupdate!');
    document.location.href = 'daftarBuku.php';
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
    <title>Edit Data Buku - Admin SkanicPerpus</title>
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
            <span class="badge-status warning mb-2"><i class="fa-solid fa-pen-to-square"></i> Perbarui Data</span>
            <h3 class="fw-bold text-dark">Edit Informasi Buku</h3>
            <p class="text-muted small">Perbarui rincian buku <strong>"<?= htmlspecialchars($reviewData["judul"]); ?>"</strong></p>
          </div>

          <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="coverLama" value="<?= htmlspecialchars($reviewData["cover"]); ?>">
            
            <div class="row g-3">
              
              <!-- Current Cover Preview & New Upload -->
              <div class="col-12">
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                  <img src="../../imgDB/<?= htmlspecialchars($reviewData["cover"]); ?>" alt="Current Cover" class="rounded-2 shadow-sm" style="width: 64px; height: 80px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
                  <div class="flex-grow-1">
                    <label for="cover" class="form-label mb-1">Ganti Cover Buku (Opsional)</label>
                    <input class="form-control" type="file" name="cover" id="cover" accept="image/*">
                    <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah cover yang sudah ada.</small>
                  </div>
                </div>
              </div>

              <!-- ID Buku & Kategori -->
              <div class="col-md-6">
                <label for="id_buku" class="form-label">ID Buku</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-barcode"></i></span>
                  <input type="text" class="form-control bg-light" name="id_buku" id="id_buku" value="<?= htmlspecialchars($reviewData["id_buku"]); ?>" readonly>
                </div>
              </div>

              <div class="col-md-6">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                  <?php foreach ($kategori as $item) : ?>
                    <option value="<?= htmlspecialchars($item["kategori"]); ?>" <?= ($item["kategori"] == $reviewData["kategori"]) ? 'selected' : ''; ?>>
                      <?= ucfirst(htmlspecialchars($item["kategori"])); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Judul -->
              <div class="col-12">
                <label for="judul" class="form-label">Judul Buku</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-book"></i></span>
                  <input type="text" class="form-control" name="judul" id="judul" value="<?= htmlspecialchars($reviewData["judul"]); ?>" required>
                  <div class="invalid-feedback">Judul buku wajib diisi!</div>
                </div>
              </div>

              <!-- Pengarang & Penerbit -->
              <div class="col-md-6">
                <label for="pengarang" class="form-label">Pengarang</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-pen-nib"></i></span>
                  <input type="text" class="form-control" name="pengarang" id="pengarang" value="<?= htmlspecialchars($reviewData["pengarang"]); ?>" required>
                  <div class="invalid-feedback">Pengarang wajib diisi!</div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="penerbit" class="form-label">Penerbit</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-building"></i></span>
                  <input type="text" class="form-control" name="penerbit" id="penerbit" value="<?= htmlspecialchars($reviewData["penerbit"]); ?>" required>
                  <div class="invalid-feedback">Penerbit wajib diisi!</div>
                </div>
              </div>

              <!-- Tahun Terbit & Halaman -->
              <div class="col-md-6">
                <label for="tahun_terbit" class="form-label">Tanggal / Tahun Terbit</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                  <input type="date" class="form-control" name="tahun_terbit" id="tahun_terbit" value="<?= htmlspecialchars($reviewData["tahun_terbit"]); ?>" required>
                  <div class="invalid-feedback">Tanggal terbit wajib diisi!</div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="jumlah_halaman" class="form-label">Jumlah Halaman</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-file-lines"></i></span>
                  <input type="number" class="form-control" name="jumlah_halaman" id="jumlah_halaman" value="<?= htmlspecialchars($reviewData["jumlah_halaman"]); ?>" required min="1">
                  <div class="invalid-feedback">Jumlah halaman wajib diisi!</div>
                </div>
              </div>

              <!-- Deskripsi -->
              <div class="col-12">
                <label for="buku_deskripsi" class="form-label">Sinopsis / Ringkasan Buku</label>
                <textarea class="form-control" name="buku_deskripsi" id="buku_deskripsi" rows="4"><?= htmlspecialchars($reviewData["buku_deskripsi"]); ?></textarea>
              </div>

            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
              <button class="btn btn-modern-primary flex-grow-1 py-2" type="submit" name="update">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>