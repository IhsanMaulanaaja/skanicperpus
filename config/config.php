<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database_name = "perpustakaan";
$connection = mysqli_connect($host, $username, $password, $database_name);

// === FUNCTION KHUSUS ADMIN START ===

// MENAMPILKAN DATA KATEGORI BUKU
function queryReadData($dataKategori) {
  global $connection;
  $result = mysqli_query($connection, $dataKategori);
  $items = [];
  while($item = mysqli_fetch_assoc($result)) {
    $items[] = $item;
  }     
  return $items;
}

// Menambahkan data buku 
function tambahBuku($dataBuku) {
  global $connection;
  
  $cover = upload();
  $idBuku = htmlspecialchars($dataBuku["id_buku"]);
  $kategoriBuku = $dataBuku["kategori"];
  $judulBuku = htmlspecialchars($dataBuku["judul"]);
  $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
  $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
  $tahunTerbit = $dataBuku["tahun_terbit"];
  $jumlahHalaman = $dataBuku["jumlah_halaman"];
  $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
  
  if(!$cover) {
    return 0;
  } 
  
  $queryInsertDataBuku = "INSERT INTO buku VALUES('$cover', '$idBuku', '$kategoriBuku', '$judulBuku', '$pengarangBuku', '$penerbitBuku', '$tahunTerbit', $jumlahHalaman, '$deskripsiBuku')";
  
  mysqli_query($connection, $queryInsertDataBuku);
  return mysqli_affected_rows($connection);
  
}       

// Function upload gambar 
function upload() {
  $namaFile = $_FILES["cover"]["name"];
  $ukuranFile = $_FILES["cover"]["size"];
  $error = $_FILES["cover"]["error"];
  $tmpName = $_FILES["cover"]["tmp_name"];
  
  // cek apakah ada gambar yg diupload
  if($error === 4) {
    echo "<script>
    alert('Silahkan upload cover buku terlebih dahulu!')
    </script>";
    return 0;
  }
  
  // cek kesesuaian format gambar
  $jpg = "jpg";
  $jpeg = "jpeg";
  $png = "png";
  $svg = "svg";
  $bmp = "bmp";
  $psd = "psd";
  $tiff = "tiff";
  $formatGambarValid = [$jpg, $jpeg, $png, $svg, $bmp, $psd, $tiff];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));
  
  if(!in_array($ekstensiGambar, $formatGambarValid)) {
    echo "<script>
    alert('Format file tidak sesuai');
    </script>";
    return 0;
  }
  
  // batas ukuran file
  if($ukuranFile > 2000000) {
    echo "<script>
    alert('Ukuran file terlalu besar!');
    </script>";
    return 0;
  }
  
   //generate nama file baru, agar nama file tdk ada yg sama
  $namaFileBaru = uniqid();
  $namaFileBaru .= ".";
  $namaFileBaru .= $ekstensiGambar;
  
  move_uploaded_file($tmpName, '../../imgDB/' . $namaFileBaru);
  return $namaFileBaru;
} 

// MENAMPILKAN SESUATU SESUAI DENGAN INPUTAN USER PADA * SEARCH ENGINE *
function search($keyword) {
  global $connection;
  $safeKeyword = mysqli_real_escape_string($connection, $keyword);
  // search data buku
  $querySearch = "SELECT * FROM buku 
  WHERE
  judul LIKE '%$safeKeyword%' OR
  kategori LIKE '%$safeKeyword%' OR
  pengarang LIKE '%$safeKeyword%' OR
  penerbit LIKE '%$safeKeyword%'
  ";
  return queryReadData($querySearch);
}

function searchMember ($keyword) {
  global $connection;
  $safeKeyword = mysqli_real_escape_string($connection, $keyword);
  // search member terdaftar || admin
  $searchMember = "SELECT * FROM member WHERE 
  nisn LIKE '%$safeKeyword%' OR 
  kode_member LIKE '%$safeKeyword%' OR
  nama LIKE '%$safeKeyword%' OR 
  jurusan LIKE '%$safeKeyword%' OR
  kelas LIKE '%$safeKeyword%'
  ";
  return queryReadData($searchMember);
}


