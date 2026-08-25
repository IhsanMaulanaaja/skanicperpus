<?php
session_start();

if(isset($_SESSION["signIn"]) && isset($_SESSION["admin"])) {
  header("Location: ../../DashboardAdmin/dashboardAdmin.php");
  exit;
}

require "../../loginSystem/connect.php";

$error = false;

if(isset($_POST["signIn"])) {
  $nama = mysqli_real_escape_string($connect, strtolower(trim($_POST["nama_admin"])));
  $password = trim($_POST["password"]);
  
  $result = mysqli_query($connect, "SELECT * FROM admin WHERE nama_admin = '$nama' AND password = '$password'");
  
  if(mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    // SET SESSION 
    $_SESSION["signIn"] = true;
    $_SESSION["admin"]["id"] = $row["id"];
    $_SESSION["admin"]["nama_admin"] = $row["nama_admin"];
    $_SESSION["admin"]["kode_admin"] = $row["kode_admin"];
    $_SESSION["admin"]["no_tlp"] = $row["no_tlp"] ?? "";
    $_SESSION["admin"]["foto"] = !empty($row["foto"]) ? $row["foto"] : "default_admin.png";
    header("Location: ../../DashboardAdmin/dashboardAdmin.php");
    exit;
  } else {
    $error = true;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Admin - SkanicPerpus</title>
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
          <img src="../../assets/adminLogo.png" alt="Admin Icon" onerror="this.src='../../assets/logo_skanic.png'">
        </div>

        <div class="text-center mb-4">
          <span class="badge-status secondary mb-2"><i class="fa-solid fa-shield-halved"></i> Administrator</span>
          <h3 class="fw-bold text-dark">Portal Admin</h3>
          <p class="text-muted small">Silakan masukkan nama admin dan kata sandi Anda</p>
        </div>

        <?php if($error) : ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3" role="alert">
            <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
            <div>Nama admin atau password tidak sesuai!</div>
          </div>
        <?php endif; ?>

        <form action="" method="post" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama Admin</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-user-tie"></i></span>
              <input type="text" class="form-control" name="nama_admin" id="nama_admin" placeholder="Contoh: admin1" required autofocus>
              <div class="invalid-feedback">Nama admin wajib diisi!</div>
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
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