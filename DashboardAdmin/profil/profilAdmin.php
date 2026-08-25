<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$idAdmin = (int)$_SESSION["admin"]["id"];
$adminData = queryReadData("SELECT * FROM admin WHERE id = $idAdmin")[0] ?? null;

if(!$adminData) {
  header("Location: ../dashboardAdmin.php");
  exit;
}

$successMsg = "";
$errorMsg = "";

if(isset($_POST["simpanProfil"])) {
  if(updateProfilAdmin($_POST, $_FILES) > 0) {
    $successMsg = "Profil berhasil diperbarui!";
    // Refresh admin data
    $adminData = queryReadData("SELECT * FROM admin WHERE id = $idAdmin")[0];
  } else {
    $errorMsg = "Gagal memperbarui profil. Pastikan data dan format file foto valid.";
  }
}

$fotoAdmin = !empty($adminData["foto"]) ? $adminData["foto"] : "default_admin.png";
$avatarPath = "../../imgDB/avatar/" . $fotoAdmin;
if(!file_exists(__DIR__ . "/../../imgDB/avatar/" . $fotoAdmin) && !file_exists(__DIR__ . "/../../assets/" . $fotoAdmin)) {
  $avatarPath = "../../assets/adminLogo.png";
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - Admin SkanicPerpus</title>
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
      <div class="container-fluid px-lg-4 py-3">
        
        <!-- Header -->
        <div class="mb-4">
          <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-gear text-primary me-2"></i> Pengaturan Akun & Profil Admin</h3>
          <p class="text-muted small mb-0">Kelola informasi pribadi, ubah foto profil, dan perbarui kata sandi akun Anda.</p>
        </div>

        <?php if(!empty($successMsg)) : ?>
          <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center gap-2 mb-4 py-3 px-4">
            <i class="fa-solid fa-circle-check fs-4"></i>
            <div><?= htmlspecialchars($successMsg); ?></div>
          </div>
        <?php endif; ?>

        <?php if(!empty($errorMsg)) : ?>
          <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center gap-2 mb-4 py-3 px-4">
            <i class="fa-solid fa-circle-exclamation fs-4"></i>
            <div><?= htmlspecialchars($errorMsg); ?></div>
          </div>
        <?php endif; ?>

        <div class="row g-4">
          
          <!-- Left Column: Profile Card -->
          <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 bg-white">
              <div class="position-relative d-inline-block mx-auto mb-3">
                <img id="avatarPreview" src="<?= $avatarPath; ?>" alt="Foto Profil" class="rounded-circle shadow" style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #ffffff;" onerror="this.src='../../assets/adminLogo.png'">
                <label for="fotoInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" style="cursor: pointer; width: 38px; height: 38px;" title="Ganti Foto">
                  <i class="fa-solid fa-camera" style="font-size: 0.9rem;"></i>
                </label>
              </div>

              <h4 class="fw-bold text-dark text-capitalize mb-1"><?= htmlspecialchars($adminData["nama_admin"]); ?></h4>
              <p class="text-muted small mb-2"><i class="fa-solid fa-id-badge text-primary me-1"></i> Kode: <?= htmlspecialchars($adminData["kode_admin"]); ?></p>
              <div>
                <span class="badge-status primary"><i class="fa-solid fa-shield-halved"></i> Administrator Utama</span>
              </div>

              <hr class="my-4">

              <div class="text-start small">
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted"><i class="fa-solid fa-hashtag me-1"></i> ID Admin:</span>
                  <span class="fw-bold">#<?= htmlspecialchars($adminData["id"]); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted"><i class="fa-solid fa-phone me-1"></i> No. Telepon:</span>
                  <span class="fw-bold"><?= htmlspecialchars($adminData["no_tlp"] ?: "-"); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i> Status Akun:</span>
                  <span class="badge-status success py-0 px-2">Aktif</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Edit Profile Form -->
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-4 bg-white">
              <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Informasi Akun</h5>
              
              <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($adminData["id"]); ?>">
                <input type="hidden" name="fotoLama" value="<?= htmlspecialchars($adminData["foto"] ?? "default_admin.png"); ?>">

                <!-- Foto Upload Input Hidden/Linked -->
                <div class="mb-4">
                  <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-image text-primary me-1"></i> Unggah Foto Profil Baru</label>
                  <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(this)">
                  <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 3MB)</small>
                </div>

                <div class="row g-3 mb-4">
                  <!-- Nama Admin -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-user text-primary me-1"></i> Nama Lengkap Admin <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="text" name="nama_admin" class="form-control" value="<?= htmlspecialchars($adminData["nama_admin"]); ?>" required autocomplete="off">
                    </div>
                  </div>

                  <!-- Kode Admin (Read Only) -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-id-badge text-muted me-1"></i> Kode Admin (Username)</label>
                    <div class="form-floating-custom">
                      <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($adminData["kode_admin"]); ?>" readonly>
                    </div>
                  </div>

                  <!-- No. Telepon -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-phone text-primary me-1"></i> No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="tel" name="no_tlp" class="form-control" value="<?= htmlspecialchars($adminData["no_tlp"]); ?>" required autocomplete="off">
                    </div>
                  </div>
                </div>

                <!-- Password Change Section -->
                <div class="p-3 rounded-3 bg-light mb-4 border">
                  <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-lock text-warning me-1"></i> Ganti Kata Sandi (Opsional)</h6>
                  <p class="text-muted small mb-3">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>

                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-dark small">Kata Sandi Baru</label>
                      <input type="password" name="password_baru" class="form-control form-control-sm" placeholder="Masukkan sandi baru jika ingin diubah">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-semibold text-dark small">Konfirmasi Kata Sandi Baru</label>
                      <input type="password" name="confirm_password" class="form-control form-control-sm" placeholder="Ulangi sandi baru">
                    </div>
                  </div>
                </div>

                <!-- Action Button -->
                <div class="d-flex justify-content-end gap-2">
                  <a href="../dashboardAdmin.php" class="btn btn-modern-secondary px-4">Kembali</a>
                  <button type="submit" name="simpanProfil" class="btn btn-modern-primary px-4 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                  </button>
                </div>

              </form>

            </div>
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
    <script>
      function previewImage(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            document.getElementById('avatarPreview').src = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>
  </body>
</html>
