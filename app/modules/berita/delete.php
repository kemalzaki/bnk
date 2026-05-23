<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Berita tidak valid.');
}

$conn = get_db_connection();

// Hapus file gambar dulu
$berita = get_berita_by_id($conn, $id);
if ($berita && !empty($berita['thumbnail'])) {
    $file_path = PUBLIC_PATH . '/../assets/images/berita/' . $berita['thumbnail'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$success = delete_berita($conn, $id);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Berita berhasil dihapus.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus berita.');
}
