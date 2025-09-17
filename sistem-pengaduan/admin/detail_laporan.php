<?php
include '../template/header.php';
include '../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$laporan_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$laporan_id || $laporan_id <= 0) {
    $_SESSION['error'] = "ID laporan tidak valid!";
    header("Location: dashboard.php");
    exit();
}

$laporan_query = "SELECT l.*, u.nama as nama_pelapor 
                  FROM laporan l 
                  JOIN users u ON l.user_id = u.id 
                  WHERE l.id = ?";
$laporan_stmt = mysqli_prepare($koneksi, $laporan_query);
mysqli_stmt_bind_param($laporan_stmt, "i", $laporan_id);
mysqli_stmt_execute($laporan_stmt);
$laporan_result = mysqli_stmt_get_result($laporan_stmt);
$laporan = mysqli_fetch_assoc($laporan_result);

if (!$laporan) {
    $_SESSION['error'] = "Laporan tidak ditemukan!";
    header("Location: dashboard.php");
    exit();
}

$tanggapan_query = "SELECT t.*, u.nama as nama_admin 
                    FROM tanggapan t 
                    JOIN users u ON t.admin_id = u.id 
                    WHERE t.laporan_id = ? 
                    ORDER BY t.tanggal_tanggapan DESC";
$tanggapan_stmt = mysqli_prepare($koneksi, $tanggapan_query);
mysqli_stmt_bind_param($tanggapan_stmt, "i", $laporan_id);
mysqli_stmt_execute($tanggapan_stmt);
$tanggapan_result = mysqli_stmt_get_result($tanggapan_stmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Token keamanan tidak valid!";
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
    
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $allowed_statuses = ['menunggu', 'diproses', 'selesai'];
    
    if (!in_array($status, $allowed_statuses)) {
        $_SESSION['error'] = "Status tidak valid!";
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
    
    $update_query = "UPDATE laporan SET status = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($update_stmt, "si", $status, $laporan_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        $_SESSION['success'] = "Status berhasil diperbarui!";
        mysqli_stmt_close($update_stmt);
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui status: " . mysqli_error($koneksi);
        mysqli_stmt_close($update_stmt);
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_tanggapan'])) {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Token keamanan tidak valid!";
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
    
    $isi_tanggapan = trim(filter_input(INPUT_POST, 'isi_tanggapan', FILTER_SANITIZE_STRING));
    $admin_id = $_SESSION['user_id'];
    
    if (empty($isi_tanggapan)) {
        $_SESSION['error'] = "Isi tanggapan tidak boleh kosong!";
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
    
    if (strlen($isi_tanggapan) < 5) {
        $_SESSION['error'] = "Isi tanggapan minimal 5 karakter!";
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
    
    $insert_query = "INSERT INTO tanggapan (laporan_id, admin_id, isi_tanggapan) 
                     VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($koneksi, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "iis", $laporan_id, $admin_id, $isi_tanggapan);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        if ($laporan['status'] == 'menunggu') {
            $update_query = "UPDATE laporan SET status = 'diproses' WHERE id = ?";
            $update_stmt = mysqli_prepare($koneksi, $update_query);
            mysqli_stmt_bind_param($update_stmt, "i", $laporan_id);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);
        }
        
        $_SESSION['success'] = "Tanggapan berhasil ditambahkan!";
        mysqli_stmt_close($insert_stmt);
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan tanggapan: " . mysqli_error($koneksi);
        mysqli_stmt_close($insert_stmt);
        header("Location: detail_laporan.php?id=" . $laporan_id);
        exit();
    }
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Laporan</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>
                
                <h4><?php echo htmlspecialchars($laporan['judul']); ?></h4>
                <p class="text-muted">Dilaporkan oleh: <?php echo htmlspecialchars($laporan['nama_pelapor']); ?> pada <?php echo htmlspecialchars(date('d M Y H:i', strtotime($laporan['tanggal']))); ?></p>
                
                <?php if ($laporan['lokasi']): ?>
                    <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($laporan['lokasi']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($laporan['foto'])): ?>
                    <div class="mb-3">
                        <strong>Foto Bukti:</strong><br>
                        <?php
                        $safe_filename = basename($laporan['foto']);
                        $file_path = '../uploads/' . $safe_filename;
                        
                        if (file_exists($file_path) && is_file($file_path)) {
                            echo '<img src="../uploads/' . htmlspecialchars($safe_filename) . '" class="img-fluid rounded" style="max-height: 300px;" alt="Foto Bukti">';
                        } else {
                            echo '<div class="alert alert-warning">File tidak ditemukan</div>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <strong>Isi Laporan:</strong>
                    <p><?php echo nl2br(htmlspecialchars($laporan['isi'])); ?></p>
                </div>
                
                <form method="POST" class="mb-3">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Ubah Status:</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="menunggu" <?php if ($laporan['status'] == 'menunggu') echo 'selected'; ?>>Menunggu</option>
                                <option value="diproses" <?php if ($laporan['status'] == 'diproses') echo 'selected'; ?>>Diproses</option>
                                <option value="selesai" <?php if ($laporan['status'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tanggapan</h5>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($tanggapan_result) > 0): ?>
                    <?php while ($tanggapan = mysqli_fetch_assoc($tanggapan_result)): ?>
                        <div class="mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong><?php echo htmlspecialchars($tanggapan['nama_admin']); ?></strong>
                                <span class="text-muted"><?php echo htmlspecialchars(date('d M Y H:i', strtotime($tanggapan['tanggal_tanggapan']))); ?></span>
                            </div>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($tanggapan['isi_tanggapan'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted">Belum ada tanggapan.</p>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="mb-3">
                        <label for="isi_tanggapan" class="form-label">Tambah Tanggapan:</label>
                        <textarea class="form-control" id="isi_tanggapan" name="isi_tanggapan" rows="3" 
                                  required minlength="5" maxlength="1000"></textarea>
                        <div class="form-text">Minimal 5 karakter, maksimal 1000 karakter</div>
                    </div>
                    <button type="submit" name="tambah_tanggapan" class="btn btn-primary">Kirim Tanggapan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
if (isset($laporan_stmt)) mysqli_stmt_close($laporan_stmt);
if (isset($tanggapan_stmt)) mysqli_stmt_close($tanggapan_stmt);

include '../template/footer.php'; 

?>
