<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
$nik_lama = $_POST['nik_lama'] ?? '';

if (!validate_csrf_token($csrf_token)) {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Token keamanan tidak valid.');
}

$nik_baru = sanitize_input($_POST['nik_baru'] ?? '');
$nama = sanitize_input($_POST['nama'] ?? '');
$tanggal_lahir = sanitize_input($_POST['tanggal_lahir'] ?? '');
$jenis_kelamin = sanitize_input($_POST['jenis_kelamin'] ?? '');
$alamat = sanitize_input($_POST['alamat'] ?? '');

// Validasi Required
if (!validate_required($nik_lama) || !validate_required($nik_baru) || !validate_required($nama) || !validate_required($tanggal_lahir) || !validate_required($jenis_kelamin)) {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Semua field dengan tanda bintang (*) wajib diisi.');
}

// Validasi NIK
if (!validate_nik($nik_baru)) {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Format NIK tidak valid. NIK harus 16 digit angka.');
}

// Validasi Tanggal Lahir
if (!validate_date($tanggal_lahir) || !validate_future_date($tanggal_lahir)) {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Tanggal lahir tidak valid. Tidak boleh di masa depan.');
}

// Validasi Enum
if (!validate_enum($jenis_kelamin, ['L', 'P'])) {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Jenis kelamin tidak valid.');
}

$conn = get_db_connection();

// Cek NIK Duplikat jika NIK diubah
if ($nik_lama !== $nik_baru) {
    if (check_nik_exist($conn, $nik_baru)) {
        mysqli_close($conn);
        redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'NIK baru sudah digunakan oleh individu lain.');
    }
}

$success = update_individu($conn, $nik_lama, $nik_baru, $nama, $tanggal_lahir, $alamat, $jenis_kelamin);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data individu berhasil diperbarui.');
} else {
    redirect_with_message("edit.php?nik=" . urlencode($nik_lama), 'error', 'Gagal memperbarui data individu.');
}
