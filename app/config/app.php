<?php
// app/config/app.php

// Start session secara otomatis untuk seluruh aplikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper to dynamically calculate base URL
function get_base_url() {
    $env_url = getenv('BASE_URL');
    // Use environment variable only if it's a valid HTTP/HTTPS URL
    if ($env_url && (strpos($env_url, 'http://') === 0 || strpos($env_url, 'https://') === 0)) {
        return rtrim($env_url, '/');
    }
    
    // Fallback: Dynamically detect base URL
    if (isset($_SERVER['HTTP_HOST'])) {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
                  (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        $root_path = str_replace('\\', '/', dirname(dirname(__DIR__)));
        $script_filename = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
        $relative_path = str_replace($root_path, '', $script_filename);
        $script_name = $_SERVER['SCRIPT_NAME'];
        
        $base_path = '';
        if (substr($script_name, -strlen($relative_path)) === $relative_path) {
            $base_path = substr($script_name, 0, -strlen($relative_path));
        }
        
        return $scheme . '://' . $host . $base_path;
    }
    
    return 'http://localhost/SKPL%20WEB';
}

define('BASE_URL', get_base_url());

// Konfigurasi path
define('APP_PATH', dirname(__DIR__));
define('ROOT_PATH', dirname(APP_PATH));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ADMIN_PATH', ROOT_PATH . '/admin');

// Atur timezone
date_default_timezone_set('Asia/Jakarta');

// Autoload helper dan file yang diperlukan
require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/helpers/validation_helper.php';
require_once APP_PATH . '/helpers/auth_helper.php';
require_once APP_PATH . '/helpers/date_helper.php';
require_once APP_PATH . '/helpers/response_helper.php';
