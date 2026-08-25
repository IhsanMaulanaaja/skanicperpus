<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$tgl_dari = $_GET["tgl_dari"] ?? "";
$tgl_sampai = $_GET["tgl_sampai"] ?? "";
$keyword = $_GET["keyword"] ?? "";

$queryStr = "SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, buku.cover, peminjaman.nisn, member.nama, member.kelas, member.jurusan, member.foto, peminjaman.id_admin, admin.nama_admin, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian 
FROM peminjaman 
INNER JOIN member ON peminjaman.nisn = member.nisn
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
LEFT JOIN admin ON peminjaman.id_admin = admin.id
WHERE 1=1";

if(!empty($tgl_dari)) {
  $safeDari = mysqli_real_escape_string($connection, $tgl_dari);
  $queryStr .= " AND peminjaman.tgl_peminjaman >= '$safeDari'";
}
if(!empty($tgl_sampai)) {
  $safeSampai = mysqli_real_escape_string($connection, $tgl_sampai);
  $queryStr .= " AND peminjaman.tgl_peminjaman <= '$safeSampai'";
}
if(!empty($keyword)) {
  $safeKw = mysqli_real_escape_string($connection, $keyword);
  $queryStr .= " AND (member.nama LIKE '%$safeKw%' OR buku.judul LIKE '%$safeKw%' OR peminjaman.nisn LIKE '%$safeKw%')";
}

$queryStr .= " ORDER BY peminjaman.tgl_peminjaman DESC";
$dataPeminjam = queryReadData($queryStr);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaksi Peminjaman - Admin SkanicPerpus</title>
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
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container-fluid px-lg-4">
        
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
          <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-arrow-right-arrow-left text-warning me-2"></i> Transaksi Peminjaman Aktif</h3>
            <p class="text-muted small mb-0">Total <?= count($dataPeminjam); ?> transaksi peminjaman buku ditemukan</p>
          </div>
        </div>

        <!-- Filter Form by Date & Search -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
          <form action="" method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-calendar-day text-primary me-1"></i> Dari Tanggal Pinjam</label>
              <input type="date" name="tgl_dari" class="form-control form-control-sm" value="<?= htmlspecialchars($tgl_dari); ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-calendar-check text-primary me-1"></i> Sampai Tanggal Pinjam</label>
              <input type="date" name="tgl_sampai" class="form-control form-control-sm" value="<?= htmlspecialchars($tgl_sampai); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-magnifying-glass text-primary me-1"></i> Cari Siswa / Judul</label>
              <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Nama siswa, NISN, atau judul..." value="<?= htmlspecialchars($keyword); ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
              <button type="submit" class="btn btn-modern-primary btn-sm flex-grow-1">
                <i class="fa-solid fa-filter me-1"></i> Cek Tanggal
              </button>
              <?php if(!empty($tgl_dari) || !empty($tgl_sampai) || !empty($keyword)) : ?>
                <a href="peminjamanBuku.php" class="btn btn-modern-secondary btn-sm" title="Reset Filter">
                  <i class="fa-solid fa-rotate-left"></i>
                </a>
              <?php endif; ?>
            </div>
          </form>
        </div>

        <!-- Modern Table -->
        <div class="table-card">
          <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
              <thead>
                <tr>
                  <th>ID Pinjam</th>
                  <th>Buku</th>
                  <th>Peminjam (Siswa)</th>
                  <th>Kelas / Jurusan</th>
                  <th>Admin Petugas</th>
                  <th>Tanggal Pinjam</th>
                  <th>Tenggat Pengembalian</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($dataPeminjam)) : ?>
                  <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="fa-solid fa-calendar-xmark fs-2 mb-2 d-block text-secondary"></i>
                      Tidak ada transaksi peminjaman pada rentang tanggal atau kriteria yang dicari.
                    </td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($dataPeminjam as $item) : 
                    $today = date('Y-m-d');
                    $isOverdue = ($today > $item["tgl_pengembalian"]);
                  ?>
                    <tr>
                      <td class="fw-bold text-primary">#<?= htmlspecialchars($item["id_peminjaman"]); ?></td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <img src="../../imgDB/<?= htmlspecialchars($item["cover"]); ?>" alt="Cover" class="rounded-2 shadow-sm" style="width: 38px; height: 48px; object-fit: cover;" onerror="this.src='../../assets/logoPerpus.jpg'">
                          <div>
                            <div class="fw-bold text-dark" style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($item["judul"]); ?></div>
                            <small class="text-muted">Kode: <?= htmlspecialchars($item["id_buku"]); ?></small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <img src="../../imgDB/avatar/<?= htmlspecialchars(!empty($item["foto"]) ? $item["foto"] : "default_member.png"); ?>" alt="Foto" class="rounded-circle shadow-sm" style="width: 34px; height: 34px; object-fit: cover;" onerror="this.src='../../assets/memberLogo.png'">
                          <div>
                            <div class="fw-bold text-dark text-capitalize"><?= htmlspecialchars($item["nama"]); ?></div>
                            <small class="text-muted">NISN: <?= htmlspecialchars($item["nisn"]); ?></small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="fw-semibold"><?= htmlspecialchars($item["kelas"]); ?></div>
                        <small class="text-muted"><?= htmlspecialchars($item["jurusan"]); ?></small>
                      </td>
                      <td>
                        <span class="badge-status secondary">
                          <i class="fa-solid fa-user-tie"></i> <?= htmlspecialchars($item["nama_admin"] ?? "Admin"); ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge-status primary">
                          <i class="fa-solid fa-calendar-day"></i> <?= date('d M Y', strtotime($item["tgl_peminjaman"])); ?>
                        </span>
                      </td>
                      <td class="fw-semibold <?= $isOverdue ? 'text-danger' : 'text-dark'; ?>">
                        <i class="fa-solid fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($item["tgl_pengembalian"])); ?>
                      </td>
                      <td>
                        <?php if($isOverdue) : ?>
                          <span class="badge-status danger"><i class="fa-solid fa-triangle-exclamation"></i> Lewat Tenggat</span>
                        <?php else : ?>
                          <span class="badge-status warning"><i class="fa-solid fa-clock"></i> Dipinjam</span>
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
        <p class="mb-0">SkanicPerpus Admin • v2.0</p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../style/js/script.js"></script>
  </body>
</html>