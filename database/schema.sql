CREATE DATABASE IF NOT EXISTS db_bnk;
USE db_bnk;

CREATE TABLE IF NOT EXISTS tb_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: username: admin, password: password
-- (hashed dengan password_hash('password', PASSWORD_DEFAULT))
INSERT INTO tb_user (username, password, nama_lengkap, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator Utama', 'admin')
ON DUPLICATE KEY UPDATE username=username;

CREATE TABLE IF NOT EXISTS tb_kasus (
    id_kasus INT AUTO_INCREMENT PRIMARY KEY,
    nomor_kasus VARCHAR(20) UNIQUE NOT NULL,
    tanggal_kejadian DATE NOT NULL,
    status_hukum ENUM('penyidikan','selesai') NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_individu (
    nik CHAR(16) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT,
    jenis_kelamin ENUM('L','P')
);

CREATE TABLE IF NOT EXISTS tb_pusat_rehabilitasi (
    id_pusat INT AUTO_INCREMENT PRIMARY KEY,
    nama_pusat VARCHAR(100),
    alamat TEXT,
    kapasitas INT NOT NULL
);

CREATE TABLE IF NOT EXISTS tb_pasien_rehabilitasi (
    id_rehabilitasi INT AUTO_INCREMENT PRIMARY KEY,
    nik CHAR(16),
    id_pusat INT,
    status_rehabilitasi ENUM('aktif','selesai','drop-out'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nik) REFERENCES tb_individu(nik) ON DELETE CASCADE,
    FOREIGN KEY (id_pusat) REFERENCES tb_pusat_rehabilitasi(id_pusat) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS tb_berita (
    id_berita INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    thumbnail VARCHAR(255),
    isi LONGTEXT NOT NULL,
    status ENUM('draft','publish') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_galeri (
    id_galeri INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    gambar VARCHAR(255) NOT NULL,
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_produk_hukum (
    id_produk_hukum INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    file_dokumen VARCHAR(255) NOT NULL,
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_buku_tamu (
    id_buku_tamu INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    pesan TEXT NOT NULL,
    status ENUM('pending','publish') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tb_profil_bnk (
    id_profil INT AUTO_INCREMENT PRIMARY KEY,
    sambutan TEXT NULL,
    tupoksi TEXT NULL,
    kondisi_umum TEXT NULL,
    renstra TEXT NULL,
    struktur_organisasi TEXT,
    visi TEXT,
    misi TEXT
);
