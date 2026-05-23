<?php
// app/queries/user_query.php

function get_all_users($conn) {
    $query = "SELECT id_user, username, nama_lengkap, role, created_at FROM tb_user ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_user_by_id($conn, $id) {
    $query = "SELECT id_user, username, nama_lengkap, role FROM tb_user WHERE id_user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_user($conn, $username, $password, $nama_lengkap, $role) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO tb_user (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $username, $hashed, $nama_lengkap, $role);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_user($conn, $id, $username, $nama_lengkap, $role) {
    $query = "UPDATE tb_user SET username = ?, nama_lengkap = ?, role = ? WHERE id_user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $nama_lengkap, $role, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_user_password($conn, $id, $new_password) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE tb_user SET password = ? WHERE id_user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $hashed, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_user($conn, $id) {
    $query = "DELETE FROM tb_user WHERE id_user = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function check_username_exist($conn, $username, $exclude_id = null) {
    if ($exclude_id) {
        $query = "SELECT id_user FROM tb_user WHERE username = ? AND id_user != ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $username, $exclude_id);
    } else {
        $query = "SELECT id_user FROM tb_user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
