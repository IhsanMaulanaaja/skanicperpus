/**
 * SkanicPerpus Helper Script
 * Robust date calculation, late fine calculation & WhatsApp-style Avatar Zoom Lightbox
 */

// Logic pengisian input peminjaman buku secara otomatis (7 hari dari tgl peminjaman)
function setReturnDate() {
  const borrowInput = document.getElementById("tgl_peminjaman");
  const returnInput = document.getElementById("tgl_pengembalian");
  
  if (!borrowInput || !returnInput || !borrowInput.value) return;

  const borrowDate = new Date(borrowInput.value);
  if (isNaN(borrowDate.getTime())) return;

  const returnDate = new Date(borrowDate);
  returnDate.setDate(borrowDate.getDate() + 7); // Menambahkan 7 hari batas pinjam

  const formattedDate = returnDate.toISOString().split('T')[0];
  returnInput.value = formattedDate;
}

// Logic pengisian input denda dan keterlambatan pengembalian buku secara otomatis
function hitungDenda() {
  const tglPengembalianEl = document.getElementById('tgl_pengembalian');
  const bukuKembaliEl = document.getElementById('buku_kembali');
  const keterlambatanEl = document.getElementById('keterlambatan');
  const dendaEl = document.getElementById('denda');

  // Pastikan semua elemen yang dibutuhkan tersedia di halaman
  if (!tglPengembalianEl || !bukuKembaliEl || !keterlambatanEl || !dendaEl) {
    return;
  }

  if (!tglPengembalianEl.value || !bukuKembaliEl.value) {
    keterlambatanEl.value = 'Tidak';
    dendaEl.value = 0;
    return;
  }

  const tglPengembalian = new Date(tglPengembalianEl.value);
  const bukuKembali = new Date(bukuKembaliEl.value);

  if (isNaN(tglPengembalian.getTime()) || isNaN(bukuKembali.getTime())) {
    return;
  }

  // Hitung selisih hari
  const diffTime = bukuKembali.getTime() - tglPengembalian.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if (diffDays > 0) {
    keterlambatanEl.value = `YA (${diffDays} Hari)`;
    // Denda Rp 10.000 (flat atau per keterlambatan sesuai logic aplikasi)
    dendaEl.value = 10000;
  } else {
    keterlambatanEl.value = 'Tidak';
    dendaEl.value = 0;
  }
}

// === WhatsApp-Style Avatar Zoom Lightbox Modal ===
document.addEventListener('DOMContentLoaded', function () {
  // Inject modal markup if not already present
  if (!document.getElementById('waAvatarModal')) {
    const modalHtml = `
      <div id="waAvatarModal" class="wa-avatar-modal" style="display:none;" tabindex="-1" role="dialog">
        <div class="wa-avatar-backdrop"></div>
        <div class="wa-avatar-dialog">
          <div class="wa-avatar-header">
            <div class="wa-avatar-title" id="waAvatarTitle">Foto Profil</div>
            <button type="button" class="wa-avatar-close" id="waAvatarClose" title="Tutup (ESC)">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>
          <div class="wa-avatar-body">
            <img id="waAvatarImg" src="" alt="Avatar Zoom" class="wa-avatar-full">
          </div>
        </div>
      </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
  }

  const modal = document.getElementById('waAvatarModal');
  const imgEl = document.getElementById('waAvatarImg');
  const titleEl = document.getElementById('waAvatarTitle');
  const closeBtn = document.getElementById('waAvatarClose');
  const backdrop = modal.querySelector('.wa-avatar-backdrop');

  function openModal(src, title) {
    imgEl.src = src;
    titleEl.textContent = title || 'Foto Profil';
    modal.style.display = 'flex';
    requestAnimationFrame(() => {
      modal.classList.add('show');
    });
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('show');
    setTimeout(() => {
      modal.style.display = 'none';
      imgEl.src = '';
      document.body.style.overflow = '';
    }, 220);
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
      closeModal();
    }
  });

  // Global event delegation for all avatar / profile images
  document.addEventListener('click', function (e) {
    const target = e.target.closest('img');
    if (!target) return;

    // Check if image is an avatar / profile image
    const isAvatar =
      target.classList.contains('avatar-clickable') ||
      target.classList.contains('wa-zoomable') ||
      target.id === 'avatarPreview' ||
      (target.src && (
        target.src.includes('/avatar/') ||
        target.src.includes('adminLogo') ||
        target.src.includes('memberLogo')
      ));

    if (isAvatar) {
      // If inside dropdown button, prevent opening dropdown so it zooms cleanly
      if (target.closest('.user-profile-btn')) {
        e.stopPropagation();
      }

      // Determine label / name title
      let title = target.alt || 'Foto Profil';
      const nameRow = target.closest('tr') ? target.closest('tr').querySelector('.fw-bold') : null;
      if (nameRow && nameRow.textContent.trim()) {
        title = nameRow.textContent.trim();
      } else if (target.closest('.card') && target.closest('.card').querySelector('h4, h5')) {
        title = target.closest('.card').querySelector('h4, h5').textContent.trim();
      }

      openModal(target.src, title);
    }
  }, true);
});