// DELETE DATA Buku
function delete($bukuId) {
  global $connection;
  $queryDeleteBuku = "DELETE FROM buku WHERE id_buku = '$bukuId'
  ";
  mysqli_query($connection, $queryDeleteBuku);
  
  return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA BUKU 
function updateBuku($dataBuku) {
  global $connection;

  $gambarLama = htmlspecialchars($dataBuku["coverLama"]);
  $idBuku = htmlspecialchars($dataBuku["id_buku"]);
  $kategoriBuku = $dataBuku["kategori"];
  $judulBuku = htmlspecialchars($dataBuku["judul"]);
  $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
  $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
  $tahunTerbit = $dataBuku["tahun_terbit"];
  $jumlahHalaman = $dataBuku["jumlah_halaman"];
  $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
  
  
  // pengecekan mengganti gambar || tidak
  if($_FILES["cover"]["error"] === 4) {
    $cover = $gambarLama;
  }else {
    $cover = upload();
  }
  // 4 === gagal upload gambar
  // 0 === berhasil upload gambar
  
  $queryUpdate = "UPDATE buku SET 
  cover = '$cover',
  id_buku = '$idBuku',
  kategori = '$kategoriBuku',
  judul = '$judulBuku',
  pengarang = '$pengarangBuku',
  penerbit = '$penerbitBuku',
  tahun_terbit = '$tahunTerbit',
  jumlah_halaman = $jumlahHalaman,
  buku_deskripsi = '$deskripsiBuku'
  WHERE id_buku = '$idBuku'
  ";
  
  mysqli_query($connection, $queryUpdate);
  return mysqli_affected_rows($connection);
}

// Hapus member yang terdaftar
function deleteMember($nisnMember) {
  global $connection;
  
  $deleteMember = "DELETE FROM member WHERE nisn = $nisnMember";
  mysqli_query($connection, $deleteMember);
  return mysqli_affected_rows($connection);
}

// Hapus history pengembalian data BUKU
function deleteDataPengembalian($idPengembalian) {
  global $connection;
  
  $deleteDataPengembalianBuku = "DELETE FROM pengembalian WHERE id_pengembalian = $idPengembalian";
  mysqli_query($connection, $deleteDataPengembalianBuku);
  return mysqli_affected_rows($connection);
}


// Tambah Akun Admin Baru (Hanya bisa dilakukan oleh Admin)
function tambahAdmin($dataAdmin) {
  global $connection;
  
  $nama = mysqli_real_escape_string($connection, strtolower(trim($dataAdmin["nama_admin"])));
  $kodeAdmin = mysqli_real_escape_string($connection, trim($dataAdmin["kode_admin"]));
  $password = trim($dataAdmin["password"]);
  $confirmPw = trim($dataAdmin["confirmPw"]);
  $noTlp = mysqli_real_escape_string($connection, trim($dataAdmin["no_tlp"]));
  
  // Cek nama_admin sudah ada
  $cekNama = mysqli_query($connection, "SELECT id FROM admin WHERE nama_admin = '$nama'");
  if(mysqli_num_rows($cekNama) > 0) {
    echo "<script>alert('Nama Admin sudah digunakan! Silakan gunakan nama lain.');</script>";
    return 0;
  }
  
  // Cek kode_admin sudah ada
  $cekKode = mysqli_query($connection, "SELECT id FROM admin WHERE kode_admin = '$kodeAdmin'");
  if(mysqli_num_rows($cekKode) > 0) {
    echo "<script>alert('Kode Admin sudah digunakan! Silakan gunakan kode lain.');</script>";
    return 0;
  }
  
  // Cek konfirmasi password
  if($password !== $confirmPw) {
    echo "<script>alert('Password dan Konfirmasi Password tidak sesuai!');</script>";
    return 0;
  }
  
  $queryInsert = "INSERT INTO admin (nama_admin, password, kode_admin, no_tlp) VALUES ('$nama', '$password', '$kodeAdmin', '$noTlp')";
  mysqli_query($connection, $queryInsert);
  return mysqli_affected_rows($connection);
}

// Hapus Akun Admin
function deleteAdmin($idAdmin) {
  global $connection;
  
  // Pastikan tidak menghapus admin terakhir
  $count = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM admin"))[0] ?? 0;
  if($count <= 1) {
    echo "<script>alert('Tidak dapat menghapus admin! Harus ada setidaknya 1 akun admin di sistem.');</script>";
    return 0;
  }
  
  $safeId = (int)$idAdmin;
  $queryDelete = "DELETE FROM admin WHERE id = $safeId";
  mysqli_query($connection, $queryDelete);
  return mysqli_affected_rows($connection);
}

// === FUNCTION KHUSUS ADMIN END ===


// === FUNCTION PROFIL & AVATAR START ===

// Upload Foto Profil (Avatar)
function uploadAvatar($inputName = "foto", $default = "default_avatar.png") {
  $namaFile = $_FILES[$inputName]['name'];
  $ukuranFile = $_FILES[$inputName]['size'];
  $error = $_FILES[$inputName]['error'];
  $tmpName = $_FILES[$inputName]['tmp_name'];

  if($error === 4) {
    return $default; // tidak ada file baru yang diunggah
  }

  $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'webp'];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));

  if(!in_array($ekstensiGambar, $ekstensiGambarValid)) {
    echo "<script>alert('Format file foto tidak didukung! Gunakan format JPG, JPEG, PNG, atau WEBP.');</script>";
    return false;
  }

  if($ukuranFile > 3145728) { // 3 MB max
    echo "<script>alert('Ukuran foto terlalu besar! Maksimal 3MB.');</script>";
    return false;
  }

  $namaFileBaru = uniqid('avatar_') . '.' . $ekstensiGambar;
  $targetDir = __DIR__ . '/../imgDB/avatar/';
  if(!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
  }

  move_uploaded_file($tmpName, $targetDir . $namaFileBaru);
  return $namaFileBaru;
}

