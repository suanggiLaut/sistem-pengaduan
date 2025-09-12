Sistem Pengaduan Masyarakat 🏢
https://img.shields.io/badge/PHP-7.4%252B-777BB4?logo=php
https://img.shields.io/badge/MySQL-5.7%252B-4479A1?logo=mysql
https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap
https://img.shields.io/badge/License-MIT-blue.svg

Sistem pengaduan masyarakat berbasis web yang memungkinkan warga untuk melaporkan masalah dan keluhan secara online. Admin dapat menanggapi dan mengelola laporan dengan mudah.

⚠️ PERINGATAN KEAMANAN
SISTEM INI DIBUAT DENGAN KERENTANAN KEAMANAN YANG DISENGAJA UNTUK TUJUAN PEMBELAJARAN DAN PENGETESAN KEAMANAN (PENTEST). JANGAN GUNAKAN DI PRODUCTION ENVIRONMENT!

🎯 Tujuan Project
Project ini dibuat khusus untuk:

Pembelajaran tentang keamanan web application

Latihan penetration testing untuk mahasiswa/pengembang

Memahami berbagai jenis kerentanan web yang umum

Praktik memperbaiki vulnerability dalam aplikasi web

🔍 Kerentanan yang Disengaja
1. SQL Injection
Input user langsung dimasukkan ke query tanpa sanitization

Tidak menggunakan prepared statements

Terdapat halaman SQL executor untuk testing

2. Cross-Site Scripting (XSS)
Output tidak di-escape dengan benar

Form input rentan terhadap script injection

3. File Upload Vulnerabilities
Upload file tanpa validasi adequate

Tidak membatasi tipe file yang diupload

Nama file menggunakan original filename

4. Insecure Direct Object Reference (IDOR)
Parameter ID tidak divalidasi

Tidak ada authorization checks

5. Cross-Site Request Forgery (CSRF)
Tidak ada token protection pada form

Request dapat dipalsukan dari external site

6. Session Management Issues
Session fixation vulnerabilities

Tidak ada regenerasi session ID

🛠️ Teknologi yang Digunakan
Backend: PHP Native

Frontend: Bootstrap 5, JavaScript

Database: MySQL

Server: XAMPP / Laragon (local development)

📦 Instalasi
Prerequisites
PHP 7.4 atau lebih tinggi

MySQL 5.7 atau lebih tinggi

Web server (Apache/Nginx)

Composer (optional)

Langkah Instalasi
Clone Repository

bash
git clone https://github.com/username/sistem-pengaduan.git
cd sistem-pengaduan
Setup Database

sql
CREATE DATABASE sistem_pengaduan;
USE sistem_pengaduan;

-- Import file database.sql yang disediakan
Konfigurasi Database
Edit file config/koneksi.php:

php
$host = "localhost";
$user = "username_db";
$pass = "password_db"; 
$db = "sistem_pengaduan";
Setup Uploads Directory

bash
mkdir uploads
chmod 755 uploads
Akses Aplikasi
Buka browser dan akses: http://localhost/sistem-pengaduan

👥 Default Login
Admin
Username: admin

Password: admin123

User
Daftar melalui halaman registrasi

🎯 Cara Testing Kerentanan
1. SQL Injection Testing
sql
-- Pada form login
Username: admin' OR '1'='1
Password: anything

-- Pada search field
search: ' UNION SELECT username, password FROM users --
2. XSS Testing
html
-- Pada form input
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
3. File Upload Testing
Upload file PHP dengan nama shell.php

Coba akses melalui: http://localhost/sistem-pengaduan/uploads/shell.php

4. IDOR Testing
Ubah parameter ID di URL: detail_laporan.php?id=1 menjadi id=2

📁 Struktur Project
text
sistem-pengaduan/
├── admin/                 # Halaman admin
│   ├── dashboard.php      # Dashboard admin
│   ├── detail_laporan.php # Detail laporan
│   ├── hapus_laporan.php  # Hapus laporan (rentan)
│   └── sql_executor.php   # SQL executor (sangat rentan)
├── user/                  # Halaman user
│   ├── dashboard.php      # Dashboard user
│   ├── buat_laporan.php   # Buat laporan (rentan)
│   ├── edit_laporan.php   # Edit laporan
│   └── hapus_laporan.php  # Hapus laporan
├── config/
│   └── koneksi.php        # Konfigurasi database (rentan)
├── templates/             # Template HTML
│   ├── header.php         # Header template
│   └── footer.php         # Footer template
├── uploads/               # Folder upload (rentan)
├── index.php              # Halaman utama
├── login.php              # Halaman login (rentan)
├── registrasi.php         # Halaman registrasi
├── logout.php             # Logout
└── database.sql           # Struktur database
🚀 Fitur yang Tersedia
Untuk Masyarakat (User)
📝 Registrasi akun baru

📤 Membuat laporan pengaduan

📷 Upload foto bukti

👀 Melihat riwayat laporan

✏️ Mengedit laporan sendiri

❌ Menghapus laporan sendiri

Untuk Admin
👥 Kelola semua laporan

📊 Dashboard statistik

💬 Beri tanggapan pada laporan

📋 Ubah status laporan

👨‍💼 Tambah admin baru

⚠️ SQL Executor (for testing)

🔧 Cara Memperbaiki Kerentanan
1. Perbaikan SQL Injection
php
// Ganti dengan prepared statements
$stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
2. Perbaikan XSS
php
// Gunakan htmlspecialchars untuk output
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
3. Perbaikan File Upload
php
// Validasi file upload
$allowed_types = ['image/jpeg', 'image/png'];
$file_type = mime_content_type($file['tmp_name']);
4. Tambahkan CSRF Protection
php
// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
📝 License
Project ini dilisensikan di bawah MIT License - lihat file LICENSE untuk detail lengkap.

⚠️ Disclaimer
PERINGATAN: Project ini dibuat khusus untuk tujuan edukasi dan testing keamanan. Penulis tidak bertanggung jawab atas penyalahgunaan code atau kerentanan yang ada. Selalu gunakan di environment yang terkontrol dan terisolasi.

🤝 Kontribusi
Contribusi dipersilakan! Silakan fork project ini dan submit pull request untuk perbaikan atau tambahan fitur.

📞 Kontak
Untuk pertanyaan atau informasi lebih lanjut:

Email: your-email@example.com

Issues: GitHub Issues

Selamat Mencoba dan Happy Pentesting! 🎯

Jangan lupa untuk selalu membackup database sebelum melakukan testing yang destructive!

