<?php
// app/config/database.php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_bnk');

function get_db_connection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Set charset ke utf8mb4
    mysqli_set_charset($conn, "utf8mb4");

    return $conn;
}
