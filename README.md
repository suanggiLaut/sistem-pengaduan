Sistem Pengaduan Masyarakat 🏢
Sistem pengaduan masyarakat berbasis web yang memungkinkan warga untuk melaporkan masalah dan keluhan secara online. Admin dapat menanggapi dan mengelola laporan dengan mudah.

⚠️ PERINGATAN KEAMANAN
SISTEM INI DIBUAT DENGAN KERENTANAN KEAMANAN YANG DISENGAJA UNTUK TUJUAN PEMBELAJARAN DAN PENGETESAN KEAMANAN (PENTEST). JANGAN GUNAKAN DI LINGKUNGAN PRODUKSI!

🎯 Tujuan Proyek
Proyek ini dibuat khusus untuk:

Pembelajaran tentang keamanan aplikasi web.

Latihan penetration testing.

Memahami berbagai jenis kerentanan web yang umum.

🛠️ Teknologi yang Digunakan
Backend: PHP Native

Frontend: Bootstrap 5, JavaScript

Database: MySQL

Server: XAMPP / Laragon (untuk pengembangan lokal)

📦 Instalasi
Prasyarat
PHP 7.4 atau lebih tinggi

MySQL 5.7 atau lebih tinggi

Web server (Apache/Nginx)

Composer (opsional)

Langkah Instalasi
Clone Repository

Bash

git clone https://github.com/username/sistem-pengaduan.git
cd sistem-pengaduan
Setup Database
Buat database baru dan impor file .sql yang disediakan.

SQL

CREATE DATABASE sistem_pengaduan;
USE sistem_pengaduan;
-- Import file database.sql
Konfigurasi Database
Salin dan sesuaikan file config/koneksi.php dengan detail koneksi Anda:

PHP

<?php
$host = "localhost";
$user = "username_db";
$pass = "password_db"; 
$db   = "sistem_pengaduan";
?>
Setup Direktori Uploads
Buat direktori uploads dan berikan izin yang sesuai.

Bash

mkdir uploads
chmod 755 uploads
Akses Aplikasi
Buka browser Anda dan akses: http://localhost/sistem-pengaduan

👥 Akun Default
Admin
Username: admin (disarankan)

Password: password (disarankan)

User
Username: soc

Password: soc

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

💬 Memberi tanggapan pada laporan

📋 Mengubah status laporan

👨‍💼 Menambah admin baru

⚠️ SQL Executor (untuk pengujian)

📝 Lisensi
Proyek ini dilisensikan di bawah MIT License - lihat file LICENSE untuk detail lengkap.

⚠️ Disclaimer
PERINGATAN: Proyek ini dibuat khusus untuk tujuan edukasi dan pengujian keamanan. Penulis tidak bertanggung jawab atas penyalahgunaan kode atau kerentanan yang ada.

🤝 Kontribusi
Kontribusi sangat kami harapkan! Silakan fork proyek ini dan ajukan pull request untuk perbaikan atau tambahan fitur.

📞 Kontak
Untuk pertanyaan atau informasi lebih lanjut:

Email: suanggiLaut@gmail.com

Issues: GitHub Issues

Selamat Mencoba dan Happy Pentesting! 🎯
