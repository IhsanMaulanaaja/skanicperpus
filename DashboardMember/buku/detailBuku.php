<?php 
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

$idBuku = $_GET["id"] ?? "";
$query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");

if(empty($query)) {
  header("Location: daftarBuku.php");
  exit;
}

$item = $query[0];
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Buku - <?= htmlspecialchars($item["judul"]); ?></title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="daftarBuku.php">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container" style="max-width: 900px;">
        
        <div class="form-card p-4 p-md-5">
          <div class="row g-4 align-items-start">
            
            <!-- Book Cover Column -->
            <div class="col-md-5 text-center">
              <div class="position-relative d-inline-block w-100">
                <img src="../../imgDB/<?= htmlspecialchars($item["cover"]); ?>" class="img-fluid rounded-4 shadow-lg w-100" alt="<?= htmlspecialchars($item["judul"]); ?>" style="max-height: 380px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
                <div class="mt-3">
                  <span class="badge-status primary fs-6 py-2 px-3">
                    <i class="fa-solid fa-bookmark me-1"></i> <?= ucfirst(htmlspecialchars($item["kategori"])); ?>
                  </span>
                </div>
              </div>
            </div>

            <!-- Book Info Column -->
            <div class="col-md-7">
              <span class="badge-status info mb-2" style="font-size: 0.75rem;">ID BUKU: <?= htmlspecialchars($item["id_buku"]); ?></span>
              <h2 class="fw-extrabold text-dark mb-2" style="font-weight: 800; line-height: 1.25;">
                <?= htmlspecialchars($item["judul"]); ?>
              </h2>
              <p class="text-muted mb-4">
                <i class="fa-solid fa-pen-nib text-primary me-1"></i> Ditulis oleh <strong><?= htmlspecialchars($item["pengarang"]); ?></strong>
              </p>

              <!-- Meta specs table -->
              <div class="bg-light rounded-3 p-3 border mb-4">
                <div class="row g-2 small">
                  <div class="col-6">
                    <span class="text-muted d-block">Penerbit:</span>
                    <strong class="text-dark"><?= htmlspecialchars($item["penerbit"]); ?></strong>
                  </div>
                  <div class="col-6">
                    <span class="text-muted d-block">Tahun Terbit:</span>
                    <strong class="text-dark"><?= htmlspecialchars($item["tahun_terbit"]); ?></strong>
                  </div>
                  <div class="col-6 mt-2">
                    <span class="text-muted d-block">Jumlah Halaman:</span>
                    <strong class="text-dark"><?= htmlspecialchars($item["jumlah_halaman"]); ?> Halaman</strong>
                  </div>
                  <div class="col-6 mt-2">
                    <span class="text-muted d-block">Status Koleksi:</span>
                    <span class="badge-status success" style="font-size: 0.7rem;">Tersedia</span>
                  </div>
                </div>
              </div>

              <!-- Synopsis -->
              <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-align-left text-primary me-1"></i> Sinopsis / Deskripsi Buku</h6>
              <p class="text-muted small mb-4" style="line-height: 1.7; text-align: justify;">
                <?= !empty($item["buku_deskripsi"]) ? nl2br(htmlspecialchars($item["buku_deskripsi"])) : "Belum ada sinopsis untuk buku ini."; ?>
              </p>

              <!-- CTA Actions -->
              <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                <a href="../formPeminjaman/pinjamBuku.php?id=<?= urlencode($item["id_buku"]); ?>" class="btn btn-modern-primary flex-grow-1 py-2 d-inline-flex align-items-center justify-content-center gap-2">
                  <i class="fa-solid fa-hand-holding-hand"></i> Pinjam Buku Ini
                </a>
                <a href="daftarBuku.php" class="btn btn-modern-secondary py-2">
                  Batal
                </a>
              </div>
            </div>

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
  </body>
</html>