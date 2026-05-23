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
    redirect_with_message('create.php', 'error', 'Token keamanan tidak valid.');
}

$judul = sanitize_input($_POST['judul'] ?? '');
$isi = $_POST['isi'] ?? ''; // Jangan sanitize berlebihan karena bisa mengandung tag HTML (jika nanti pakai rich text editor)
$status = sanitize_input($_POST['status'] ?? '');

// Generate Slug
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
$slug = $slug . '-' . time(); // append timestamp to ensure uniqueness

if (!validate_required($judul) || !validate_required($isi)) {
    redirect_with_message('create.php', 'error', 'Judul dan isi wajib diisi.');
}

if (!validate_enum($status, ['draft', 'publish'])) {
    redirect_with_message('create.php', 'error', 'Status tidak valid.');
}

// Upload Image
$thumbnail = '';
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $_FILES['thumbnail']['type'];
    $file_size = $_FILES['thumbnail']['size'];
    $file_tmp = $_FILES['thumbnail']['tmp_name'];
    
    if (!in_array($file_type, $allowed_types)) {
        redirect_with_message('create.php', 'error', 'Format gambar tidak didukung. Gunakan JPG atau PNG.');
    }
    
    if ($file_size > 2 * 1024 * 1024) { // 2MB
        redirect_with_message('create.php', 'error', 'Ukuran gambar maksimal 2MB.');
    }
    
    $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    $thumbnail = 'berita_' . time() . '.' . $ext;
    $upload_path = PUBLIC_PATH . '/../assets/images/berita/' . $thumbnail;
    
    if (!move_uploaded_file($file_tmp, $upload_path)) {
        redirect_with_message('create.php', 'error', 'Gagal mengupload gambar.');
    }
} else {
    redirect_with_message('create.php', 'error', 'Thumbnail wajib diupload.');
}

$conn = get_db_connection();
$success = insert_berita($conn, $judul, $slug, $thumbnail, $isi, $status);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Berita berhasil ditambahkan.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menyimpan berita.');
}
