<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('create.php', 'error', 'Token keamanan tidak valid. Silakan coba lagi.');
}

$nomor_kasus = sanitize_input($_POST['nomor_kasus'] ?? '');
$tanggal_kejadian = sanitize_input($_POST['tanggal_kejadian'] ?? '');
$status_hukum = sanitize_input($_POST['status_hukum'] ?? '');

// Validasi Required
if (!validate_required($nomor_kasus) || !validate_required($tanggal_kejadian) || !validate_required($status_hukum)) {
    redirect_with_message('create.php', 'error', 'Semua field wajib diisi.');
}

// Validasi Nomor Kasus
if (!validate_nomor_kasus($nomor_kasus)) {
    redirect_with_message('create.php', 'error', 'Format nomor kasus tidak valid. Gunakan format NK-YYYY-XXXX.');
}

// Validasi Tanggal Kejadian (Maksimal hari ini, Minimal tahun 2020)
if (!validate_date($tanggal_kejadian) || !validate_future_date($tanggal_kejadian) || !validate_min_year($tanggal_kejadian, 2020)) {
    redirect_with_message('create.php', 'error', 'Tanggal kejadian tidak valid. Minimal tahun 2020 dan tidak boleh tanggal di masa depan.');
}

// Validasi Enum Status Hukum
if (!validate_enum($status_hukum, ['penyidikan', 'selesai'])) {
    redirect_with_message('create.php', 'error', 'Status hukum tidak valid.');
}

$conn = get_db_connection();

// Cek Nomor Kasus Duplikat
if (check_nomor_kasus_exist($conn, $nomor_kasus)) {
    mysqli_close($conn);
    redirect_with_message('create.php', 'error', 'Nomor kasus sudah terdaftar di sistem.');
}

// Insert Data
$success = insert_kasus($conn, $nomor_kasus, $tanggal_kejadian, $status_hukum);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data kasus berhasil ditambahkan.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menyimpan data kasus. Silakan coba lagi.');
}
