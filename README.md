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

## Screenshots

![App Screenshot](https://via.placeholder.com/468x300?text=App+Screenshot+Here)


## Acknowledgements

 - [Awesome Readme Templates](https://awesomeopensource.com/project/elangosundar/awesome-README-templates)
 - [Awesome README](https://github.com/matiassingers/awesome-readme)
 - [How to write a Good readme](https://bulldogjob.com/news/449-how-to-write-a-good-readme-for-your-github-project)


## API Reference

#### Get all items

```http
  GET /api/items
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `api_key` | `string` | **Required**. Your API key |

#### Get item

```http
  GET /api/items/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### add(num1, num2)

Takes two numbers and returns the sum.


## Appendix

Any additional information goes here


## Authors

- [@octokatherine](https://www.github.com/octokatherine)


## Badges

Add badges from somewhere like: [shields.io](https://shields.io/)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
[![AGPL License](https://img.shields.io/badge/license-AGPL-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

## Color Reference

| Color             | Hex                                                                |
| ----------------- | ------------------------------------------------------------------ |
| Example Color | ![#0a192f](https://via.placeholder.com/10/0a192f?text=+) #0a192f |
| Example Color | ![#f8f8f8](https://via.placeholder.com/10/f8f8f8?text=+) #f8f8f8 |
| Example Color | ![#00b48a](https://via.placeholder.com/10/00b48a?text=+) #00b48a |
| Example Color | ![#00d1a0](https://via.placeholder.com/10/00b48a?text=+) #00d1a0 |


## Used By

This project is used by the following companies:

- Company 1
- Company 2


## Usage/Examples

```javascript
import Component from 'my-project'

function App() {
  return <Component />
}
```


## Running Tests

To run tests, run the following command

```bash
  npm run test
```


## Tech Stack

**Client:** React, Redux, TailwindCSS

**Server:** Node, Express


## Support

For support, email fake@fake.com or join our Slack channel.


## Run Locally

Clone the project

```bash
  git clone https://link-to-project
```

Go to the project directory

```bash
  cd my-project
```

Install dependencies

```bash
  npm install
```

Start the server

```bash
  npm run start
```


## Roadmap

- Additional browser support

- Add more integrations


## Related

Here are some related projects

[Awesome README](https://github.com/matiassingers/awesome-readme)


## Optimizations

What optimizations did you make in your code? E.g. refactors, performance improvements, accessibility


![Logo](https://dev-to-uploads.s3.amazonaws.com/uploads/articles/th5xamgrr6se0x5ro4g6.png)


## License

[MIT](https://choosealicense.com/licenses/mit/)


## Lessons Learned

What did you learn while building this project? What challenges did you face and how did you overcome them?


## Installation

Install my-project with npm

```bash
  npm install my-project
  cd my-project
```
    
## 🛠 Skills
Javascript, HTML, CSS...


## Other Common Github Profile Sections
👩‍💻 I'm currently working on...

🧠 I'm currently learning...

👯‍♀️ I'm looking to collaborate on...

🤔 I'm looking for help with...

💬 Ask me about...

📫 How to reach me...

😄 Pronouns...

⚡️ Fun fact...


## 🔗 Links
[![portfolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://katherineoelsner.com/)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/)
[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/)


# Hi, I'm Katherine! 👋


## 🚀 About Me
I'm a full stack developer...


## Feedback

If you have any feedback, please reach out to us at fake@fake.com


## Features

- Light/dark mode toggle
- Live previews
- Fullscreen mode
- Cross platform


## FAQ

#### Question 1

Answer 1

#### Question 2

Answer 2


## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`API_KEY`

`ANOTHER_API_KEY`


## Documentation

[Documentation](https://linktodocumentation)


## Deployment

To deploy this project run

```bash
  npm run deploy
```


## Demo

Insert gif or link to demo


## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.

