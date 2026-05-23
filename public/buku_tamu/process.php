<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// 1. Validasi CSRF
$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('index.php', 'error', 'Token keamanan tidak valid.');
}

// 2. Validasi CAPTCHA
$captcha_input = isset($_POST['captcha']) ? (int)$_POST['captcha'] : null;
$captcha_result = $_SESSION['captcha_result'] ?? null;

if ($captcha_input === null || $captcha_input !== $captcha_result) {
    redirect_with_message('index.php', 'error', 'Jawaban CAPTCHA salah. Silakan coba lagi.');
}

// Hapus captcha dari session agar tidak dapat digunakan kembali (anti-replay)
unset($_SESSION['captcha_result']);
unset($_SESSION['captcha_num1']);
unset($_SESSION['captcha_num2']);

// 3. Ambil dan bersihkan input
$nama = sanitize_input($_POST['nama'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$pesan = sanitize_input($_POST['pesan'] ?? '');

// 4. Validasi input wajib
if (!validate_required($nama) || !validate_required($email) || !validate_required($pesan)) {
    redirect_with_message('index.php', 'error', 'Semua bidang form wajib diisi.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('index.php', 'error', 'Format email tidak valid.');
}

// 5. Simpan ke database
$conn = get_db_connection();
$success = insert_buku_tamu($conn, $nama, $email, $pesan);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Pesan Anda berhasil dikirim! Pesan Anda akan muncul setelah divalidasi oleh administrator.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal mengirimkan pesan. Silakan coba beberapa saat lagi.');
}
