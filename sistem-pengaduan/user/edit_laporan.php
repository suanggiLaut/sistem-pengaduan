<?php
include '../template/header.php';
include '../config/koneksi.php';

// KERENTANAN 1: Validasi session yang lemah
if (@$_SESSION['level'] != 'user') {
    header("Location: ../admin/dashboard.php");
}

// KERENTANAN 2: Tidak memvalidasi parameter ID dengan benar
$user_id = $_SESSION['user_id'];
$laporan_id = $_GET['id']; // Langsung mengambil tanpa filter

// KERENTANAN 3: SQL Injection - langsung memasukkan input user ke query
$query = "SELECT * FROM laporan WHERE id = $laporan_id AND user_id = $user_id";
$result = mysqli_query($koneksi, $query);
$laporan = mysqli_fetch_assoc($result);

// KERENTANAN 4: Authorization bypass - hanya memeriksa apakah ada hasil, bukan kepemilikan
if (!$laporan) {
    echo "<script>alert('Laporan tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // KERENTANAN 5: Tidak menggunakan mysqli_real_escape_string atau prepared statements
    $judul = $_POST['judul'];
    $lokasi = $_POST['lokasi'];
    $isi = $_POST['isi'];
    $hapus_foto = isset($_POST['hapus_foto']) ? true : false;
    
    // Validasi minimal
    if (empty($judul) || empty($isi)) {
        $error = "Judul dan isi laporan harus diisi!";
    } else {
        // Proses upload foto baru jika ada
        $foto_name = $laporan['foto'];
        
        if ($hapus_foto && !empty($foto_name)) {
            // Hapus foto lama
            $file_path = "../uploads/" . $foto_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            $foto_name = '';
        }
        
        if (!empty($_FILES['foto']['name'])) {
            // Hapus foto lama jika ada
            if (!empty($foto_name)) {
                $file_path = "../uploads/" . $foto_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            // Upload foto baru
            $foto = $_FILES['foto'];
            $foto_name = time() . '_' . basename($foto['name']);
            $target_dir = "../uploads/";
            $target_file = $target_dir . $foto_name;
            
            // KERENTANAN 6: Validasi upload file yang lemah
            // Hanya memeriksa ekstensi file, bukan tipe MIME yang sebenarnya
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check jika file adalah gambar (bisa diakali)
            $check = getimagesize($foto["tmp_name"]);
            if ($check === false) {
                $error = "File yang diupload bukan gambar.";
            }
            // Check ukuran file (maks 2MB)
            elseif ($foto["size"] > 2000000) {
                $error = "Ukuran file terlalu besar. Maksimal 2MB.";
            }
            // Hanya format tertentu
            elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $error = "Hanya format JPG, JPEG, PNG & GIF yang diizinkan.";
            }
            // Coba upload file
            else {
                move_uploaded_file($foto["tmp_name"], $target_file);
            }
        }
        
        // Jika tidak ada error, update database
        if (empty($error)) {
            // KERENTANAN 7: SQL Injection pada update query
            $update_query = "UPDATE laporan SET judul = '$judul', lokasi = '$lokasi', 
                             isi = '$isi', foto = '$foto_name' 
                             WHERE id = $laporan_id";
            
            if (mysqli_query($koneksi, $update_query)) {
                $success = "Laporan berhasil diperbarui!";
                // Refresh data
                $result = mysqli_query($koneksi, $query);
                $laporan = mysqli_fetch_assoc($result);
            } else {
                $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Laporan</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <!-- KERENTANAN 8: Form tanpa CSRF protection -->
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $laporan_id; ?>">
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $laporan['judul']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi Kejadian</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo $laporan['lokasi']; ?>" placeholder="Contoh: Jl. Merdeka No. 10, Jakarta">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto Saat Ini:</label>
                        <?php if (!empty($laporan['foto'])): ?>
                            <div class="mb-2">
                                <img src="../uploads/<?php echo $laporan['foto']; ?>" class="img-thumbnail" style="max-height: 200px;" alt="Foto Laporan">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="hapus_foto" name="hapus_foto">
                                <label class="form-check-label" for="hapus_foto">Hapus foto saat ini</label>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada foto</p>
                        <?php endif; ?>
                        
                        <label for="foto" class="form-label">Upload Foto Baru (Opsional)</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <div class="form-text">Format: JPG, JPEG, PNG, GIF (Maks. 2MB)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Laporan <span class="text-danger">*</span></label>
                        <!-- KERENTANAN 9: Output tanpa escaping - memungkinkan XSS -->
                        <textarea class="form-control" id="isi" name="isi" rows="5" required><?php echo $laporan['isi']; ?></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Perbarui Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>