// Update Profil Admin
function updateProfilAdmin($data, $files) {
  global $connection;

  $id = (int)$data["id"];
  $nama = mysqli_real_escape_string($connection, strtolower(trim($data["nama_admin"])));
  $noTlp = mysqli_real_escape_string($connection, trim($data["no_tlp"]));
  $passwordBaru = trim($data["password_baru"] ?? "");
  $confirmPw = trim($data["confirm_password"] ?? "");
  $fotoLama = $data["fotoLama"] ?? "default_admin.png";

  // Cek upload foto baru
  if(isset($files["foto"]) && $files["foto"]["error"] !== 4) {
    $foto = uploadAvatar("foto", $fotoLama);
    if(!$foto) {
      return 0;
    }
  } else {
    $foto = $fotoLama;
  }

  if(!empty($passwordBaru)) {
    if($passwordBaru !== $confirmPw) {
      echo "<script>alert('Password baru dan konfirmasi password tidak cocok!');</script>";
      return 0;
    }
    $safePw = mysqli_real_escape_string($connection, $passwordBaru);
    $query = "UPDATE admin SET nama_admin = '$nama', no_tlp = '$noTlp', password = '$safePw', foto = '$foto' WHERE id = $id";
  } else {
    $query = "UPDATE admin SET nama_admin = '$nama', no_tlp = '$noTlp', foto = '$foto' WHERE id = $id";
  }

  mysqli_query($connection, $query);

  // Update session
  $_SESSION["admin"]["nama_admin"] = $nama;
  $_SESSION["admin"]["no_tlp"] = $noTlp;
  $_SESSION["admin"]["foto"] = $foto;

  return 1;
}

// Update Profil Member (Siswa)
function updateProfilMember($data, $files) {
  global $connection;

  $nisn = (int)$data["nisn"];
  $nama = mysqli_real_escape_string($connection, strtolower(trim($data["nama"])));
  $kelas = mysqli_real_escape_string($connection, trim($data["kelas"]));
  $jurusan = mysqli_real_escape_string($connection, trim($data["jurusan"]));
  $noTlp = mysqli_real_escape_string($connection, trim($data["no_tlp"]));
  $passwordBaru = trim($data["password_baru"] ?? "");
  $confirmPw = trim($data["confirm_password"] ?? "");
  $fotoLama = $data["fotoLama"] ?? "default_member.png";

  // Cek upload foto baru
  if(isset($files["foto"]) && $files["foto"]["error"] !== 4) {
    $foto = uploadAvatar("foto", $fotoLama);
    if(!$foto) {
      return 0;
    }
  } else {
    $foto = $fotoLama;
  }

  if(!empty($passwordBaru)) {
    if($passwordBaru !== $confirmPw) {
      echo "<script>alert('Password baru dan konfirmasi password tidak cocok!');</script>";
      return 0;
    }
    $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);
    $query = "UPDATE member SET nama = '$nama', kelas = '$kelas', jurusan = '$jurusan', no_tlp = '$noTlp', password = '$passwordHash', foto = '$foto' WHERE nisn = $nisn";
  } else {
    $query = "UPDATE member SET nama = '$nama', kelas = '$kelas', jurusan = '$jurusan', no_tlp = '$noTlp', foto = '$foto' WHERE nisn = $nisn";
  }

  mysqli_query($connection, $query);

  // Update session
  $_SESSION["member"]["nama"] = $nama;
  $_SESSION["member"]["kelas"] = $kelas;
  $_SESSION["member"]["jurusan"] = $jurusan;
  $_SESSION["member"]["no_tlp"] = $noTlp;
  $_SESSION["member"]["foto"] = $foto;

  return 1;
}

