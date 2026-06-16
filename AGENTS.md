# ACUAN IMPLEMENTASI APLIKASI UNTUK LLM ANTIGRAVITY

## Tujuan Dokumen

Dokumen ini digunakan sebagai acuan utama untuk LLM Antigravity dalam membangun keseluruhan aplikasi berdasarkan SKPL.

Fokus utama implementasi:

* Menggunakan paradigma PROCEDURAL.
* BUKAN Object Oriented Programming (OOP).
* Tidak menggunakan class.
* Tidak menggunakan inheritance.
* Tidak menggunakan dependency injection berbasis object.
* Semua alur dibuat berbasis fungsi/prosedur.
* Struktur aplikasi modular procedural.

---

# 1. KARAKTERISTIK IMPLEMENTASI

## 1.1 Paradigma Wajib

Aplikasi HARUS menggunakan:

* Procedural programming.
* Function-based architecture.
* File modular procedural.
* Shared utility function.
* Stateless flow jika memungkinkan.

Aplikasi DILARANG menggunakan:

* OOP.
* Class.
* Entity model.
* Repository pattern.
* Service class.
* Controller class.
* MVC berbasis object.
* ORM berbasis entity object.

---

# 2. TEKNOLOGI YANG DIREKOMENDASIKAN

## 2.1 Backend

Gunakan:

* PHP Native procedural

Prioritas utama:

* Mudah dibaca.
* Mudah dipelajari.
* Minim abstraksi.
* Mudah maintenance.

## 2.2 Database

Gunakan:

* MySQL.

Akses database:

* Query SQL langsung.
* Gunakan helper procedural.
* Tidak menggunakan ORM.

## 2.3 Frontend

Gunakan:

* HTML.
* CSS.
* JavaScript vanilla.
* Bootstrap diperbolehkan.

Tidak menggunakan:

* React.
* Vue.
* Angular.
* State manager frontend.

---

# 3. TUJUAN SISTEM

Sistem digunakan untuk:

* Pengelolaan data kasus narkotika.
* Pengelolaan individu terkait kasus.
* Pengelolaan rehabilitasi.
* Monitoring status hukum.
* Monitoring status rehabilitasi.
* Penyimpanan data terstruktur.
* Pelaporan data.

---

# 3.1 PEMBAGIAN AREA APLIKASI

Aplikasi WAJIB memiliki dua area utama yang terpisah:

## 3.1.1 Public Page

Halaman publik dapat diakses tanpa login.

Fitur public page:

* Home.
* Profil BNK.
* Berita.
* Galeri.
* Produk hukum.
* Buku tamu.
* Kontak.
* Statistik umum.
* Detail berita.
* Detail produk hukum.
* Pencarian konten.

Folder public page:

```text
/public
    index.php

    /profil
    /berita
    /galeri
    /produk_hukum
    /buku_tamu
    /kontak
```

## 3.1.2 Admin Panel

Admin panel hanya dapat diakses setelah login.

Fitur admin:

* Dashboard admin.
* Manajemen pengguna.
* Manajemen kasus.
* Manajemen individu.
* Manajemen rehabilitasi.
* Manajemen berita.
* Manajemen galeri.
* Manajemen produk hukum.
* Manajemen buku tamu.
* Laporan.
* Statistik.
* Pengaturan sistem.

Hak akses:

* admin
* petugas

---

# 4. MODUL UTAMA SISTEM

## 4.1 Modul Autentikasi

Fitur:

* Login.
* Logout.
* Session management.
* Validasi user.

Tabel:

* tb_user.

Fungsi procedural wajib:

```text
login_user()
logout_user()
check_session()
validate_password()
```

---

## 4.2 Modul Manajemen Kasus

Fitur:

* Tambah kasus.
* Edit kasus.
* Hapus kasus.
* Detail kasus.
* List kasus.
* Pencarian kasus.
* Filter kasus.

Tabel:

* tb_kasus.

Field penting:

* id_kasus
* nomor_kasus
* tanggal_kejadian
* status_hukum

Fungsi procedural:

```text
get_all_kasus()
get_kasus_by_id()
insert_kasus()
update_kasus()
delete_kasus()
search_kasus()
validate_nomor_kasus()
```

---

## 4.3 Modul Individu

Fitur:

* Tambah individu.
* Edit individu.
* Hapus individu.
* Detail individu.
* Relasi individu dengan kasus.

Tabel:

* tb_individu.

Field penting:

* nik
* nama
* tanggal_lahir
* alamat
* jenis_kelamin

Fungsi procedural:

