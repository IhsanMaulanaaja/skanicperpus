<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkanicPerpus - Sistem Perpustakaan Digital SMKN 1 Ciomas</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Modern CSS -->
    <link rel="stylesheet" href="style/main.css">
    <link rel="icon" href="assets/logoPerpus.jpg" type="image/jpeg">
  </head>
  <body>
    
    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
          <img src="assets/SkanicLogin.png" alt="SkanicPerpus" height="46" onerror="this.src='assets/logo_skanic.png'">
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars text-dark fs-4"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav align-items-center gap-1">
            <li class="nav-item">
              <a class="nav-link active" href="#homeSection"><i class="fa-solid fa-house me-1"></i> Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#featuresSection"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Fitur</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#aboutSection"><i class="fa-solid fa-circle-info me-1"></i> Tentang</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#footer"><i class="fa-solid fa-envelope me-1"></i> Kontak</a>
            </li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
              <a class="btn btn-modern-primary d-inline-flex align-items-center gap-2" href="sign/link_login.html">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk Portal
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper">
      <!-- Hero Section -->
      <section id="homeSection" class="container py-4">
        <div class="hero-section">
          <div class="row align-items-center g-5">
            <div class="col-lg-7">
              <div class="badge-status primary mb-3">
                <i class="fa-solid fa-bolt"></i> Perpustakaan Digital SMKN 1 Ciomas
              </div>
              <h1 class="display-4 fw-extrabold text-dark mb-3" style="font-weight: 800; letter-spacing: -0.03em;">
                Jelajahi Ilmu Tanpa Batas di <span style="color: var(--primary);">SkanicPerpus</span>
              </h1>
              <p class="lead text-muted mb-4" style="line-height: 1.7; font-size: 1.1rem;">
                Platform perpustakaan digital resmi SMKN 1 Ciomas yang memudahkan siswa dan pengajar dalam meminjam, membaca, dan mengelola koleksi buku perpustakaan secara cepat, fleksibel, dan terorganisir.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="sign/link_login.html" class="btn btn-modern-primary btn-lg d-inline-flex align-items-center gap-2">
                  <i class="fa-solid fa-book-open-reader"></i> Mulai Sekarang
                </a>
                <a href="#aboutSection" class="btn btn-modern-secondary btn-lg d-inline-flex align-items-center gap-2">
                  <i class="fa-solid fa-compass"></i> Pelajari Selengkapnya
                </a>
              </div>
            </div>
            <div class="col-lg-5 text-center">
              <div class="position-relative d-inline-block">
                <img src="assets/logoDashboard-transformed.jpeg" class="img-fluid rounded-4 shadow-lg" alt="SkanicPerpus Illustration" style="max-height: 380px; object-fit: cover;">
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section id="featuresSection" class="container py-5">
        <div class="text-center mb-5">
          <div class="badge-status secondary mb-2">
            <i class="fa-solid fa-star"></i> Fitur Unggulan
          </div>
          <h2 class="fw-bold text-dark">Mengapa Memilih SkanicPerpus?</h2>
          <p class="text-muted">Kemudahan akses dan pengelolaan buku perpustakaan dalam satu platform</p>
        </div>

        <div class="row g-4">
          <div class="col-md-6 col-lg-3">
            <div class="menu-card text-center p-4">
              <div class="menu-icon primary mx-auto">
                <i class="fa-solid fa-book-bookmark"></i>
              </div>
              <h4>Koleksi Beragam</h4>
              <p>Mulai dari Informatika, Bisnis, Novel, Filsafat, hingga Sains dengan kategori terorganisir.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3">
            <div class="menu-card text-center p-4">
              <div class="menu-icon success mx-auto">
                <i class="fa-solid fa-bolt-lightning"></i>
              </div>
              <h4>Peminjaman Kilat</h4>
              <p>Pinjam buku dengan verifikasi digital instan dan tanggal pengembalian otomatis tercatat.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3">
            <div class="menu-card text-center p-4">
              <div class="menu-icon warning mx-auto">
                <i class="fa-solid fa-clock-rotate-left"></i>
              </div>
              <h4>Riwayat Transparan</h4>
              <p>Pantau seluruh transaksi peminjaman, pengembalian, dan rincian denda dengan akurat.</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3">
            <div class="menu-card text-center p-4">
              <div class="menu-icon secondary mx-auto">
                <i class="fa-solid fa-shield-halved"></i>
              </div>
              <h4>Portal Admin & Siswa</h4>
              <p>Sistem manajemen terintegrasi dengan hak akses khusus untuk Admin dan Siswa.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- About Section -->
      <section class="container py-5" id="aboutSection">
        <div class="modern-card p-4 p-md-5">
          <div class="row align-items-center g-4">
            <div class="col-lg-7">
              <div class="badge-status primary mb-2">
                <i class="fa-solid fa-circle-info"></i> Tentang Aplikasi
              </div>
              <h2 class="fw-bold text-dark mb-3">Solusi Cerdas Literasi Digital Sekolah</h2>
              <p class="text-muted" style="text-align: justify; line-height: 1.8;">
                <strong>SkanicPerpus</strong> dirancang untuk memberi akses tak terbatas ke ribuan buku dan sumber daya pendidikan berkualitas. Kami percaya bahwa pengetahuan adalah kunci untuk membuka potensi diri. Dengan antarmuka yang ramah pengguna dan fitur pencarian canggih, kami berkomitmen untuk memajukan literasi dan pembelajaran sepanjang hayat di lingkungan SMKN 1 Ciomas.
              </p>
              <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="badge-status primary"><i class="fa-brands fa-php"></i> PHP & MySQL</span>
                <span class="badge-status info"><i class="fa-brands fa-bootstrap"></i> Bootstrap 5</span>
                <span class="badge-status success"><i class="fa-brands fa-js"></i> Modern JavaScript</span>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="stat-card bg-light border-0 shadow-sm p-4">
                <div class="stat-icon primary">
                  <i class="fa-solid fa-code"></i>
                </div>
                <div class="stat-info">
                  <p class="text-muted mb-1">Dikembangkan Oleh</p>
                  <h4 class="fw-bold mb-1" style="font-size: 1.25rem;">Ihsan Maulana Ardianto</h4>
                  <small class="text-secondary fw-semibold">SMKN 1 Ciomas</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Modern Footer -->
    <footer id="footer" class="footer-modern">
      <div class="container">
        <div class="row g-4 py-3">
          <div class="col-lg-5">
            <div class="d-flex align-items-center gap-2 mb-3">
              <img src="assets/logo_skanic.png" alt="SMKN 1 Ciomas" width="48" onerror="this.style.display='none'">
              <h5 class="text-white fw-bold mb-0">SkanicPerpus</h5>
            </div>
            <p class="text-light-50 small mb-0" style="color: #94a3b8; max-width: 380px;">
              Sistem Pengelolaan dan Layanan Perpustakaan Digital SMKN 1 Ciomas untuk kemajuan literasi dan pendidikan generasi muda.
            </p>
          </div>
          <div class="col-lg-4">
            <h6 class="text-white fw-semibold mb-3"><i class="fa-solid fa-location-dot text-primary me-2"></i> Lokasi</h6>
            <p class="small text-light-50 mb-0" style="color: #94a3b8;">
              Jl. Raya Laladon, Kec. Ciomas<br>
              Kab. Bogor, Jawa Barat 16610
            </p>
          </div>
          <div class="col-lg-3">
            <h6 class="text-white fw-semibold mb-3"><i class="fa-solid fa-share-nodes text-primary me-2"></i> Terhubung</h6>
            <div class="d-flex gap-3 fs-5">
              <a href="https://github.com/IhsanMaulanaaja" target="_blank" class="text-light" title="GitHub"><i class="fa-brands fa-github"></i></a>
              <a href="https://t.me/Sanzy_08" target="_blank" class="text-light" title="Telegram"><i class="fa-brands fa-telegram"></i></a>
              <a href="https://www.instagram.com/ihsnn44?igsh=NGkyd2ZmaWJ0cTh3" target="_blank" class="text-light" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
          </div>
        </div>
        <hr class="my-4" style="border-color: #334155;">
        <div class="d-flex flex-wrap justify-content-between align-items-center small text-light-50" style="color: #94a3b8;">
          <p class="mb-0">© 2025 SkanicPerpus • Dikembangkan oleh <span class="text-white fw-semibold">Ihsan Maulana Ardianto</span></p>
          <p class="mb-0">Versi 2.0 Modern</p>
        </div>
      </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>