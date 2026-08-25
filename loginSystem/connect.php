<?php
// FILE LOGIN SYSTEM 
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "perpustakaan";
$connect = mysqli_connect($host, $username, $password, $database);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

/* SIGN UP Member */
function signUp($data) {
  global $connect;
  
  $nisn = mysqli_real_escape_string($connect, htmlspecialchars($data["nisn"] ?? ""));
  
  if (!empty($data["kode_member"])) {
    $kodeMember = mysqli_real_escape_string($connect, htmlspecialchars($data["kode_member"]));
  } else {
    // Generate kode member otomatis, misal: MBR + 4 digit akhir NISN atau random 4 digit
    $kodeMember = "MBR" . substr(str_pad($nisn, 4, "0", STR_PAD_LEFT), -4);
  }
  
  $nama = mysqli_real_escape_string($connect, htmlspecialchars(strtolower($data["nama"] ?? "")));
  $password = mysqli_real_escape_string($connect, $data["password"] ?? "");
  $confirmPw = mysqli_real_escape_string($connect, $data["confirmPw"] ?? "");
  $jk = mysqli_real_escape_string($connect, htmlspecialchars($data["jenis_kelamin"] ?? ""));
  $kelas = mysqli_real_escape_string($connect, htmlspecialchars($data["kelas"] ?? ""));
  $jurusan = mysqli_real_escape_string($connect, htmlspecialchars($data["jurusan"] ?? ""));
  $noTlp = mysqli_real_escape_string($connect, htmlspecialchars($data["no_tlp"] ?? ""));
  $tglDaftar = mysqli_real_escape_string($connect, $data["tgl_pendaftaran"] ?? date('Y-m-d'));
  
  // cek nisn sudah ada / belum 
  $nisnResult = mysqli_query($connect, "SELECT nisn FROM member WHERE nisn = '$nisn'");
  if(mysqli_fetch_assoc($nisnResult)) {
    echo "<script>
    alert('NISN sudah terdaftar, silahkan gunakan NISN lain!');
    </script>";
    return 0;
  }
  
  // cek kodeMember sudah ada / belum
  $kodeMemberResult = mysqli_query($connect, "SELECT kode_member FROM member WHERE kode_member = '$kodeMember'");
  if(mysqli_fetch_assoc($kodeMemberResult)){
    if (!empty($data["kode_member"])) {
      echo "<script>
      alert('Kode member telah terdaftar, silahkan gunakan kode member lain!');
      </script>";
      return 0;
    } else {
      $kodeMember = "MBR" . rand(1000, 9999);
    }
  }
  
  // Pengecekan kesamaan confirm password dan password
  if($password !== $confirmPw) {
    echo "<script>
    alert('Password dan Confirm Password tidak sesuai!');
    </script>";
    return 0;
  }
  
  // Enkripsi password
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);
  
  $querySignUp = "INSERT INTO member (nisn, kode_member, nama, password, jenis_kelamin, kelas, jurusan, no_tlp, tgl_pendaftaran) 
                  VALUES ('$nisn', '$kodeMember', '$nama', '$passwordHash', '$jk', '$kelas', '$jurusan', '$noTlp', '$tglDaftar')";
  
  mysqli_query($connect, $querySignUp);
  
  return mysqli_affected_rows($connect);
}
?>