```text
get_all_individu()
get_individu_by_nik()
insert_individu()
update_individu()
delete_individu()
validate_nik()
calculate_umur()
```

---

## 4.4 Modul Rehabilitasi

Fitur:

* Pendaftaran rehabilitasi.
* Monitoring rehabilitasi.
* Status rehabilitasi.
* Riwayat rehabilitasi.

Tabel:

* tb_pusat_rehabilitasi
* tb_pasien_rehabilitasi

Field penting:

* kapasitas
* status_rehabilitasi

Fungsi procedural:

```text
get_all_pusat_rehabilitasi()
insert_pusat_rehabilitasi()
update_pusat_rehabilitasi()
check_kapasitas()
register_pasien_rehabilitasi()
update_status_rehabilitasi()
```

---

## 4.5 Modul Berita

Fitur:

* Tambah berita.
* Edit berita.
* Hapus berita.
* Publish berita.
* Detail berita.
* Upload thumbnail.
* Kategori berita.
* Pencarian berita.

Tabel:

* tb_berita.

Fungsi procedural:

```text
get_all_berita()
get_berita_by_id()
insert_berita()
update_berita()
delete_berita()
publish_berita()
search_berita()
```

---

## 4.6 Modul Galeri

Fitur:

* Upload foto.
* Hapus foto.
* Kategori galeri.
* Album galeri.
* Tampilan galeri publik.

Tabel:

* tb_galeri.

Fungsi procedural:

```text
get_all_galeri()
insert_galeri()
delete_galeri()
upload_gambar()
validate_image()
```

---

## 4.7 Modul Produk Hukum

Fitur:

* Tambah produk hukum.
* Upload dokumen.
* Download dokumen.
* Kategori hukum.
* Pencarian dokumen.

Tabel:

* tb_produk_hukum.

Fungsi procedural:

```text
get_all_produk_hukum()
insert_produk_hukum()
update_produk_hukum()
delete_produk_hukum()
download_produk_hukum()
```

---

## 4.8 Modul Buku Tamu

Fitur:

* Input pesan tamu.
* Validasi captcha.
* Moderasi pesan.
* Status pesan.
* Balasan admin.

Tabel:

* tb_buku_tamu.

Fungsi procedural:

```text
insert_buku_tamu()
get_all_buku_tamu()
update_status_buku_tamu()
reply_buku_tamu()
validate_captcha()
```

---

## 4.9 Modul Profil BNK

Fitur:

* Visi misi.
* Struktur organisasi.
* Sejarah BNK.
* Sambutan kepala BNK.
* Informasi kontak.
* Informasi layanan.

Tabel:

* tb_profil_bnk.

Fungsi procedural:

```text
get_profil_bnk()
update_profil_bnk()
update_visi_misi()
update_struktur_organisasi()
```

---

## 4.10 Modul Laporan

Fitur:

* Laporan kasus.
* Laporan rehabilitasi.
* Statistik.
* Filter tanggal.
* Export data.

Fungsi procedural:

```text
generate_laporan_kasus()
generate_laporan_rehabilitasi()
get_statistik_kasus()
get_statistik_rehabilitasi()
export_pdf()
export_excel()
```

---

# 5. STRUKTUR FOLDER WAJIB

## 5.1 Struktur Procedural

```text
/app
    /config
        database.php
        app.php

    /helpers
        auth_helper.php
        validation_helper.php
        date_helper.php
        response_helper.php

    /public
        index.php

        /profil
            index.php

        /berita
            index.php
            detail.php

        /galeri
            index.php

        /produk_hukum
            index.php
            detail.php
            download.php

        /buku_tamu
            index.php
            process.php

        /kontak
            index.php

    /admin
        dashboard.php

    /modules
        /auth
            login.php
            logout.php
            process_login.php

        /kasus
            index.php
            create.php
            edit.php
            delete.php
            detail.php
            process_create.php
            process_update.php

        /individu
            index.php
            create.php
            edit.php
            delete.php
            detail.php

        /rehabilitasi
            index.php
            create.php
            detail.php
            process.php

        /laporan
            kasus.php
            rehabilitasi.php
            statistik.php
            export.php

    /queries
        kasus_query.php
        individu_query.php
        rehabilitasi_query.php
        auth_query.php

    /templates
        header.php
        footer.php
        sidebar.php
        navbar.php

    /assets
        /css
        /js
        /images

index.php
```

---

# 6. STANDAR KODE

## 6.1 Aturan Umum

* Gunakan snake_case.
* Nama fungsi deskriptif.
* Hindari nested berlebihan.
* Satu fungsi satu tanggung jawab.
* Semua validasi dipisah ke helper.
* Semua query dipisah ke file query.

