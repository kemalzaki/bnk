<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('create_pusat.php', 'error', 'Token keamanan tidak valid.');
}

$nama_pusat = sanitize_input($_POST['nama_pusat'] ?? '');
$kapasitas = $_POST['kapasitas'] ?? '';
$alamat = sanitize_input($_POST['alamat'] ?? '');

// Validasi
if (!validate_required($nama_pusat) || !validate_required($kapasitas)) {
    redirect_with_message('create_pusat.php', 'error', 'Nama pusat dan kapasitas wajib diisi.');
}

if (!validate_kapasitas($kapasitas)) {
    redirect_with_message('create_pusat.php', 'error', 'Kapasitas harus berupa angka antara 1 sampai 1000.');
}

$conn = get_db_connection();
$success = insert_pusat_rehabilitasi($conn, $nama_pusat, $alamat, $kapasitas);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data pusat rehabilitasi berhasil ditambahkan.');
} else {
    redirect_with_message('create_pusat.php', 'error', 'Gagal menyimpan data pusat rehabilitasi.');
}
