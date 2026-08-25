<?php 
session_start();

// Proteksi: Hanya Admin yang bisa mengakses script ini
if(!isset($_SESSION["signIn"]) || !isset($_SESSION["admin"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

require "../../config/config.php";

$idAdmin = $_GET["id"] ?? "";

if(!empty($idAdmin)) {
  // Cek apakah admin mencoba menghapus dirinya sendiri
  $currentId = $_SESSION["admin"]["id"] ?? 0;
  if((int)$idAdmin === (int)$currentId) {
    echo "<script>
      alert('Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
      document.location.href = 'kelolaAdmin.php';
    </script>";
    exit;
  }

  if(deleteAdmin($idAdmin) > 0) {
    echo "<script>
      alert('Akun admin berhasil dihapus!');
      document.location.href = 'kelolaAdmin.php';
    </script>";
  } else {
    echo "<script>
      alert('Gagal menghapus akun admin!');
      document.location.href = 'kelolaAdmin.php';
    </script>";
  }
} else {
  header("Location: kelolaAdmin.php");
  exit;
}
?>
