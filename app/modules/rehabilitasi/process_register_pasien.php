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
    redirect_with_message('register_pasien.php', 'error', 'Token keamanan tidak valid.');
}

$nik = sanitize_input($_POST['nik'] ?? '');
$id_pusat = $_POST['id_pusat'] ?? '';
$status_rehabilitasi = sanitize_input($_POST['status_rehabilitasi'] ?? '');

// Validasi
if (!validate_required($nik) || !validate_required($id_pusat) || !validate_required($status_rehabilitasi)) {
    redirect_with_message('register_pasien.php', 'error', 'Semua field wajib diisi.');
}

if (!validate_enum($status_rehabilitasi, ['aktif', 'selesai', 'drop-out'])) {
    redirect_with_message('register_pasien.php', 'error', 'Status rehabilitasi tidak valid.');
}

$conn = get_db_connection();

// 1. Cek apakah pasien sudah aktif di pusat rehab manapun
if ($status_rehabilitasi === 'aktif' && check_pasien_aktif($conn, $nik)) {
    mysqli_close($conn);
    redirect_with_message('register_pasien.php', 'error', 'Individu ini sudah terdaftar sebagai pasien aktif di suatu pusat rehabilitasi. Selesaikan atau drop-out statusnya terlebih dahulu.');
}

// 2. Cek kapasitas pusat rehab jika mendaftar sebagai 'aktif'
if ($status_rehabilitasi === 'aktif' && !check_kapasitas($conn, $id_pusat)) {
    mysqli_close($conn);
    redirect_with_message('register_pasien.php', 'error', 'Kapasitas pusat rehabilitasi sudah penuh.');
}

// 3. Proses Insert
$success = register_pasien_rehabilitasi($conn, $nik, $id_pusat, $status_rehabilitasi);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Pasien berhasil didaftarkan ke pusat rehabilitasi.');
} else {
    redirect_with_message('register_pasien.php', 'error', 'Gagal mendaftarkan pasien.');
}
