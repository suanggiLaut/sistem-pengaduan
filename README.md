Sistem Pengaduan Masyarakat 🏢

Sistem pengaduan masyarakat berbasis web yang memungkinkan warga untuk melaporkan masalah dan keluhan secara online. Admin dapat menanggapi dan mengelola laporan dengan mudah.

⚠️ PERINGATAN KEAMANAN
SISTEM INI DIBUAT DENGAN KERENTANAN KEAMANAN YANG DISENGAJA UNTUK TUJUAN PEMBELAJARAN DAN PENGETESAN KEAMANAN (PENTEST). JANGAN GUNAKAN DI PRODUCTION ENVIRONMENT!

🎯 Tujuan Project
Project ini dibuat khusus untuk:
Pembelajaran tentang keamanan web application
Latihan penetration testing
Memahami berbagai jenis kerentanan web yang umum

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
Username: ####
Paswd: ####

User
username:soc
paswd:soc

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
📝 License
Project ini dilisensikan di bawah MIT License - lihat file LICENSE untuk detail lengkap.

⚠️ Disclaimer
PERINGATAN: Project ini dibuat khusus untuk tujuan edukasi dan testing keamanan.

🤝 Kontribusi
Contribusi dipersilakan! Silakan fork project ini dan submit pull request untuk perbaikan atau tambahan fitur.

📞 Kontak
Untuk pertanyaan atau informasi lebih lanjut:
Email: suanggiLaut@gmail.com
Issues: GitHub Issues
Selamat Mencoba dan Happy Pentesting! 🎯


