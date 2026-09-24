<?php
// Mencegah error PHP mentah merusak format JSON
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

require_once 'koneksi.php';

// Pengecekan koneksi database
if (!$koneksi) {
    echo json_encode([
        'status' => false,
        'message' => 'Koneksi ke database gagal: ' . mysqli_connect_error()
    ], JSON_PRETTY_PRINT);
    exit;
}

$query = "SELECT * FROM books ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);

// Pengecekan jika query error
if (!$result) {
    echo json_encode([
        'status' => false,
        'message' => 'Query error: ' . mysqli_error($koneksi)
    ], JSON_PRETTY_PRINT);
    exit;
}

$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

// Mengembalikan data JSON dengan format rapi (mudah dibaca saat dibuka langsung di browser)
echo json_encode($books, JSON_PRETTY_PRINT);
exit;
?>