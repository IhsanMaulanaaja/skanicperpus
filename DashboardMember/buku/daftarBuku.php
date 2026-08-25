<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

$selectedCategory = "semua";
$keyword = "";

// Query awal semua buku
$buku = queryReadData("SELECT * FROM buku ORDER BY id_buku DESC");

// Search buku
if(isset($_POST["search"])) {
  $keyword = $_POST["keyword"];
  $buku = search($keyword);
  $selectedCategory = "search";
}

// Filter kategori buku
if(isset($_POST["kategori"])) {
  $kategoriChoice = $_POST["kategori"];
  $selectedCategory = $kategoriChoice;
  if($kategoriChoice === 'semua') {
    $buku = queryReadData("SELECT * FROM buku ORDER BY id_buku DESC");
  } else {
    $safeKategori = mysqli_real_escape_string($connection, $kategoriChoice);
    $buku = queryReadData("SELECT * FROM buku WHERE kategori = '$safeKategori' ORDER BY id_buku DESC");
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Buku - SkanicPerpus</title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="../dashboardMember.php">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
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
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-book-open text-primary me-2"></i> Jelajahi Koleksi Buku</h3>
            <p class="text-muted small mb-0">Temukan buku yang Anda butuhkan untuk belajar atau membaca santai</p>
          </div>

          <form action="" method="post" class="search-bar-modern">
            <input type="text" name="keyword" placeholder="Cari judul, kategori, atau pengarang..." value="<?= htmlspecialchars($keyword); ?>" autocomplete="off">
            <button type="submit" name="search" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>

        <!-- Filter Kategori Tabs -->
        <form action="" method="post" class="category-filter-wrap mb-4">
          <button type="submit" name="kategori" value="semua" class="category-pill <?= ($selectedCategory === 'semua') ? 'active' : ''; ?>">
            <i class="fa-solid fa-layer-group me-1"></i> Semua Kategori
          </button>
          <button type="submit" name="kategori" value="informatika" class="category-pill <?= ($selectedCategory === 'informatika') ? 'active' : ''; ?>">
            <i class="fa-solid fa-laptop-code me-1"></i> Informatika
          </button>
          <button type="submit" name="kategori" value="bisnis" class="category-pill <?= ($selectedCategory === 'bisnis') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line me-1"></i> Bisnis
          </button>
          <button type="submit" name="kategori" value="filsafat" class="category-pill <?= ($selectedCategory === 'filsafat') ? 'active' : ''; ?>">
            <i class="fa-solid fa-brain me-1"></i> Filsafat
          </button>
          <button type="submit" name="kategori" value="novel" class="category-pill <?= ($selectedCategory === 'novel') ? 'active' : ''; ?>">
            <i class="fa-solid fa-book-bookmark me-1"></i> Novel
          </button>
          <button type="submit" name="kategori" value="sains" class="category-pill <?= ($selectedCategory === 'sains') ? 'active' : ''; ?>">
            <i class="fa-solid fa-flask me-1"></i> Sains
          </button>
        </form>

        <!-- Book Cards Grid -->
        <?php if(empty($buku)) : ?>
          <div class="empty-state bg-white rounded-4 border p-5">
            <i class="fa-solid fa-book-bookmark"></i>
            <h4>Buku Tidak Ditemukan</h4>
            <p>Maaf, tidak ada buku yang sesuai dengan pencarian atau filter kategori yang Anda pilih.</p>
            <a href="daftarBuku.php" class="btn btn-modern-secondary mt-3">Lihat Semua Buku</a>
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
                  <h6 class="book-title" title="<?= htmlspecialchars($item["judul"]); ?>">
                    <?= htmlspecialchars($item["judul"]); ?>
                  </h6>
                  <p class="book-author">
                    <i class="fa-solid fa-pen-nib me-1"></i> <?= htmlspecialchars($item["pengarang"]); ?>
                  </p>
                  
                  <div class="book-actions">
                    <a class="btn btn-modern-primary btn-sm w-100" href="detailBuku.php?id=<?= urlencode($item["id_buku"]); ?>">
                      <i class="fa-solid fa-circle-info me-1"></i> Lihat Detail
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
        <p class="mb-0">SkanicPerpus Member • v2.0</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>