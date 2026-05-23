<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit;
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Sistem Informasi BNK</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: #fff;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-logo h3 {
            font-weight: 700;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-logo">
        <h3>Admin Panel BNK</h3>
        <p class="text-muted">Silakan login untuk melanjutkan</p>
    </div>

    <?php display_flash_message(); ?>

    <form action="process_login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
    
    <div class="text-center mt-3">
        <a href="<?php echo BASE_URL; ?>/public/index.php" class="text-decoration-none">Kembali ke Halaman Utama</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
