<?php
// app/queries/konten_query.php

// === BERITA ===
function get_all_berita($conn) {
    $query = "SELECT * FROM tb_berita ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_berita_by_id($conn, $id) {
    $query = "SELECT * FROM tb_berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_berita($conn, $judul, $slug, $thumbnail, $isi, $status) {
    $query = "INSERT INTO tb_berita (judul, slug, thumbnail, isi, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $judul, $slug, $thumbnail, $isi, $status);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_berita($conn, $id, $judul, $slug, $thumbnail, $isi, $status) {
    $query = "UPDATE tb_berita SET judul = ?, slug = ?, thumbnail = ?, isi = ?, status = ? WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $judul, $slug, $thumbnail, $isi, $status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_berita($conn, $id) {
    $query = "DELETE FROM tb_berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === GALERI ===
function get_all_galeri($conn) {
    $query = "SELECT * FROM tb_galeri ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function insert_galeri($conn, $judul, $gambar, $kategori) {
    $query = "INSERT INTO tb_galeri (judul, gambar, kategori) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $judul, $gambar, $kategori);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_galeri($conn, $id) {
    $query = "DELETE FROM tb_galeri WHERE id_galeri = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === PRODUK HUKUM ===
function get_all_produk_hukum($conn) {
    $query = "SELECT * FROM tb_produk_hukum ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_produk_hukum_by_id($conn, $id) {
    $query = "SELECT * FROM tb_produk_hukum WHERE id_produk_hukum = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_produk_hukum($conn, $judul, $file_dokumen, $kategori) {
    $query = "INSERT INTO tb_produk_hukum (judul, file_dokumen, kategori) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $judul, $file_dokumen, $kategori);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_produk_hukum($conn, $id) {
    $query = "DELETE FROM tb_produk_hukum WHERE id_produk_hukum = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === BUKU TAMU ===
function get_all_buku_tamu($conn) {
    $query = "SELECT * FROM tb_buku_tamu ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function update_status_buku_tamu($conn, $id, $status) {
    $query = "UPDATE tb_buku_tamu SET status = ? WHERE id_buku_tamu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_buku_tamu($conn, $id) {
    $query = "DELETE FROM tb_buku_tamu WHERE id_buku_tamu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === PROFIL BNK ===
function get_profil_bnk($conn) {
    // Mengambil data versi terbaru berdasarkan id_profil terbesar (Memenuhi syarat riwayat versi SKPL)
    $query = "SELECT * FROM tb_profil_bnk ORDER BY id_profil DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    // Jika tabel kosong, insert data awal/kosong sesuai dengan 7 komponen profil di SKPL
    if (mysqli_num_rows($result) == 0) {
        $insert_query = "INSERT INTO tb_profil_bnk (sambutan, tupoksi, kondisi_umum, renstra, struktur_organisasi, visi, misi) 
                         VALUES ('-', '-', '-', '-', '-', '-', '-')";
        mysqli_query($conn, $insert_query);
        
        // Panggil ulang fungsi untuk mengambil data yang baru saja di-insert
        return get_profil_bnk($conn);
    }
    
    return mysqli_fetch_assoc($result);
}

function update_profil_bnk($conn, $id, $sambutan, $tupoksi, $kondisi_umum, $renstra, $struktur, $visi, $misi) {
    // Menggunakan INSERT agar data lama tidak terhapus dan tersimpan sebagai riwayat versi
    $query = "INSERT INTO tb_profil_bnk (sambutan, tupoksi, kondisi_umum, renstra, struktur_organisasi, visi, misi) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    // Karena INSERT baru, kita tidak membutuhkan parameter ID di akhir, jadi tipe datanya "sssssss" (7 string)
    mysqli_stmt_bind_param($stmt, "sssssss", $sambutan, $tupoksi, $kondisi_umum, $renstra, $struktur, $visi, $misi);
    
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}
