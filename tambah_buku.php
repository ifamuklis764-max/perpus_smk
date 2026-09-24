<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
require_once 'koneksi.php';

$error = '';
$form_data = [
    'kode_buku' => '',
    'judul' => '',
    'penulis' => '',
    'kategori' => '',
    'tahun_terbit' => '',
    'penerbit' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $field => $value) {
        $form_data[$field] = trim($_POST[$field] ?? '');
    }

    $kode_buku    = mysqli_real_escape_string($koneksi, $form_data['kode_buku']);
    $judul        = mysqli_real_escape_string($koneksi, $form_data['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $form_data['penulis']);
    $kategori     = mysqli_real_escape_string($koneksi, $form_data['kategori']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $form_data['tahun_terbit']);
    $penerbit     = mysqli_real_escape_string($koneksi, $form_data['penerbit']);

    if (!empty($kode_buku) && !empty($judul)) {
        $query = "INSERT INTO books (kode_buku, judul, penulis, kategori, tahun_terbit, penerbit) 
                  VALUES ('$kode_buku', '$judul', '$penulis', '$kategori', '$tahun_terbit', '$penerbit')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Gagal menyimpan data buku!";
        }
    } else {
        $error = "Kode buku dan Judul wajib diisi!";
    }
}

$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - perpus_api</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .book-form-box {
            max-width: 860px;
            overflow: hidden;
        }

        .book-form-box .box-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fbff, #ffffff);
        }

        .book-form-box .box-body {
            padding: 24px;
        }

        .book-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 20px;
        }

        .book-form-grid .full-width {
            grid-column: 1 / -1;
        }

        .form-help {
            margin: -6px 0 20px;
            color: #64748b;
            font-size: 12px;
        }

        .form-alert {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 20px 24px 0;
            padding: 12px 14px;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            color: #9f1239;
            background: #fff1f2;
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .book-form-grid {
                grid-template-columns: 1fr;
            }

            .book-form-grid .full-width {
                grid-column: auto;
            }

            .book-form-box .box-body {
                padding: 18px;
            }

            .form-alert {
                margin-right: 18px;
                margin-left: 18px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <div class="sidebar-logo">
            <i class="fa-solid fa-book-bookmark"></i> perpus_api
        </div>
        <div class="user-panel">
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
            </div>
            <div class="info">
                <p><?= htmlspecialchars($_SESSION['admin_username']); ?></p>
                <div class="status"><i class="fa fa-circle"></i> Online</div>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="dashboard.php">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
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

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <header class="main-header">
            <div>
                <a href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </header>

        <section class="content-header">
            <h1>
                Tambah Buku
                <small>Input Data Buku Baru</small>
            </h1>
        </section>

        <section class="content">
            <div class="box book-form-box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-book"></i> Form Tambah Buku</h3>
                </div>
                
                <?php if ($error): ?>
                    <div class="form-alert">
                        <i class="fa fa-exclamation-circle"></i> <?= $escape($error); ?>
                    </div>
                <?php endif; ?>

                <div class="box-body">
                    <p class="form-help">Isi data buku dengan lengkap. Kolom bertanda (*) wajib diisi.</p>
                    <form action="tambah_buku.php" method="POST">
                        <div class="book-form-grid">
                            <div class="form-group">
                                <label for="kode_buku">Kode Buku *</label>
                                <input type="text" name="kode_buku" id="kode_buku" class="form-control" placeholder="Contoh: BK011" value="<?= $escape($form_data['kode_buku']); ?>" maxlength="30" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="judul">Judul Buku *</label>
                                <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan judul buku" value="<?= $escape($form_data['judul']); ?>" maxlength="150" required>
                            </div>

                            <div class="form-group">
                                <label for="penulis">Penulis</label>
                                <input type="text" name="penulis" id="penulis" class="form-control" placeholder="Nama penulis" value="<?= $escape($form_data['penulis']); ?>" maxlength="100">
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Contoh: Novel, Teknologi" value="<?= $escape($form_data['kategori']); ?>" maxlength="80">
                            </div>

                            <div class="form-group">
                                <label for="tahun_terbit">Tahun Terbit</label>
                                <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control" placeholder="Contoh: 2024" value="<?= $escape($form_data['tahun_terbit']); ?>" min="1000" max="2100">
                            </div>

                            <div class="form-group">
                                <label for="penerbit">Penerbit</label>
                                <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Nama penerbit" value="<?= $escape($form_data['penerbit']); ?>" maxlength="100">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Data</button>
                            <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
<script>
document.getElementById('formBuku').addEventListener('submit', async function(e) {
    e.preventDefault(); // Mencegah reload halaman

    // Mengambil nilai dari input form
    const payload = {
        kode_buku: document.getElementById('kode_buku').value,
        judul: document.getElementById('judul').value,
        penulis: document.getElementById('penulis').value,
        kategori: document.getElementById('kategori').value,
        tahun_terbit: document.getElementById('tahun_terbit').value,
        penerbit: document.getElementById('penerbit').value
    };

    try {
        // Mengirimkan request JSON ke endpoint api_buku.php
        const response = await fetch('api_buku.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.status) {
            alert(result.message);
            window.location.href = 'dashboard.php';
        } else {
            alert('Gagal: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi API!');
    }
});
</script>
</body>
</html>