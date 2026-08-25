<?php
require "config/config.php";

// 1. Tambah kolom 'foto' ke tabel admin jika belum ada
$checkAdminFoto = mysqli_query($connection, "SHOW COLUMNS FROM `admin` LIKE 'foto'");
if (mysqli_num_rows($checkAdminFoto) == 0) {
    mysqli_query($connection, "ALTER TABLE `admin` ADD COLUMN `foto` VARCHAR(255) NULL DEFAULT 'default_admin.png' AFTER `no_tlp`");
    echo "Kolom 'foto' berhasil ditambahkan ke tabel admin.\n";
} else {
    echo "Kolom 'foto' sudah ada di tabel admin.\n";
}

// 2. Tambah kolom 'foto' ke tabel member jika belum ada
$checkMemberFoto = mysqli_query($connection, "SHOW COLUMNS FROM `member` LIKE 'foto'");
if (mysqli_num_rows($checkMemberFoto) == 0) {
    mysqli_query($connection, "ALTER TABLE `member` ADD COLUMN `foto` VARCHAR(255) NULL DEFAULT 'default_member.png' AFTER `tgl_pendaftaran`");
    echo "Kolom 'foto' berhasil ditambahkan ke tabel member.\n";
} else {
    echo "Kolom 'foto' sudah ada di tabel member.\n";
}

// 3. Buat direktori avatar jika belum ada
$avatarDir = __DIR__ . "/imgDB/avatar";
if (!is_dir($avatarDir)) {
    mkdir($avatarDir, 0777, true);
    echo "Folder imgDB/avatar berhasil dibuat.\n";
}

// 4. Salin default avatar jika ada
if (file_exists(__DIR__ . "/assets/adminLogo.png")) {
    copy(__DIR__ . "/assets/adminLogo.png", $avatarDir . "/default_admin.png");
}
if (file_exists(__DIR__ . "/assets/memberLogo.png")) {
    copy(__DIR__ . "/assets/memberLogo.png", $avatarDir . "/default_member.png");
}

echo "Migrasi profil & avatar sukses!\n";
?>