---

## 6.2 Aturan Query

Semua query HARUS:

* Menggunakan prepared statement.
* Anti SQL Injection.
* Dipisahkan dalam file query.

Contoh:

```php
function get_kasus_by_id($conn, $id_kasus)
{
    $query = "SELECT * FROM tb_kasus WHERE id_kasus = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $id_kasus);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}
```

---

# 7. VALIDASI DATA

## 7.1 Validasi nomor_kasus

Format:

```text
NK-YYYY-XXXX
```

Contoh:

```text
NK-2024-0001
```

Aturan:

* Wajib unik.
* Nomor reset setiap tahun.
* Tidak boleh kosong.

---

## 7.2 Validasi tanggal_kejadian

Format:

```text
YYYY-MM-DD
```

Aturan:

* Tidak boleh masa depan.
* Minimal tahun 2020.

---

## 7.3 Validasi status_hukum

Nilai valid:

```text
penyidikan
selesai
```

---

## 7.4 Validasi NIK

Aturan:

* Tepat 16 digit.
* Hanya angka.
* Tidak boleh duplikat.

Contoh helper:

```php
function validate_nik($nik)
{
    if (!preg_match('/^[0-9]{16}$/', $nik)) {
        return false;
    }

    return true;
}
```

---

## 7.5 Validasi tanggal_lahir

Aturan:

* Minimal 1900-01-01.
* Tidak boleh lebih dari hari ini.

---

## 7.6 Validasi kapasitas

Aturan:

* Integer.
* Range 1 - 1000.

---

## 7.7 Validasi status_rehabilitasi

Nilai valid:

```text
aktif
selesai
drop-out
```

---

# 8. DESAIN DATABASE

## 8.1 Tabel tb_kasus

```sql
CREATE TABLE tb_kasus (
    id_kasus INT AUTO_INCREMENT PRIMARY KEY,
    nomor_kasus VARCHAR(20) UNIQUE NOT NULL,
    tanggal_kejadian DATE NOT NULL,
    status_hukum ENUM('penyidikan','selesai') NOT NULL
);
```

---

## 8.2 Tabel tb_individu

```sql
CREATE TABLE tb_individu (
    nik CHAR(16) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT,
    jenis_kelamin ENUM('L','P')
);
```

---

## 8.3 Tabel tb_pusat_rehabilitasi

```sql
CREATE TABLE tb_pusat_rehabilitasi (
    id_pusat INT AUTO_INCREMENT PRIMARY KEY,
    nama_pusat VARCHAR(100),
    alamat TEXT,
    kapasitas INT NOT NULL
);
```

---

## 8.4 Tabel tb_berita

