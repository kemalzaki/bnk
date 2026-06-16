<?php
// app/helpers/auth_helper.php

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "/app/modules/auth/login.php");
        exit;
    }
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: " . BASE_URL . "/admin/dashboard.php?error=unauthorized");
        exit;
    }
}

function login_user($user_data) {
    $_SESSION['user_id'] = $user_data['id_user'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];
    $_SESSION['role'] = $user_data['role'];
}

function logout_user() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "/app/modules/auth/login.php");
    exit;
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}
