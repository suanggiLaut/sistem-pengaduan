<?php
include '../template/header.php';
include '../config/koneksi.php';

// Pastikan hanya user yang bisa akses
if ($_SESSION['level'] != 'user') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $user_id = $_SESSION['user_id'];
    
    // Validasi
    if (empty($judul) || empty($isi)) {
        $error = "Judul dan isi laporan harus diisi!";
    } else {
        // Proses upload foto
        $foto_name = '';
        if (!empty($_FILES['foto']['name'])) {
            $foto = $_FILES['foto'];
            $foto_name = time() . '_' . basename($foto['name']);
            $target_dir = "../uploads/";
            $target_file = $target_dir . $foto_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check jika file adalah gambar
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
            elseif (!move_uploaded_file($foto["tmp_name"], $target_file)) {
                $error = "Maaf, terjadi error saat upload file.";
            }
        }
        
        // Jika tidak ada error, insert ke database
        if (empty($error)) {
            $query = "INSERT INTO laporan (user_id, judul, isi, lokasi, foto) 
                      VALUES ($user_id, '$judul', '$isi', '$lokasi', '$foto_name')";
            
            if (mysqli_query($koneksi, $query)) {
                $success = "Laporan berhasil dibuat!";
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
                <h5 class="mb-0">Buat Laporan Baru</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi Kejadian</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Contoh: Jl. Merdeka No. 10, Jakarta">
                    </div>
                    
                    <div class="mb-3">
                        <label for="foto" class="form-label">Upload Foto Bukti</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <div class="form-text">Format: JPG, JPEG, PNG, GIF (Maks. 2MB)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Laporan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="isi" name="isi" rows="5" required placeholder="Jelaskan secara detail kejadian yang ingin Anda laporkan..."></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>