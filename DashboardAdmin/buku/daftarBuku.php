<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$buku = queryReadData("SELECT * FROM buku ORDER BY id_buku DESC");

// Mengaktifkan search engine
$keyword = "";
if(isset($_POST["search"])) {
  $keyword = $_POST["keyword"];
  $buku = search($keyword);
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Data Buku - Admin SkanicPerpus</title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="../dashboardAdmin.php">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
          </a>
          <a class="btn btn-modern-primary d-inline-flex align-items-center gap-2" href="tambahBuku.php">
            <i class="fa-solid fa-plus"></i> Tambah Buku
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container-fluid px-lg-4">
        
        <!-- Header & Search -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
          <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-book me-2 text-primary"></i> Kelola Koleksi Buku</h3>
            <p class="text-muted small mb-0">Total <?= count($buku); ?> judul buku terdaftar di katalog perpustakaan</p>
          </div>

          <form action="" method="post" class="search-bar-modern">
            <input type="text" name="keyword" placeholder="Cari judul, kategori, pengarang..." value="<?= htmlspecialchars($keyword); ?>" autocomplete="off">
            <button type="submit" name="search" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>

        <!-- Book Cards Grid -->
        <?php if(empty($buku)) : ?>
          <div class="empty-state bg-white rounded-4 border p-5">
            <i class="fa-solid fa-box-open"></i>
            <h4>Tidak Ada Data Buku</h4>
            <p>Buku yang Anda cari tidak ditemukan. Coba gunakan kata kunci lain atau tambahkan buku baru.</p>
            <a href="daftarBuku.php" class="btn btn-modern-secondary mt-3">Reset Pencarian</a>
          </div>
        <?php else : ?>
          <div class="book-grid">
            <?php foreach ($buku as $item) : ?>
              <div class="book-card">
                <div class="book-cover-wrap">
                  <img src="../../imgDB/<?= htmlspecialchars($item["cover"]); ?>" alt="<?= htmlspecialchars($item["judul"]); ?>" onerror="this.src='../../assets/logoPerpus.jpg'">
                  <span class="book-badge-category"><?= htmlspecialchars($item["kategori"]); ?></span>
                </div>
                <div class="book-content">
                  <span class="badge-status info mb-2" style="font-size: 0.7rem; align-self: flex-start;">
                    ID: <?= htmlspecialchars($item["id_buku"]); ?>
                  </span>
                  <h6 class="book-title" title="<?= htmlspecialchars($item["judul"]); ?>">
                    <?= htmlspecialchars($item["judul"]); ?>
                  </h6>
                  <p class="book-author">
                    <i class="fa-solid fa-pen-nib me-1"></i> <?= htmlspecialchars($item["pengarang"]); ?>
                  </p>
                  
                  <div class="book-actions">
                    <a class="btn btn-modern-secondary btn-sm flex-grow-1" href="updateBuku.php?idReview=<?= urlencode($item["id_buku"]); ?>">
                      <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                    </a>
                    <a class="btn btn-modern-danger btn-sm" href="deleteBuku.php?id=<?= urlencode($item["id_buku"]); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku \'<?= addslashes($item["judul"]); ?>\'?');" title="Hapus">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </main>

    <!-- Modern Footer -->
    <footer class="footer-modern py-3">
      <div class="container-fluid px-lg-4 d-flex flex-wrap justify-content-between align-items-center small">
        <p class="mb-0">Created by <span class="text-white fw-bold">Ihsan Maulana Ardianto</span> © 2025</p>
        <p class="mb-0">SkanicPerpus Admin • v2.0</p>
      </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>