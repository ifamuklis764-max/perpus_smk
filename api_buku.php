<?php
// Set header agar respon selalu berformat JSON & mendukung CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ambil semua data buku
        $query = "SELECT * FROM books ORDER BY id DESC";
        $result = mysqli_query($koneksi, $query);
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        echo json_encode([
            "status" => true,
            "message" => "Data buku berhasil diambil",
            "total" => count($data),
            "data" => $data
        ], JSON_PRETTY_PRINT);
        break;

    case 'POST':
        // Membaca data JSON yang dikirimkan
        $raw_input = file_get_contents("php://input");
        $input = json_decode($raw_input, true);

        // Fallback jika dikirim lewat Form Data
        if (!$input) {
            $input = $_POST;
        }

        $kode_buku   = isset($input['kode_buku']) ? mysqli_real_escape_string($koneksi, trim($input['kode_buku'])) : '';
        $judul       = isset($input['judul']) ? mysqli_real_escape_string($koneksi, trim($input['judul'])) : '';
        $penulis     = isset($input['penulis']) ? mysqli_real_escape_string($koneksi, trim($input['penulis'])) : '';
        $kategori    = isset($input['kategori']) ? mysqli_real_escape_string($koneksi, trim($input['kategori'])) : '';
        $tahun_terbit= isset($input['tahun_terbit']) ? mysqli_real_escape_string($koneksi, trim($input['tahun_terbit'])) : '';
        $penerbit    = isset($input['penerbit']) ? mysqli_real_escape_string($koneksi, trim($input['penerbit'])) : '';

        // Validasi
        if (empty($kode_buku) || empty($judul)) {
            http_response_code(400);
            echo json_encode([
                "status" => false,
                "message" => "Kode buku dan Judul wajib diisi!"
            ]);
            exit;
        }

        $query = "INSERT INTO books (kode_buku, judul, penulis, kategori, tahun_terbit, penerbit) 
                  VALUES ('$kode_buku', '$judul', '$penulis', '$kategori', '$tahun_terbit', '$penerbit')";

        if (mysqli_query($koneksi, $query)) {
            http_response_code(201);
            echo json_encode([
                "status" => true,
                "message" => "Buku berhasil ditambahkan!",
                "data" => [
                    "id" => mysqli_insert_id($koneksi),
                    "kode_buku" => $kode_buku,
                    "judul" => $judul
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => false,
                "message" => "Gagal menyimpan data: " . mysqli_error($koneksi)
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode([
            "status" => false,
            "message" => "Method tidak diizinkan!"
        ]);
        break;
}
?>