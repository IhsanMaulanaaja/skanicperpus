<?php 
require "../../loginSystem/connect.php";

$success = false;
$errorMsg = "";

if(isset($_POST["signUp"])) {
  $result = signUp($_POST);
  if($result > 0) {
    $success = true;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun Siswa - SkanicPerpus</title>
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
    <div class="auth-wrapper" style="padding-top: 3rem; padding-bottom: 3rem;">
      <div class="auth-card" style="max-width: 650px;">
        <!-- Logo Badge -->
        <div class="auth-logo-badge">
          <img src="../../assets/memberLogo.png" alt="Member Icon" onerror="this.src='../../assets/logo_skanic.png'">
        </div>

        <div class="text-center mb-4">
          <span class="badge-status primary mb-2"><i class="fa-solid fa-user-plus"></i> Registrasi Member</span>
          <h3 class="fw-bold text-dark">Buat Akun Siswa</h3>
          <p class="text-muted small">Lengkapi data diri Anda untuk mulai meminjam buku di perpustakaan</p>
        </div>

        <?php if($success) : ?>
          <div class="alert alert-success d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
              <i class="fa-solid fa-circle-check fs-4"></i>
              <div>
                <strong>Pendaftaran Berhasil!</strong>
                <div class="small">Akun Anda telah aktif, silakan masuk.</div>
              </div>
            </div>
            <a href="sign_in.php" class="btn btn-success btn-sm">Login Sekarang</a>
          </div>
        <?php endif; ?>

        <form action="" method="post" class="needs-validation" novalidate>
          <div class="row g-3">
            <!-- NISN & Nama Lengkap -->
            <div class="col-md-6">
              <label for="nisn" class="form-label">NISN</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                <input type="number" class="form-control" name="nisn" id="nisn" placeholder="Contoh: 0071234567" required>
                <div class="invalid-feedback">NISN wajib diisi!</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="nama" class="form-label">Nama Lengkap</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                <div class="invalid-feedback">Nama wajib diisi!</div>
              </div>
            </div>

            <!-- Password & Confirm Password -->
            <div class="col-md-6">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Buat password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" tabindex="-1" title="Lihat/Sembunyikan Password">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <div class="invalid-feedback">Password wajib diisi!</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="confirmPw" class="form-label">Konfirmasi Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" id="confirmPw" name="confirmPw" placeholder="Ulangi password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmPw" tabindex="-1" title="Lihat/Sembunyikan Password">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <div class="invalid-feedback">Konfirmasi password wajib diisi!</div>
              </div>
            </div>

            <!-- Gender & Kelas -->
            <div class="col-md-6">
              <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
              <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                <option value="Laki laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
              <div class="invalid-feedback">Pilih jenis kelamin!</div>
            </div>

            <div class="col-md-6">
              <label for="kelas" class="form-label">Kelas</label>
              <select class="form-select" id="kelas" name="kelas" required>
                <option value="" selected disabled>Pilih Tingkat Kelas</option>
                <option value="X">Kelas X</option>
                <option value="XI">Kelas XI</option>
                <option value="XII">Kelas XII</option>
              </select>
              <div class="invalid-feedback">Pilih tingkat kelas!</div>
            </div>

            <!-- Jurusan -->
            <div class="col-12">
              <label for="jurusan" class="form-label">Jurusan / Kompetensi Keahlian</label>
              <select class="form-select" id="jurusan" name="jurusan" required>
                <option value="" selected disabled>Pilih Jurusan Anda</option>
                <option value="Pengembangan Perangkat Lunak dan Gim">Pengembangan Perangkat Lunak dan Gim (PPLG)</option>
                <option value="Broadcasting & Perfilman">Broadcasting & Perfilman (BCP)</option>
                <option value="Animasi">Animasi (ANM)</option>
                <option value="Teknik Otomotif">Teknik Otomotif (TO)</option>
                <option value="Teknik Pengelasan & Fabrikasi Logam">Teknik Pengelasan & Fabrikasi Logam (TPL)</option>
              </select>
              <div class="invalid-feedback">Pilih jurusan!</div>
            </div>

            <!-- No Telp & Tanggal Pendaftaran -->
            <div class="col-md-6">
              <label for="no_tlp" class="form-label">No. Telepon / WhatsApp</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                <input type="tel" class="form-control" name="no_tlp" id="no_tlp" placeholder="08xxxxxxxxxx" required>
                <div class="invalid-feedback">No telepon wajib diisi!</div>
              </div>
            </div>

            <div class="col-md-6">
              <label for="tgl_pendaftaran" class="form-label">Tanggal Pendaftaran</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                <input type="date" class="form-control" name="tgl_pendaftaran" id="tgl_pendaftaran" value="<?php echo date('Y-m-d'); ?>" required>
                <div class="invalid-feedback">Tanggal pendaftaran wajib diisi!</div>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4 pt-2">
            <button class="btn btn-modern-primary flex-grow-1 py-2" type="submit" name="signUp">
              <i class="fa-solid fa-user-plus me-1"></i> Daftar Sekarang
            </button>
            <button type="reset" class="btn btn-modern-secondary py-2">
              <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
          </div>

          <div class="text-center mt-3 pt-3 border-top">
            <p class="text-muted small mb-0">Sudah memiliki akun? <a href="sign_in.php" class="text-primary fw-bold text-decoration-none">Masuk Disini</a></p>
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