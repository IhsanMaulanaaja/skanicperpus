<?php 
session_start();

// Proteksi akses: HANYA ADMIN yang boleh mengakses form ini
if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

if(isset($_POST["tambah"])) {
  if(tambahAdmin($_POST) > 0) {
    echo "<script>
      alert('Akun Admin Baru Berhasil Ditambahkan!');
      document.location.href = 'kelolaAdmin.php';
    </script>";
  } else {
    echo "<script>
      alert('Gagal Menambahkan Admin Baru!');
    </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Admin Baru - SkanicPerpus</title>
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
          <a class="btn btn-modern-secondary d-inline-flex align-items-center gap-2" href="kelolaAdmin.php">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Admin
          </a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-xl-7">
            
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
              
              <!-- Form Header -->
              <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="stat-icon primary m-0" style="width: 54px; height: 54px; font-size: 1.5rem;">
                  <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                  <h3 class="fw-bold text-dark mb-1">Tambah Petugas Admin Baru</h3>
                  <p class="text-muted small mb-0">Hanya administrator terdaftar yang memiliki hak akses untuk menambah petugas baru.</p>
                </div>
              </div>

              <!-- Form Body -->
              <form action="" method="post" class="needs-validation">
                
                <div class="row g-3 mb-3">
                  <!-- Nama Admin -->
                  <div class="col-md-12">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-user text-primary me-1"></i> Nama Lengkap Admin <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="text" name="nama_admin" class="form-control" placeholder="Contoh: Siti Rahmawati" required autocomplete="off">
                    </div>
                  </div>

                  <!-- Kode Admin & No Telp -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-id-badge text-primary me-1"></i> Kode / Username Admin <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="text" name="kode_admin" class="form-control" placeholder="Contoh: admin3" required autocomplete="off">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-phone text-primary me-1"></i> No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="tel" name="no_tlp" class="form-control" placeholder="Contoh: 081234567890" required autocomplete="off">
                    </div>
                  </div>

                  <!-- Password & Confirm Password -->
                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-lock text-primary me-1"></i> Password <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-lock-open text-primary me-1"></i> Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="form-floating-custom">
                      <input type="password" name="confirmPw" class="form-control" placeholder="Ulangi password" required>
                    </div>
                  </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info border-0 rounded-3 d-flex align-items-center gap-2 py-2 px-3 small mb-4">
                  <i class="fa-solid fa-shield-halved fs-5 text-primary"></i>
                  <span>Akun admin baru akan langsung aktif dan memiliki hak penuh mengelola data buku, peminjaman, dan member siswa.</span>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2">
                  <a href="kelolaAdmin.php" class="btn btn-modern-secondary px-4">
                    Batal
                  </a>
                  <button type="submit" name="tambah" class="btn btn-modern-primary px-4 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Admin Baru
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
  </body>
</html>