```sql
CREATE TABLE tb_berita (
    id_berita INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    thumbnail VARCHAR(255),
    isi LONGTEXT NOT NULL,
    status ENUM('draft','publish') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8.5 Tabel tb_galeri

```sql
CREATE TABLE tb_galeri (
    id_galeri INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    gambar VARCHAR(255) NOT NULL,
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8.6 Tabel tb_produk_hukum

```sql
CREATE TABLE tb_produk_hukum (
    id_produk_hukum INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    file_dokumen VARCHAR(255) NOT NULL,
    kategori VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8.7 Tabel tb_buku_tamu

```sql
CREATE TABLE tb_buku_tamu (
    id_buku_tamu INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    pesan TEXT NOT NULL,
    status ENUM('pending','publish') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 8.8 Tabel tb_profil_bnk

```sql
CREATE TABLE tb_profil_bnk (
    id_profil INT AUTO_INCREMENT PRIMARY KEY,
    visi TEXT,
    misi TEXT,
    sejarah LONGTEXT,
    struktur_organisasi TEXT,
    kontak TEXT
);
```

---

## 8.9 Tabel tb_pasien_rehabilitasi

```sql
CREATE TABLE tb_pasien_rehabilitasi (
    id_rehabilitasi INT AUTO_INCREMENT PRIMARY KEY,
    nik CHAR(16),
    id_pusat INT,
    status_rehabilitasi ENUM('aktif','selesai','drop-out'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

# 9. ALUR APLIKASI

## 9.1 Login

```text
User buka login
→ input username/password
→ validasi
→ cek database
→ buat session
→ redirect dashboard
```

---

## 9.2 Tambah Kasus

```text
User buka form kasus
→ input data
→ validasi form
→ validasi nomor kasus
→ insert database
→ tampilkan notifikasi
```

---

## 9.3 Tambah Individu

```text
User buka form individu
→ input data
→ validasi NIK
→ validasi tanggal lahir
→ insert database
→ tampilkan notifikasi
```

---

## 9.4 Registrasi Rehabilitasi

```text
Pilih individu
→ pilih pusat rehabilitasi
→ cek kapasitas
→ simpan data rehabilitasi
→ update statistik
```

---

# 10. STANDAR UI

## 10.1 Dashboard

Dashboard wajib memiliki:

* Statistik kasus.
* Statistik rehabilitasi.
* Grafik sederhana.
* Data terbaru.
* Navigasi sidebar.

---

## 10.2 Form

Semua form wajib:

* Validasi frontend.
* Validasi backend.
* Menampilkan error.
* Menggunakan CSRF token.

---

## 10.3 Tabel Data

Fitur:

* Pagination.
* Search.
* Filter.
* Sorting.

---

# 11. KEAMANAN

## 11.1 Wajib

Implementasi:

* Session validation.
* CSRF protection.
* SQL Injection prevention.
* XSS sanitization.
* Input escaping.
* Password hashing.

---

## 11.2 Password

Gunakan:

```php
password_hash()
password_verify()
```

---

# 12. STANDAR RESPONSE

## 12.1 Success

```json
{
  "success": true,
  "message": "Data berhasil disimpan"
}
```

---

## 12.2 Error

```json
{
  "success": false,
  "message": "Validasi gagal"
}
```

---

# 13. STANDAR HELPER

## 13.1 validation_helper.php

Berisi:

```text
validate_nik()
validate_nomor_kasus()
validate_date()
validate_required()
validate_enum()
```

---

## 13.2 auth_helper.php

Berisi:

```text
check_login()
login_user()
logout_user()
check_role()
```

---

## 13.3 date_helper.php

Berisi:

```text
format_date()
calculate_age()
validate_future_date()
```

---

# 14. STANDAR CODING UNTUK LLM ANTIGRAVITY

## 14.1 Wajib Diikuti

LLM wajib:

* Membuat kode procedural.
* Memecah fungsi berdasarkan modul.
* Tidak membuat class.
* Tidak membuat object.
* Tidak menggunakan framework OOP.
* Membuat query terpisah.
* Membuat helper terpisah.
* Membuat template terpisah.
* Menggunakan include/require.

---

## 14.2 Larangan

LLM DILARANG:

* Menggunakan arsitektur MVC berbasis class.
* Menggunakan repository pattern.
* Menggunakan service pattern.
* Menggunakan entity model.
* Menggunakan ORM.
* Menggunakan singleton.
* Menggunakan dependency injection.
* Menggunakan interface.
* Menggunakan abstract class.

---

# 15. PRIORITAS IMPLEMENTASI

Urutan pengerjaan:

1. Database.
2. Konfigurasi koneksi.
3. Helper.
4. Authentication.
5. Modul kasus.
6. Modul individu.
7. Modul rehabilitasi.
8. Modul laporan.
9. Dashboard.
10. Optimasi.
11. Security hardening.

---

# 16. OUTPUT YANG HARUS DIHASILKAN OLEH LLM

LLM wajib menghasilkan:

* Struktur folder lengkap.
* SQL schema.
* File procedural.
* Query procedural.
* Helper procedural.
* Template HTML.
* CSS.
* JavaScript.
* Validasi.
* Authentication.
* Dashboard.
* CRUD lengkap.
* Laporan.
* Export data.

---

# 17. KOMPONEN WAJIB BERDASARKAN SKPL

LLM wajib memastikan seluruh kebutuhan berikut tersedia:

* Public page terpisah.
* Admin panel terpisah.
* Modul berita.
* Modul galeri.
* Modul buku tamu.
* Modul produk hukum.
* Modul profil BNK.
* Modul autentikasi.
* Modul kasus.
* Modul individu.
* Modul rehabilitasi.
* Modul laporan.
* Dashboard statistik.
* Hak akses admin dan petugas.
* Upload file dan gambar.
* Export laporan.
* Validasi seluruh field sesuai SKPL.
* Struktur procedural penuh.

Semua requirement SKPL HARUS dianggap mandatory.

---

# 18. KETENTUAN FINAL

Semua implementasi HARUS:

* Konsisten procedural.
* Mudah dibaca.
* Mudah dikembangkan.
* Minim abstraksi.
* Fokus maintainability.
* Fokus keamanan.
* Fokus validasi.
* Fokus modular procedural.

LLM Antigravity harus menganggap dokumen ini sebagai aturan utama implementasi sistem.
