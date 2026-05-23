<?php
// app/queries/auth_query.php

function get_user_by_username($conn, $username) {
    $query = "SELECT id_user, username, password, nama_lengkap, role FROM tb_user WHERE username = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    
    mysqli_stmt_close($stmt);
    
    return $user;
}
