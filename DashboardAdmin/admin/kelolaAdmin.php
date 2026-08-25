<?php
session_start();

// Proteksi akses: HANYA ADMIN yang boleh mengakses halaman ini
if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$keyword = $_GET["keyword"] ?? "";

$queryStr = "SELECT * FROM admin WHERE 1=1";
if(!empty($keyword)) {
  $safeKw = mysqli_real_escape_string($connection, $keyword);
  $queryStr .= " AND (nama_admin LIKE '%$safeKw%' OR kode_admin LIKE '%$safeKw%' OR no_tlp LIKE '%$safeKw%')";
}
$queryStr .= " ORDER BY id ASC";

$dataAdmin = queryReadData($queryStr);
$currentAdminName = $_SESSION["admin"]["nama_admin"] ?? "";
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Akun Admin - SkanicPerpus</title>
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
          <a class="btn btn-modern-primary d-inline-flex align-items-center gap-2" href="tambahAdmin.php">
            <i class="fa-solid fa-user-plus"></i> Tambah Admin Baru
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container-fluid px-lg-4">
        
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
          <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-shield text-primary me-2"></i> Manajemen Akun Petugas Admin</h3>
            <p class="text-muted small mb-0">Total <?= count($dataAdmin); ?> petugas admin terdaftar dalam sistem</p>
          </div>

          <form action="" method="get" class="search-bar-modern">
            <input type="text" name="keyword" placeholder="Cari nama admin, kode, no hp..." value="<?= htmlspecialchars($keyword); ?>" autocomplete="off">
            <button type="submit" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </div>

        <!-- Table of Admins -->
        <div class="table-card">
          <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama Petugas Admin</th>
                  <th>Kode Admin</th>
                  <th>No. Telepon / WhatsApp</th>
                  <th>Status Akun</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($dataAdmin)) : ?>
                  <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                      <i class="fa-solid fa-user-slash fs-2 mb-2 d-block text-secondary"></i>
                      Data admin tidak ditemukan.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($dataAdmin as $item) : 
                    $isCurrent = (strtolower($item["nama_admin"]) === strtolower($currentAdminName));
                  ?>
                    <tr>
                      <td class="fw-bold text-muted">#<?= htmlspecialchars($item["id"]); ?></td>
                      <td>
                        <div class="d-flex align-items-center gap-3">
                          <img src="../../imgDB/avatar/<?= htmlspecialchars(!empty($item["foto"]) ? $item["foto"] : "default_admin.png"); ?>" alt="Foto" class="rounded-circle shadow-sm" style="width: 38px; height: 38px; object-fit: cover;" onerror="this.src='../../assets/adminLogo.png'">
                          <div>
                            <div class="fw-bold text-dark text-capitalize">
                              <?= htmlspecialchars($item["nama_admin"]); ?>
                              <?php if($isCurrent) : ?>
                                <span class="badge-status success ms-1" style="font-size: 0.65rem;">(Anda)</span>
                              <?php endif; ?>
                            </div>
                            <small class="text-muted">Role: Administrator Perpustakaan</small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge-status primary fw-bold">
                          <i class="fa-solid fa-id-badge"></i> <?= htmlspecialchars($item["kode_admin"]); ?>
                        </span>
                      </td>
                      <td>
                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $item["no_tlp"])); ?>" target="_blank" class="text-decoration-none text-dark fw-semibold">
                          <i class="fa-brands fa-whatsapp text-success me-1"></i> <?= htmlspecialchars($item["no_tlp"]); ?>
                        </a>
                      </td>
                      <td>
                        <span class="badge-status success">
                          <i class="fa-solid fa-circle-check"></i> Aktif
                        </span>
                      </td>
                      <td class="text-center">
                        <?php if($isCurrent || count($dataAdmin) <= 1) : ?>
                          <button class="btn btn-secondary btn-sm" disabled title="Tidak dapat menghapus akun sendiri atau admin terakhir">
                            <i class="fa-solid fa-lock"></i>
                          </button>
                        <?php else : ?>
                          <a href="deleteAdmin.php?id=<?= urlencode($item["id"]); ?>" class="btn btn-modern-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun admin \'<?= addslashes($item["nama_admin"]); ?>\'?');" title="Hapus Admin">
                            <i class="fa-solid fa-trash"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../style/js/script.js"></script>
  </body>
</html>
