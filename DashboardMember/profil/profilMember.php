<?php
session_start();

if(!isset($_SESSION["signIn"]) || !isset($_SESSION["member"])) {
  header("Location: ../../sign/member/sign_in.php");
  exit;
}

require "../../config/config.php";

$nisnMember = (int)$_SESSION["member"]["nisn"];
$memberData = queryReadData("SELECT * FROM member WHERE nisn = $nisnMember")[0] ?? null;

if(!$memberData) {
  header("Location: ../dashboardMember.php");
  exit;
}

$successMsg = "";
$errorMsg = "";

if(isset($_POST["simpanProfil"])) {
  if(updateProfilMember($_POST, $_FILES) > 0) {
    $successMsg = "Profil dan foto Anda berhasil diperbarui!";
    // Refresh member data
    $memberData = queryReadData("SELECT * FROM member WHERE nisn = $nisnMember")[0];
  } else {
    $errorMsg = "Gagal memperbarui profil. Pastikan data dan format file foto valid.";
  }
}

$fotoMember = !empty($memberData["foto"]) ? $memberData["foto"] : "default_member.png";
$avatarPath = "../../imgDB/avatar/" . $fotoMember;
if(!file_exists(__DIR__ . "/../../imgDB/avatar/" . $fotoMember) && !file_exists(__DIR__ . "/../../assets/" . $fotoMember)) {
  $avatarPath = "../../assets/memberLogo.png";
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - Siswa SkanicPerpus</title>
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
      <div class="container-fluid px-lg-4 py-3">
        
        <!-- Header -->
        <div class="mb-4">
          <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i> Profil & Kartu Anggota Siswa</h3>
          <p class="text-muted small mb-0">Informasi identitas keanggotaan perpustakaan digital SMKN 1 Ciomas.</p>
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
          
          <!-- Left Column: Digital Member Card -->
          <div class="col-lg-5 col-xl-4">
            
            <!-- Digital ID Card -->
            <div class="card border-0 shadow-lg rounded-4 p-4 mb-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                  <img src="../../assets/logo_skanic.png" alt="Logo" height="32" onerror="this.src='../../assets/logoPerpus.jpg'">
                  <div>
                    <div class="fw-bold small" style="letter-spacing: 0.5px;">KARTU PERPUSTAKAAN</div>
                    <div class="text-white-50" style="font-size: 0.65rem;">SMKN 1 CIOMAS</div>
                  </div>
                </div>
                <span class="badge bg-primary text-white py-1 px-2" style="font-size: 0.7rem;">MEMBER</span>
              </div>

              <div class="d-flex align-items-center gap-3 my-3">
                <img id="avatarPreview" src="<?= $avatarPath; ?>" alt="Foto Profil" class="rounded-3 shadow" style="width: 80px; height: 95px; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);" onerror="this.src='../../assets/memberLogo.png'">
                <div>
                  <h5 class="fw-bold mb-1 text-capitalize text-white"><?= htmlspecialchars($memberData["nama"]); ?></h5>
                  <div class="small text-white-50">NISN: <span class="text-warning fw-bold"><?= htmlspecialchars($memberData["nisn"]); ?></span></div>
                  <div class="small text-white-50">Kelas: <span class="text-white fw-semibold"><?= htmlspecialchars($memberData["kelas"]); ?> - <?= htmlspecialchars($memberData["jurusan"]); ?></span></div>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-end pt-2 border-top border-secondary">
                <div style="font-size: 0.7rem;" class="text-white-50">
                  <div>Kode: <strong><?= htmlspecialchars($memberData["kode_member"]); ?></strong></div>
                  <div>Terdaftar: <?= date('d M Y', strtotime($memberData["tgl_pendaftaran"])); ?></div>
                </div>
                <div class="text-end">
                  <i class="fa-solid fa-qrcode fs-2 text-white-50"></i>
                </div>
              </div>
            </div>

            <!-- Quick Action Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
              <div class="mb-3">
                <label for="fotoInput" class="btn btn-modern-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                  <i class="fa-solid fa-camera"></i> Pilih Foto Profil Baru
                </label>
              </div>
              <small class="text-muted d-block">Pilih file foto dari galeri/komputer Anda, lalu tekan tombol <strong>"Simpan Perubahan"</strong> di bawah form.</small>
            </div>

          </div>

          <!-- Right Column: Edit Profile Form -->
          <div class="col-lg-7 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-4 bg-white">
              <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom"><i class="fa-solid fa-user-pen text-primary me-2"></i> Pengaturan Data Diri</h5>
              
              <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="nisn" value="<?= htmlspecialchars($memberData["nisn"]); ?>">
                <input type="hidden" name="fotoLama" value="<?= htmlspecialchars($memberData["foto"] ?? "default_member.png"); ?>">

                <!-- Hidden file input triggered by button -->
                <div class="mb-4">
                  <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-image text-primary me-1"></i> Unggah Foto Profil (Avatar)</label>
                  <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(this)">
                  <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 3MB)</small>
                </div>

                <div class="row g-3 mb-4">
                  <!-- Nama Lengkap -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-user text-primary me-1"></i> Nama Lengkap Siswa <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($memberData["nama"]); ?>" required autocomplete="off">
                    </div>
                  </div>

                  <!-- NISN (Read Only) -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-id-card text-muted me-1"></i> NISN (Nomor Induk Siswa Nasional)</label>
                    <div class="form-floating-custom">
                      <input type="text" class="form-control bg-light text-muted" value="<?= htmlspecialchars($memberData["nisn"]); ?>" readonly>
                    </div>
                  </div>

                  <!-- Kelas -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-chalkboard-user text-primary me-1"></i> Tingkat Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" class="form-select" required>
                      <option value="X" <?= ($memberData["kelas"] === "X") ? "selected" : ""; ?>>Kelas X</option>
                      <option value="XI" <?= ($memberData["kelas"] === "XI") ? "selected" : ""; ?>>Kelas XI</option>
                      <option value="XII" <?= ($memberData["kelas"] === "XII") ? "selected" : ""; ?>>Kelas XII</option>
                      <option value="XIII" <?= ($memberData["kelas"] === "XIII") ? "selected" : ""; ?>>Kelas XIII</option>
                    </select>
                  </div>

                  <!-- Jurusan -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-graduation-cap text-primary me-1"></i> Konsentrasi Keahlian / Jurusan <span class="text-danger">*</span></label>
                    <select name="jurusan" class="form-select" required>
                      <option value="Rekayasa Perangkat Lunak" <?= ($memberData["jurusan"] === "Rekayasa Perangkat Lunak") ? "selected" : ""; ?>>Rekayasa Perangkat Lunak</option>
                      <option value="Teknik Komputer dan Jaringan" <?= ($memberData["jurusan"] === "Teknik Komputer dan Jaringan") ? "selected" : ""; ?>>Teknik Komputer dan Jaringan</option>
                      <option value="Desain Komunikasi Visual" <?= ($memberData["jurusan"] === "Desain Komunikasi Visual") ? "selected" : ""; ?>>Desain Komunikasi Visual</option>
                      <option value="Animasi" <?= ($memberData["jurusan"] === "Animasi") ? "selected" : ""; ?>>Animasi</option>
                      <option value="Pengembangan Perangkat Lunak dan Gim" <?= ($memberData["jurusan"] === "Pengembangan Perangkat Lunak dan Gim") ? "selected" : ""; ?>>Pengembangan Perangkat Lunak dan Gim</option>
                      <option value="Teknik Kendaraan Ringan" <?= ($memberData["jurusan"] === "Teknik Kendaraan Ringan") ? "selected" : ""; ?>>Teknik Kendaraan Ringan</option>
                      <option value="Broadcasting dan Perfilman" <?= ($memberData["jurusan"] === "Broadcasting dan Perfilman") ? "selected" : ""; ?>>Broadcasting dan Perfilman</option>
                      <option value="Akuntansi" <?= ($memberData["jurusan"] === "Akuntansi") ? "selected" : ""; ?>>Akuntansi</option>
                    </select>
                  </div>

                  <!-- No. Telepon -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-phone text-primary me-1"></i> No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="tel" name="no_tlp" class="form-control" value="<?= htmlspecialchars($memberData["no_tlp"]); ?>" required autocomplete="off">
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

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                  <a href="../dashboardMember.php" class="btn btn-modern-secondary px-4">Kembali</a>
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
        <p class="mb-0">SkanicPerpus Member • v2.0</p>
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
