<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
require_once 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_buku    = mysqli_real_escape_string($koneksi, $_POST['kode_buku']);
    $judul        = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $penerbit     = mysqli_real_escape_string($koneksi, $_POST['penerbit']);

    $query = "UPDATE books SET 
              kode_buku='$kode_buku', judul='$judul', penulis='$penulis', 
              kategori='$kategori', tahun_terbit='$tahun_terbit', penerbit='$penerbit' 
              WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: dashboard.php");
        exit;
    }
}

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM books WHERE id='$id'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container" style="width: 500px; margin-top: 50px;">
        <h2>Edit Data Buku</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Kode Buku</label>
                <input type="text" name="kode_buku" value="<?= htmlspecialchars($data['kode_buku']); ?>" required>
            </div>
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']); ?>" required>
            </div>
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($data['tahun_terbit']); ?>" required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" value="<?= htmlspecialchars($data['penerbit']); ?>" required>
            </div>
            <button type="submit" class="btn">Update</button>
            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>