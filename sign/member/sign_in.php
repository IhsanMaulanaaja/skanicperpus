<?php 
session_start();

// Jika member sudah login, arahkan ke dashboard member
if(isset($_SESSION["signIn"]) && isset($_SESSION["member"])) {
  header("Location: ../../DashboardMember/dashboardMember.php");
  exit;
}

require "../../loginSystem/connect.php";

$error = false;

if(isset($_POST["signIn"])) {
  $nama = mysqli_real_escape_string($connect, strtolower(trim($_POST["nama"])));
  $nisn = mysqli_real_escape_string($connect, trim($_POST["nisn"]));
  $password = trim($_POST["password"]);
  
  if(!empty($nisn) && is_numeric($nisn)) {
    $result = mysqli_query($connect, "SELECT * FROM member WHERE (nama = '$nama' OR nisn = '$nisn') AND nisn = '$nisn'");
    
    if($result && mysqli_num_rows($result) === 1) {
      $pw = mysqli_fetch_assoc($result);
      if(password_verify($password, $pw["password"])) {
        // SET SESSION 
        $_SESSION["signIn"] = true;
        $_SESSION["member"]["nama"] = $pw["nama"];
        $_SESSION["member"]["nisn"] = $pw["nisn"];
        $_SESSION["member"]["kode_member"] = $pw["kode_member"];
        $_SESSION["member"]["kelas"] = $pw["kelas"];
        $_SESSION["member"]["jurusan"] = $pw["jurusan"];
        $_SESSION["member"]["no_tlp"] = $pw["no_tlp"] ?? "";
        $_SESSION["member"]["jenis_kelamin"] = $pw["jenis_kelamin"] ?? "";
        $_SESSION["member"]["tgl_pendaftaran"] = $pw["tgl_pendaftaran"] ?? "";
        $_SESSION["member"]["foto"] = !empty($pw["foto"]) ? $pw["foto"] : "default_member.png";
        header("Location: ../../DashboardMember/dashboardMember.php");
        exit;
      }
    }
  }
  $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Siswa - SkanicPerpus</title>
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
    <div class="auth-wrapper">
      <div class="auth-card">
        <!-- Logo Badge -->
        <div class="auth-logo-badge">
          <img src="../../assets/memberLogo.png" alt="Member Icon" onerror="this.src='../../assets/logo_skanic.png'">
        </div>

        <div class="text-center mb-4">
          <span class="badge-status primary mb-2"><i class="fa-solid fa-graduation-cap"></i> Portal Siswa</span>
          <h3 class="fw-bold text-dark">Selamat Datang</h3>
          <p class="text-muted small">Silakan masuk menggunakan nama, NISN, dan password Anda</p>
        </div>

        <?php if($error) : ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3" role="alert">
            <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
            <div>Nama, NISN, atau Password tidak sesuai!</div>
          </div>
        <?php endif; ?>

        <form action="" method="post" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
              <input type="text" class="form-control" name="nama" id="nama" placeholder="Contoh: Budi Santoso" required autofocus>
              <div class="invalid-feedback">Nama lengkap wajib diisi!</div>
            </div>
          </div>

          <div class="mb-3">
            <label for="nisn" class="form-label">NISN (Nomor Induk Siswa Nasional)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
              <input type="number" class="form-control" name="nisn" id="nisn" placeholder="Contoh: 0071234567" required>
              <div class="invalid-feedback">NISN wajib diisi!</div>
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
              <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
              <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" tabindex="-1" title="Lihat/Sembunyikan Password">
                <i class="fa-solid fa-eye"></i>
              </button>
              <div class="invalid-feedback">Password wajib diisi!</div>
            </div>
          </div>

          <div class="d-grid gap-2 mb-3">
            <button class="btn btn-modern-primary py-2" type="submit" name="signIn">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
            </button>
            <a class="btn btn-modern-secondary py-2" href="../link_login.html">
              <i class="fa-solid fa-arrow-left me-1"></i> Batal
            </a>
          </div>

          <div class="text-center mt-3 pt-3 border-top">
            <p class="text-muted small mb-0">Belum memiliki akun? <a href="sign_up.php" class="text-primary fw-bold text-decoration-none">Daftar Akun Baru</a></p>
          </div>
        </form>
      </div>
    </div>

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

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(btn => {
          btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input) {
              if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
              } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
              }
            }
          });
        });
      })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>