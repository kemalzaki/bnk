<?php
// app/templates/navbar.php
$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'Admin';
$role = $_SESSION['role'] ?? 'petugas';
?>
<!-- Page Content  -->
<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
                <i class="fas fa-align-left text-white"></i>
            </button>
            
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 fw-bold"><?php echo htmlspecialchars($nama_lengkap); ?> (<?php echo ucfirst(htmlspecialchars($role)); ?>)</span>
                <a href="<?php echo BASE_URL; ?>/app/modules/auth/logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">
