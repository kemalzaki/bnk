<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('create.php', 'error', 'Token keamanan tidak valid. Silakan coba lagi.');
}

$nik = sanitize_input($_POST['nik'] ?? '');
$nama = sanitize_input($_POST['nama'] ?? '');
$tanggal_lahir = sanitize_input($_POST['tanggal_lahir'] ?? '');
$jenis_kelamin = sanitize_input($_POST['jenis_kelamin'] ?? '');
$alamat = sanitize_input($_POST['alamat'] ?? '');

// Validasi Required
if (!validate_required($nik) || !validate_required($nama) || !validate_required($tanggal_lahir) || !validate_required($jenis_kelamin)) {
    redirect_with_message('create.php', 'error', 'Semua field dengan tanda bintang (*) wajib diisi.');
}

// Validasi NIK
if (!validate_nik($nik)) {
    redirect_with_message('create.php', 'error', 'Format NIK tidak valid. NIK harus 16 digit angka.');
}

// Validasi Tanggal Lahir (Maksimal hari ini)
if (!validate_date($tanggal_lahir) || !validate_future_date($tanggal_lahir)) {
    redirect_with_message('create.php', 'error', 'Tanggal lahir tidak valid. Tidak boleh tanggal di masa depan.');
}

// Validasi Jenis Kelamin
if (!validate_enum($jenis_kelamin, ['L', 'P'])) {
    redirect_with_message('create.php', 'error', 'Jenis kelamin tidak valid.');
}

$conn = get_db_connection();

// Cek NIK Duplikat
if (check_nik_exist($conn, $nik)) {
    mysqli_close($conn);
    redirect_with_message('create.php', 'error', 'NIK sudah terdaftar di sistem.');
}

// Insert Data
$success = insert_individu($conn, $nik, $nama, $tanggal_lahir, $alamat, $jenis_kelamin);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data individu berhasil ditambahkan.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menyimpan data individu. Silakan coba lagi.');
}
