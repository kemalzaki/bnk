# Sistem Informasi BNK (Badan Narkotika Kabupaten)

Aplikasi ini adalah sistem informasi pengelolaan data kasus narkotika, individu terkait kasus, rehabilitasi, dan portal informasi publik untuk Badan Narkotika Kabupaten (BNK). Aplikasi ini dibangun sepenuhnya menggunakan paradigma **Procedural Programming** dengan **PHP Native** dan **MySQL** sesuai dengan acuan dokumen [AGENTS.md](file:///d:/Projects/SKPL%20WEB/AGENTS.md) dan SKPL yang ditentukan.

---

## Kebutuhan Sistem (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda telah memenuhi kebutuhan perangkat lunak berikut:
- **PHP** versi 8.0 atau yang lebih baru.
- **MySQL** / **MariaDB** Database Server.
- Web Browser modern (Chrome, Edge, Firefox, dll.).
- (Opsional) Paket Web Server lokal seperti **XAMPP**, **Laragon**, atau **MAMP**.

---

## Langkah-Langkah Menjalankan Aplikasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal Anda:

### Langkah 1: Siapkan Folder Proyek
Pastikan file proyek berada di direktori kerja Anda (misalnya `d:/Projects/SKPL WEB`). 

### Langkah 2: Impor Database MySQL
1. Aktifkan service **MySQL** Anda (misalnya lewat control panel XAMPP atau Laragon).
2. Buat database baru bernama `db_bnk` (jika belum ada).
3. Impor skema database dari file [database/schema.sql](file:///d:/Projects/SKPL%20WEB/database/schema.sql) ke dalam database `db_bnk`. Anda dapat menggunakan baris perintah (CLI) atau GUI client seperti phpMyAdmin, DBeaver, atau HeidiSQL.
   * **Menggunakan CLI:**
     ```bash
     mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_bnk;"
     mysql -u root -p db_bnk < database/schema.sql
     ```

### Langkah 3: Konfigurasi Koneksi Database
Buka file [app/config/database.php](file:///d:/Projects/SKPL%20WEB/app/config/database.php) dan sesuaikan konstanta konfigurasi database sesuai dengan pengaturan server MySQL lokal Anda:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Username database Anda
define('DB_PASS', '');          // Password database Anda
define('DB_NAME', 'db_bnk');     // Nama database
```

### Langkah 4: Jalankan Server Lokal
Ada dua cara untuk menjalankan server web lokal:

#### Opsi A: Menggunakan PHP Built-in Server (Sangat Direkomendasikan & Praktis)
1. Buka terminal atau Command Prompt (CMD).
2. Arahkan ke direktori root proyek (`d:\Projects\SKPL WEB`).
3. Jalankan perintah berikut:
   ```bash
   php -S localhost:8000
   ```
4. Biarkan terminal tersebut tetap terbuka selama Anda menggunakan aplikasi.

#### Opsi B: Menggunakan Web Server Apache (XAMPP / Laragon)
1. Pindahkan atau salin folder proyek `SKPL WEB` ke direktori root server web Anda:
   - **XAMPP:** `C:\xampp\htdocs\`
   - **Laragon:** `C:\laragon\www\`
2. Pastikan service Apache dan MySQL sudah berjalan.

### Langkah 5: Akses Aplikasi di Browser
1. Jika menggunakan **Opsi A (PHP Built-in Server)**:
   - Buka browser Anda dan akses URL: **`http://localhost:8000`**
2. Jika menggunakan **Opsi B (Apache XAMPP / Laragon)**:
   - Akses URL: **`http://localhost/SKPL%20WEB`** (sesuaikan nama foldernya).
3. Anda akan otomatis diarahkan oleh file root [index.php](file:///d:/Projects/SKPL%20WEB/index.php) ke portal publik utama di subfolder `/public/`.

---

## Akun Akses Default (Login Admin)

Untuk masuk ke Admin Panel dan mengelola data kasus, individu, rehabilitasi, berita, galeri, produk hukum, buku tamu, serta mencetak laporan:

1. Klik tombol **Login Petugas/Admin** di sudut kanan atas navbar portal publik, atau akses langsung tautan login berikut:
   - **`http://localhost:8000/app/modules/auth/login.php`**
2. Gunakan kredensial berikut untuk masuk:
   - **Username:** `admin`
   - **Password:** `password`

---

## Struktur Folder & Alur Modular

Aplikasi ini dibagi menjadi 2 area utama yang terpisah secara visual dan fungsional:
1. **Public Page** (Akses tanpa login):
   - Home, Profil BNK, Berita (list & detail), Galeri (lightbox foto), Produk Hukum (pencarian & download dokumen), Buku Tamu (moderasi dengan CAPTCHA matematika), dan Kontak.
   - Folder utama: [public/](file:///d:/Projects/SKPL%20WEB/public/)
2. **Admin Panel** (Membutuhkan autentikasi):
   - Dashboard Statistik, Manajemen Pengguna, Manajemen Kasus, Manajemen Individu, Manajemen Pasien & Pusat Rehabilitasi, Laporan & Statistik (Export CSV & Cetak PDF).
   - Folder utama: [admin/](file:///d:/Projects/SKPL%20WEB/admin/) dan [app/modules/](file:///d:/Projects/SKPL%20WEB/app/modules/)

Seluruh fungsi query SQL dipisahkan dalam folder `/queries/` menggunakan **Prepared Statements** untuk keamanan maksimal melawan SQL Injection. Seluruh validasi data dan enkripsi password dikelola secara prosedural dalam `/app/helpers/`.