// === FUNCTION PROFIL & AVATAR END ===


// === FUNCTION KHUSUS MEMBER START ===
// Peminjaman BUKU
function pinjamBuku($dataBuku) {
  global $connection;
  
  $idBuku = $dataBuku["id_buku"];
  $nisn = $dataBuku["nisn"];
  $idAdmin = $dataBuku["id"];
  $tglPinjam = $dataBuku["tgl_peminjaman"];
  $tglKembali = $dataBuku["tgl_pengembalian"];
  // cek apakah user memiliki denda 
  $cekDenda = mysqli_query($connection, "SELECT denda FROM pengembalian WHERE nisn = $nisn && denda > 0");
  if(mysqli_num_rows($cekDenda) > 0) {
    $item = mysqli_fetch_assoc($cekDenda);
    $jumlahDenda = $item["denda"];
    if($jumlahDenda > 0) {
       echo "<script>
       alert('Anda belum melunasi denda, silahkan lakukan pembayaran terlebih dahulu !');
       </script>";
       return 0;
    }
  }
  // cek batas user meminjam buku berdasarkan nisn
  $nisnResult = mysqli_query($connection, "SELECT nisn FROM peminjaman WHERE nisn = $nisn");
  if(mysqli_fetch_assoc($nisnResult)) {
    echo "<script>
    alert('Anda sudah meminjam buku, Harap kembalikan dahulu buku yg anda pinjam!');
    </script>";
    return 0;
  }
  
  $queryPinjam = "INSERT INTO peminjaman VALUES(null, '$idBuku', $nisn, $idAdmin, '$tglPinjam', '$tglKembali')";
  mysqli_query($connection, $queryPinjam);
  return mysqli_affected_rows($connection);
}

// Pengembalian BUKU
function pengembalian($dataBuku) {
  global $connection;
  
  // Variabel pengembalian
  $idPeminjaman = $dataBuku["id_peminjaman"];
  $idBuku = $dataBuku["id_buku"];
  $nisn = $dataBuku["nisn"];
  $idAdmin = $dataBuku["id_admin"];
  $tenggatPengembalian = $dataBuku["tgl_pengembalian"];
  $bukuKembali = $dataBuku["buku_kembali"];
  $keterlambatan = $dataBuku["keterlambatan"];
  $denda = $dataBuku["denda"];
  
  if($bukuKembali > $tenggatPengembalian) {
    echo "<script>
    alert('Anda terlambat mengembalikan buku, harap bayar denda sesuai dengan jumlah yang ditentukan!');
    </script>";
  }
  
  // Menghapus data siswa yang sudah mengembalikan buku
  $hapusDataPeminjam = "DELETE FROM peminjaman WHERE id_peminjaman = $idPeminjaman";

  // Memasukkan data kedalam tabel pengembalian
  $queryPengembalian = "INSERT INTO pengembalian VALUES(null, $idPeminjaman, '$idBuku', $nisn, $idAdmin, '$bukuKembali', '$keterlambatan', $denda)";

  
  mysqli_query($connection, $hapusDataPeminjam);
  mysqli_query($connection, $queryPengembalian);
  return mysqli_affected_rows($connection);
}

function bayarDenda($data) {
  global $connection;
  $idPengembalian = $data["id_pengembalian"];
  $jmlDenda = $data["denda"];
  $jmlDibayar = $data["bayarDenda"];
  $calculate = $jmlDenda - $jmlDibayar;
  
  $bayarDenda = "UPDATE pengembalian SET denda = $calculate WHERE id_pengembalian = $idPengembalian";
  mysqli_query($connection, $bayarDenda);
  return mysqli_affected_rows($connection);
}

// === FUNCTION KHUSUS MEMBER END ===
?>


