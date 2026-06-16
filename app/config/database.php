<?php

// Try to parse database URL if provided (standard on Railway/Heroku)
$db_url_str = getenv('MYSQL_URL') ?: getenv('DATABASE_URL');
$parsed_db = [];
if ($db_url_str) {
    $parsed_url = parse_url($db_url_str);
    if ($parsed_url && isset($parsed_url['scheme']) && ($parsed_url['scheme'] === 'mysql' || $parsed_url['scheme'] === 'mysqli')) {
        $parsed_db['host'] = isset($parsed_url['host']) ? $parsed_url['host'] : 'localhost';
        $parsed_db['port'] = isset($parsed_url['port']) ? $parsed_url['port'] : '3306';
        $parsed_db['user'] = isset($parsed_url['user']) ? $parsed_url['user'] : 'root';
        $parsed_db['pass'] = isset($parsed_url['pass']) ? $parsed_url['pass'] : '';
        $parsed_db['name'] = isset($parsed_url['path']) ? ltrim($parsed_url['path'], '/') : '';
    }
}

// Fallback to standard Railway environment variables or default DB_*
$db_host = isset($parsed_db['host']) ? $parsed_db['host'] : (getenv('MYSQLHOST') ?: (getenv('DB_HOST') ?: 'localhost'));
$db_port = isset($parsed_db['port']) ? $parsed_db['port'] : (getenv('MYSQLPORT') ?: '3306');
$db_user = isset($parsed_db['user']) ? $parsed_db['user'] : (getenv('MYSQLUSER') ?: (getenv('DB_USER') ?: 'root'));
$db_pass = isset($parsed_db['pass']) ? $parsed_db['pass'] : (getenv('MYSQLPASSWORD') ?: (getenv('DB_PASS') ?: ''));
$db_name = isset($parsed_db['name']) ? $parsed_db['name'] : (getenv('MYSQLDATABASE') ?: (getenv('DB_NAME') ?: 'db_bnk'));

// Append port if it's not standard
if ($db_port && $db_port !== '3306') {
    $db_host .= ':' . $db_port;
}

define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);
define('DB_NAME', $db_name);

function get_db_connection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8mb4");

    return $conn;
}
