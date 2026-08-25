<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbName = "perpustakaan";
$sqlFile = __DIR__ . "/database/perpustakaan SMKN 1 CIOMAS.sql";

echo "Connecting to MySQL server ($host)...\n";
$conn = @mysqli_connect($host, $username, $password);

if (!$conn) {
    echo "ERROR: Gagal terhubung ke MySQL: " . mysqli_connect_error() . "\n";
    echo "Pastikan MySQL di Laragon sudah di-Start.\n";
    exit(1);
}

echo "MySQL Connected successfully!\n";

// Check if database exists, create if not
$query = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!mysqli_query($conn, $query)) {
    echo "ERROR saat membuat database '$dbName': " . mysqli_error($conn) . "\n";
    exit(1);
}
echo "Database '$dbName' siap.\n";

// Select database
mysqli_select_db($conn, $dbName);

// Check if tables already exist
$tables = mysqli_query($conn, "SHOW TABLES");
if (mysqli_num_rows($tables) > 0) {
    echo "Tabel sudah ada di database '$dbName' (" . mysqli_num_rows($tables) . " tabel ditemukan).\n";
} else {
    if (!file_exists($sqlFile)) {
        echo "File SQL tidak ditemukan di: $sqlFile\n";
        exit(1);
    }

    echo "Mengimpor skema database dari: $sqlFile ...\n";
    $sqlContent = file_get_contents($sqlFile);
    
    // Execute multi query
    if (mysqli_multi_query($conn, $sqlContent)) {
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
        echo "SUKSES: Database '$dbName' dan seluruh tabel berhasil dibuat dan diimpor!\n";
    } else {
        echo "ERROR saat import SQL: " . mysqli_error($conn) . "\n";
        exit(1);
    }
}

// Show list of tables
$tables = mysqli_query($conn, "SHOW TABLES");
echo "\nDaftar Tabel di Database '$dbName':\n";
while ($row = mysqli_fetch_row($tables)) {
    echo " - " . $row[0] . "\n";
}

echo "\nSelesai! Silakan refresh halaman browser Anda.\n";
