<?php
// app/config/app.php

// Start session secara otomatis untuk seluruh aplikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/SKPL%20WEB');

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
