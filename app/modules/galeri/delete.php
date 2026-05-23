<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Galeri tidak valid.');
}

$conn = get_db_connection();

// Hapus file gambar dulu
$query = "SELECT gambar FROM tb_galeri WHERE id_galeri = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$galeri = mysqli_fetch_assoc($result);

if ($galeri && !empty($galeri['gambar'])) {
    $file_path = PUBLIC_PATH . '/../assets/images/galeri/' . $galeri['gambar'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$success = delete_galeri($conn, $id);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Foto berhasil dihapus dari galeri.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus foto dari galeri.');
}
