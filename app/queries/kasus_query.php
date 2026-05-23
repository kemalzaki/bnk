<?php
// app/queries/kasus_query.php

function get_all_kasus($conn) {
    $query = "SELECT * FROM tb_kasus ORDER BY id_kasus DESC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function get_kasus_by_id($conn, $id_kasus) {
    $query = "SELECT * FROM tb_kasus WHERE id_kasus = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_kasus);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_kasus($conn, $nomor_kasus, $tanggal_kejadian, $status_hukum) {
    $query = "INSERT INTO tb_kasus (nomor_kasus, tanggal_kejadian, status_hukum) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $nomor_kasus, $tanggal_kejadian, $status_hukum);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_kasus($conn, $id_kasus, $nomor_kasus, $tanggal_kejadian, $status_hukum) {
    $query = "UPDATE tb_kasus SET nomor_kasus = ?, tanggal_kejadian = ?, status_hukum = ? WHERE id_kasus = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $nomor_kasus, $tanggal_kejadian, $status_hukum, $id_kasus);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_kasus($conn, $id_kasus) {
    $query = "DELETE FROM tb_kasus WHERE id_kasus = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_kasus);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function check_nomor_kasus_exist($conn, $nomor_kasus, $exclude_id = null) {
    if ($exclude_id) {
        $query = "SELECT id_kasus FROM tb_kasus WHERE nomor_kasus = ? AND id_kasus != ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $nomor_kasus, $exclude_id);
    } else {
        $query = "SELECT id_kasus FROM tb_kasus WHERE nomor_kasus = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nomor_kasus);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    
    return $count > 0;
}
