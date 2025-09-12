<?php
include '../template/header.php';
include '../config/koneksi.php';

// Pastikan session sudah dimulai dan hanya admin yang bisa akses
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validasi level admin dengan lebih aman
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Token CSRF untuk form tambah admin dan user
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Query dengan prepared statement untuk mendapatkan semua laporan
$query = "SELECT l.*, u.nama as nama_pelapor 
          FROM laporan l 
          JOIN users u ON l.user_id = u.id 
          ORDER BY l.tanggal DESC";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Query untuk mendapatkan semua admin
$admin_query = "SELECT id, nama, username FROM users WHERE level = 'admin'";
$admin_stmt = mysqli_prepare($koneksi, $admin_query);
mysqli_stmt_execute($admin_stmt);
$admin_result = mysqli_stmt_get_result($admin_stmt);

// Query untuk mendapatkan semua user (non-admin)
$user_query = "SELECT id, nama, username FROM users WHERE level = 'user'";
$user_stmt = mysqli_prepare($koneksi, $user_query);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);

// Proses tambah admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_admin'])) {
    
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Token keamanan tidak valid!";
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate token
    } else {
        // Sanitasi input
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validasi input
        if (empty($nama) || empty($username) || empty($password)) {
            $error = "Semua field harus diisi!";
        } elseif (strlen($password) < 8) {
            $error = "Password minimal 8 karakter!";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error = "Password harus mengandung huruf besar, huruf kecil, dan angka!";
        } elseif ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak sesuai!";
        } else {
            // Cek apakah username sudah ada dengan prepared statement
            $check_query = "SELECT id FROM users WHERE username = ?";
            $check_stmt = mysqli_prepare($koneksi, $check_query);
            mysqli_stmt_bind_param($check_stmt, "s", $username);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $error = "Username sudah digunakan!";
                mysqli_stmt_close($check_stmt);
            } else {
                mysqli_stmt_close($check_stmt);
                
                // Hash password dengan algoritma yang kuat
                $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
                
                // Insert admin baru dengan prepared statement
                $insert_query = "INSERT INTO users (nama, username, password, level) 
                                 VALUES (?, ?, ?, 'admin')";
                $insert_stmt = mysqli_prepare($koneksi, $insert_query);
                mysqli_stmt_bind_param($insert_stmt, "sss", $nama, $username, $hashed_password);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    $_SESSION['success'] = "Admin berhasil ditambahkan!";
                    mysqli_stmt_close($insert_stmt);
                    
                    // Regenerate CSRF token setelah operasi sukses
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
                    mysqli_stmt_close($insert_stmt);
                }
            }
        }
        
        // Regenerate CSRF token jika ada error
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Proses tambah user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_user'])) {
    
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Token keamanan tidak valid!";
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate token
    } else {
        // Sanitasi input
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validasi input
        if (empty($nama) || empty($username) || empty($password)) {
            $error = "Semua field harus diisi!";
        } elseif (strlen($password) < 8) {
            $error = "Password minimal 8 karakter!";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $error = "Password harus mengandung huruf besar, huruf kecil, dan angka!";
        } elseif ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak sesuai!";
        } else {
            // Cek apakah username sudah ada dengan prepared statement
            $check_query = "SELECT id FROM users WHERE username = ?";
            $check_stmt = mysqli_prepare($koneksi, $check_query);
            mysqli_stmt_bind_param($check_stmt, "s", $username);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $error = "Username sudah digunakan!";
                mysqli_stmt_close($check_stmt);
            } else {
                mysqli_stmt_close($check_stmt);
                
                // Hash password dengan algoritma yang kuat
                $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
                
                // Insert user baru dengan prepared statement
                $insert_query = "INSERT INTO users (nama, username, password, level) 
                                 VALUES (?, ?, ?, 'user')";
                $insert_stmt = mysqli_prepare($koneksi, $insert_query);
                mysqli_stmt_bind_param($insert_stmt, "sss", $nama, $username, $hashed_password);
                
                if (mysqli_stmt_execute($insert_stmt)) {
                    $_SESSION['success'] = "User berhasil ditambahkan!";
                    mysqli_stmt_close($insert_stmt);
                    
                    // Regenerate CSRF token setelah operasi sukses
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
                    mysqli_stmt_close($insert_stmt);
                }
            }
        }
        
        // Regenerate CSRF token jika ada error
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Proses hapus user
if (isset($_GET['hapus_user'])) {
    $user_id = intval($_GET['hapus_user']);
    
    // Pastikan user yang dihapus bukan admin
    $check_level_query = "SELECT level FROM users WHERE id = ?";
    $check_level_stmt = mysqli_prepare($koneksi, $check_level_query);
    mysqli_stmt_bind_param($check_level_stmt, "i", $user_id);
    mysqli_stmt_execute($check_level_stmt);
    mysqli_stmt_bind_result($check_level_stmt, $user_level);
    mysqli_stmt_fetch($check_level_stmt);
    mysqli_stmt_close($check_level_stmt);
    
    if ($user_level === 'admin') {
        $_SESSION['error'] = "Tidak dapat menghapus admin!";
    } else {
        // Hapus user dengan prepared statement
        $delete_query = "DELETE FROM users WHERE id = ?";
        $delete_stmt = mysqli_prepare($koneksi, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
        
        if (mysqli_stmt_execute($delete_stmt)) {
            $_SESSION['success'] = "User berhasil dihapus!";
            mysqli_stmt_close($delete_stmt);
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
            mysqli_stmt_close($delete_stmt);
        }
    }
}

// Tampilkan pesan success dari session jika ada
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Tampilkan pesan error dari session jika ada
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

<div class="row">
    <div class="col-md-9">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Semua Laporan Pengaduan</h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($no++); ?></td>
                                        <td><?php echo htmlspecialchars(date('d M Y H:i', strtotime($row['tanggal']))); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pelapor']); ?></td>
                                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = '';
                                            if ($row['status'] == 'menunggu') $status_class = 'status-menunggu';
                                            if ($row['status'] == 'diproses') $status_class = 'status-diproses';
                                            if ($row['status'] == 'selesai') $status_class = 'status-selesai';
                                            ?>
                                            <span class="<?php echo htmlspecialchars($status_class); ?> fw-bold">
                                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="detail_laporan.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Belum ada laporan pengaduan.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Kelola Admin</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary btn-sm w-100 mb-3" data-bs-toggle="modal" data-bs-target="#tambahAdminModal">
                    Tambah Admin
                </button>
                
                <h6>Daftar Admin:</h6>
                <ul class="list-group">
                    <?php while ($admin = mysqli_fetch_assoc($admin_result)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($admin['nama']); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($admin['username']); ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Kelola User</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-success btn-sm w-100 mb-3" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                    Tambah User
                </button>
                
                <h6>Daftar User:</h6>
                <ul class="list-group">
                    <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($user['nama']); ?>
                            <div>
                                <span class="badge bg-secondary rounded-pill me-2"><?php echo htmlspecialchars($user['username']); ?></span>
                                <a href="?hapus_user=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="tambahAdminModal" tabindex="-1" aria-labelledby="tambahAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAdminModalLabel">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <?php if (isset($error) && isset($_POST['tambah_admin'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required 
                               pattern="[A-Za-z\s]{3,50}" title="Nama hanya boleh mengandung huruf dan spasi (3-50 karakter)">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required 
                               pattern="[a-zA-Z0-9_]{5,20}" title="Username hanya boleh mengandung huruf, angka, dan underscore (5-20 karakter)">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required 
                               minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                               title="Password minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka">
                        <div class="form-text">Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_admin" class="btn btn-primary">Tambah Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahUserModalLabel">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <?php if (isset($error) && isset($_POST['tambah_user'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    
                    <div class="mb-3">
                        <label for="nama_user" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_user" name="nama" required 
                               pattern="[A-Za-z\s]{3,50}" title="Nama hanya boleh mengandung huruf dan spasi (3-50 karakter)">
                    </div>
                    <div class="mb-3">
                        <label for="username_user" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username_user" name="username" required 
                               pattern="[a-zA-Z0-9_]{5,20}" title="Username hanya boleh mengandung huruf, angka, dan underscore (5-20 karakter)">
                    </div>
                    <div class="mb-3">
                        <label for="password_user" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password_user" name="password" required 
                               minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                               title="Password minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka">
                        <div class="form-text">Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password_user" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password_user" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_user" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Tutup statement
if (isset($stmt)) mysqli_stmt_close($stmt);
if (isset($admin_stmt)) mysqli_stmt_close($admin_stmt);
if (isset($user_stmt)) mysqli_stmt_close($user_stmt);

include '../template/footer.php'; 
?>