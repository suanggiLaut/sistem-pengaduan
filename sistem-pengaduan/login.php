<?php
session_start();
include 'config/koneksi.php';
if(isset($_SESSION['user_id'])){if($_SESSION['level']=='admin'){header("Location: admin/dashboard.php");}else{header("Location: user/dashboard.php");}exit();}
$e='';if($_SERVER['REQUEST_METHOD']=='POST'){$u=mysqli_real_escape_string($koneksi,$_POST['username']);$p=$_POST['password'];
$q="SELECT * FROM users WHERE username = '$u'";$r=mysqli_query($koneksi,$q);if(mysqli_num_rows($r)==1){$d=mysqli_fetch_assoc($r);
if(password_verify($p,$d['password'])){$_SESSION['user_id']=$d['id'];$_SESSION['nama']=$d['nama'];$_SESSION['level']=$d['level'];
if($d['level']=='admin'){header("Location: admin/dashboard.php");}else{header("Location: user/dashboard.php");}exit();}else{$e="Username atau password salah!";}
}else{$e="Username atau password salah!";}}?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login - Sistem Pengaduan Masyarakat</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"><style>
:root{--primary:#1a56db;--primary-dark:#1e40af;--secondary:#4b5563;--light:#f9fafb;--dark:#1f2937;--success:#059669;--warning:#d97706;--border:#e5e7eb;}
body{font-family:'Inter',sans-serif;color:var(--dark);line-height:1.6;background-color:#fff;display:flex;flex-direction:column;min-height:100vh;}
.navbar{background-color:#fff;box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:0.8rem 0;}
.navbar-brand{font-weight:700;color:var(--primary);font-size:1.4rem;}
.nav-link{font-weight:500;color:var(--dark);transition:color 0.2s;padding:0.5rem 1rem!important;border-radius:6px;}
.nav-link:hover{color:var(--primary);background-color:#f3f4f6;}
.login-section{flex:1;display:flex;align-items:center;padding:3rem 0;background:linear-gradient(120deg,#e0f2fe 0%,#dbeafe 100%);}
.login-container{background-color:#fff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.1);overflow:hidden;width:100%;max-width:450px;margin:2rem auto;}
.login-header{background-color:var(--primary);color:#fff;padding:2rem;text-align:center;}
.login-header h2{font-weight:700;margin:0;font-size:1.8rem;}
.login-body{padding:2rem;}
.form-group{margin-bottom:1.5rem;}
.form-label{font-weight:500;color:var(--dark);margin-bottom:0.5rem;}
.form-control{padding:0.75rem 1rem;border:1px solid var(--border);border-radius:8px;font-size:1rem;transition:all 0.3s;}
.form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,86,219,0.15);}
.input-icon{position:relative;}
.input-icon i{position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--secondary);}
.input-icon .form-control{padding-left:3rem;}
.btn-primary{background-color:var(--primary);border-color:var(--primary);padding:0.75rem 1.5rem;font-weight:600;border-radius:8px;font-size:1.1rem;width:100%;transition:all 0.3s;}
.btn-primary:hover{background-color:var(--primary-dark);border-color:var(--primary-dark);transform:translateY(-2px);}
.alert{border-radius:8px;padding:1rem;margin-bottom:1.5rem;display:flex;align-items:center;}
.alert-danger{background-color:rgba(254,226,226,0.3);color:#dc2626;border-left:4px solid #dc2626;}
.register-link{text-align:center;margin-top:1.5rem;color:var(--secondary);}
.register-link a{color:var(--primary);text-decoration:none;font-weight:500;}
.register-link a:hover{text-decoration:underline;}
footer{background-color:var(--dark);padding:2rem 0 1rem;color:#f3f4f6;margin-top:auto;}
footer h5{font-weight:600;margin-bottom:1rem;color:#fff;font-size:1.1rem;}
footer a{color:#d1d5db;text-decoration:none;transition:color 0.2s;}
footer a:hover{color:#fff;}
.copyright{color:#9ca3af;text-align:center;margin-top:2rem;padding-top:1rem;border-top:1px solid #374151;}
@media (max-width:576px){.login-container{margin:1rem;max-width:100%;}.login-header,.login-body{padding:1.5rem;}}
</style></head><body><nav class="navbar navbar-expand-lg navbar-light"><div class="container"><a class="navbar-brand" href="index.php"><i class="bi bi-megaphone me-2"></i>Sistem Pengaduan</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="navbarNav"><ul class="navbar-nav me-auto"><li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li><li class="nav-item"><a class="nav-link" href="index.php#tentang">Tentang</a></li><li class="nav-item"><a class="nav-link" href="index.php#layanan">Layanan</a></li></ul><ul class="navbar-nav"><li class="nav-item me-2"><a class="nav-link" href="login.php">Login</a></li><li class="nav-item"><a class="btn btn-primary" href="registrasi.php">Daftar</a></li></ul></div></div></nav><section class="login-section"><div class="container"><div class="login-container"><div class="login-header"><h2>Masuk ke Akun Anda</h2></div><div class="login-body"><?php if($e):?><div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><span><?php echo$e;?></span></div><?php endif;?><form method="POST" action=""><div class="form-group"><label for="username" class="form-label">Username</label><div class="input-icon"><i class="bi bi-person"></i><input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required></div></div><div class="form-group"><label for="password" class="form-label">Password</label><div class="input-icon"><i class="bi bi-lock"></i><input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required></div></div><button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</button></form><div class="register-link"><p>Belum punya akun? <a href="registrasi.php">Daftar di sini</a></p></div></div></div></div></section><footer><div class="container"><div class="row"><div class="col-md-6 mb-4"><h5>Sistem Pengaduan Masyarakat</h5><p style="color:#d1d5db;">Menjadi jembatan antara masyarakat dan pemerintah untuk pelayanan yang lebih baik.</p></div><div class="col-md-3 mb-4"><h5>Tautan</h5><ul class="list-unstyled"><li class="mb-2"><a href="index.php">Beranda</a></li><li class="mb-2"><a href="index.php#tentang">Tentang</a></li><li class="mb-2"><a href="index.php#layanan">Layanan</a></li></ul></div><div class="col-md-3 mb-4"><h5>Kontak</h5><ul class="list-unstyled"><li class="mb-2"><i class="bi bi-envelope me-2"></i> info@pengaduan.com</li><li class="mb-2"><i class="bi bi-telephone me-2"></i> (021) 1234-5678</li><li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jakarta, Indonesia</li></ul></div></div><div class="copyright"><p class="mb-0">&copy; <?php echo date('Y');?> Sistem Pengaduan Masyarakat. All rights reserved.</p></div></div></footer><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>