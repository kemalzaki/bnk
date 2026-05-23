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
$kategori = sanitize_input($_POST['kategori'] ?? '');

if (!validate_required($judul) || !validate_required($kategori)) {
    redirect_with_message('create.php', 'error', 'Judul dan kategori wajib diisi.');
}

// Upload Image
$gambar = '';
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $_FILES['gambar']['type'];
    $file_size = $_FILES['gambar']['size'];
    $file_tmp = $_FILES['gambar']['tmp_name'];
    
    if (!in_array($file_type, $allowed_types)) {
        redirect_with_message('create.php', 'error', 'Format gambar tidak didukung. Gunakan JPG atau PNG.');
    }
    
    if ($file_size > 2 * 1024 * 1024) { // 2MB
        redirect_with_message('create.php', 'error', 'Ukuran gambar maksimal 2MB.');
    }
    
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambar = 'galeri_' . time() . '.' . $ext;
    $upload_path = PUBLIC_PATH . '/../assets/images/galeri/' . $gambar;
    
    if (!move_uploaded_file($file_tmp, $upload_path)) {
        redirect_with_message('create.php', 'error', 'Gagal mengupload gambar.');
    }
} else {
    redirect_with_message('create.php', 'error', 'Gambar wajib diupload.');
}

$conn = get_db_connection();
$success = insert_galeri($conn, $judul, $gambar, $kategori);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Foto berhasil ditambahkan ke galeri.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menyimpan data galeri.');
}
