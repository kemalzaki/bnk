<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('index.php', 'error', 'Token keamanan tidak valid.');
}

$id_profil = $_POST['id_profil'] ?? '';
$sambutan = $_POST['sambutan'] ?? '';
$tupoksi = $_POST['tupoksi'] ?? '';
$kondisi_umum = $_POST['kondisi_umum'] ?? '';
$renstra = $_POST['renstra'] ?? '';
$struktur = $_POST['struktur_organisasi'] ?? '';
$visi = $_POST['visi'] ?? '';
$misi = $_POST['misi'] ?? '';
$kontak = $_POST['kontak'] ?? '';


if (!filter_var($id_profil, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Profil tidak valid.');
}

$conn = get_db_connection();
$success = update_profil_bnk($conn, $id, $sambutan, $tupoksi, $kondisi_umum, $renstra, $struktur, $visi, $misi, $kontak);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Profil BNK berhasil diperbarui.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal memperbarui profil BNK.');
}
