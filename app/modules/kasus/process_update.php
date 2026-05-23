<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
$id_kasus = $_POST['id_kasus'] ?? '';

if (!validate_csrf_token($csrf_token)) {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Token keamanan tidak valid.');
}

if (!filter_var($id_kasus, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Kasus tidak valid.');
}

$nomor_kasus = sanitize_input($_POST['nomor_kasus'] ?? '');
$tanggal_kejadian = sanitize_input($_POST['tanggal_kejadian'] ?? '');
$status_hukum = sanitize_input($_POST['status_hukum'] ?? '');

// Validasi
if (!validate_required($nomor_kasus) || !validate_required($tanggal_kejadian) || !validate_required($status_hukum)) {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Semua field wajib diisi.');
}

if (!validate_nomor_kasus($nomor_kasus)) {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Format nomor kasus tidak valid. Gunakan format NK-YYYY-XXXX.');
}

if (!validate_date($tanggal_kejadian) || !validate_future_date($tanggal_kejadian) || !validate_min_year($tanggal_kejadian, 2020)) {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Tanggal kejadian tidak valid. Minimal tahun 2020 dan tidak boleh di masa depan.');
}

if (!validate_enum($status_hukum, ['penyidikan', 'selesai'])) {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Status hukum tidak valid.');
}

$conn = get_db_connection();

// Cek Nomor Kasus Duplikat (kecuali miliknya sendiri)
if (check_nomor_kasus_exist($conn, $nomor_kasus, $id_kasus)) {
    mysqli_close($conn);
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Nomor kasus sudah digunakan oleh kasus lain.');
}

$success = update_kasus($conn, $id_kasus, $nomor_kasus, $tanggal_kejadian, $status_hukum);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data kasus berhasil diperbarui.');
} else {
    redirect_with_message("edit.php?id=$id_kasus", 'error', 'Gagal memperbarui data kasus.');
}
