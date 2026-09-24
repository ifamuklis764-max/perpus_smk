<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
require_once 'koneksi.php';

// Fetch statistik dari database
$total_buku     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM books"))['total'] ?? 0;
$total_kategori = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT kategori) as total FROM books"))['total'] ?? 0;
$total_penulis  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT penulis) as total FROM books"))['total'] ?? 0;
$tahun_terbaru  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(tahun_terbit) as terbaru FROM books"))['terbaru'] ?? '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - perpus_api</title>
    <link rel="stylesheet" href="style.css">
    <!-- Menggunakan Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar Kiri (Dark Theme) -->
    <aside class="main-sidebar">
        <div class="sidebar-logo">
            perpus_api
        </div>
        <div class="user-panel">
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)); ?>
            </div>
            <div class="info">
                <p><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></p>
                <div class="status"><i class="fa fa-circle"></i> Online</div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active">
                <a href="dashboard.php">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="tambah_buku.php">
                    <i class="fa fa-plus"></i> <span>Tambah Buku</span>
                </a>
            </li>
            <li>
                <a href="export_excel.php">
                    <i class="fa fa-file-excel"></i> <span>Export Excel</span>
                </a>
            </li>
            <li>
                <a href="export_pdf.php" target="_blank">
                    <i class="fa fa-file-pdf"></i> <span>Export PDF</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="fa fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Konten Utama Kanan -->
    <div class="content-wrapper">
        <!-- Header Atas -->
        <header class="main-header">
            <div>
                <a href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </header>

        <!-- Header Halaman -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
        </section>

        <!-- Isi Konten -->
        <section class="content">
            <!-- 4 Stat Box Berwarna -->
            <div class="row">
                <!-- Box 1: Total Buku -->
                <div class="col-4">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?= $total_buku; ?></h3>
                            <p>Total Buku</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-book"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box 2: Total Kategori -->
                <div class="col-4">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= $total_kategori; ?></h3>
                            <p>Kategori</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tags"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box 3: Total Penulis -->
                <div class="col-4">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?= $total_penulis; ?></h3>
                            <p>Data Penulis</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- Box 4: Terbit Terbaru -->
                <div class="col-4">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?= $tahun_terbaru; ?></h3>
                            <p>Terbit Terbaru</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Box Tabel Data Buku -->
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list"></i> Data Buku Perpustakaan</h3>
                </div>
                <div class="box-body">
                    <div class="actions-bar">
                        <a href="tambah_buku.php" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Buku</a>
                        <a href="export_excel.php" class="btn btn-success"><i class="fa fa-file-excel"></i> Export Excel</a>
                        <a href="export_pdf.php" class="btn btn-danger" target="_blank"><i class="fa fa-file-pdf"></i> Export PDF</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE BUKU</th>
                                <th>JUDUL</th>
                                <th>PENULIS</th>
                                <th>KATEGORI</th>
                                <th>TAHUN</th>
                                <th>PENERBIT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="bukuTableBody">
                            <tr>
                                <td colspan="8" style="text-align: center;">Memuat data dari API...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- SCRIPT FETCH API JSON -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Memanggil Endpoint API
            fetch("data_json.php")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(responseData => {
                    const tbody = document.getElementById("bukuTableBody");
                    tbody.innerHTML = "";

                    // Cek jika response berupa objek wrapper { status: true, data: [...] } atau langsung Array [...]
                    const dataBuku = Array.isArray(responseData) ? responseData : (responseData.data || []);

                    if (dataBuku.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Data buku kosong.</td></tr>';
                        return;
                    }

                    dataBuku.forEach((buku, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td><strong>${buku.kode_buku || '-'}</strong></td>
                                <td>${buku.judul || '-'}</td>
                                <td>${buku.penulis || '-'}</td>
                                <td><span style="background: #e4e4e4; padding: 2px 6px; border-radius: 3px; font-size: 12px;">${buku.kategori || '-'}</span></td>
                                <td>${buku.tahun_terbit || '-'}</td>
                                <td>${buku.penerbit || '-'}</td>
                                <td>
                                    <a href="edit.php?id=${buku.id}" class="btn btn-warning" style="padding: 2px 6px; font-size: 12px;"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="hapus_buku.php?id=${encodeURIComponent(buku.id)}" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger" style="padding: 2px 6px; font-size: 12px;"><i class="fa fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error("Error Fetching Data:", error);
                    document.getElementById("bukuTableBody").innerHTML = 
                        '<tr><td colspan="8" style="text-align:center; color:red;">Gagal mengambil data dari API! Pastikan file data_json.php ada dan tidak error.</td></tr>';
                });
        });
    </script>
</body>
</html>