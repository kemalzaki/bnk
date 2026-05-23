<?php
// app/modules/auth/process_login.php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/auth_query.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/app/modules/auth/login.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('login.php', 'error', 'Token keamanan tidak valid. Silakan coba lagi.');
}

$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    redirect_with_message('login.php', 'error', 'Username dan password wajib diisi.');
}

$conn = get_db_connection();
$user = get_user_by_username($conn, $username);
mysqli_close($conn);

if ($user && password_verify($password, $user['password'])) {
    // Regenerate session ID untuk mencegah session fixation
    session_regenerate_id(true);
    
    // Set data user ke session
    login_user($user);
    
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
} else {
    redirect_with_message('login.php', 'error', 'Username atau password salah.');
}
