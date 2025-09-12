<?php
include '../template/header.php';
include '../config/koneksi.php';

if (@$_SESSION['level'] != 'user') {
    header("Location: ../admin/dashboard.php");
}

$user_id = $_REQUEST['user_id'] ?? $_SESSION['user_id'];
$query = "SELECT * FROM laporan WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Laporan Saya</h5>
                <a href="buat_laporan.php" class="btn btn-primary">Buat Laporan Baru</a>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Foto</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d M Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td><?php echo $row['judul']; ?></td>
                                        <td>
                                            <?php if (!empty($row['foto'])): ?>
                                                <img src="../uploads/<?php echo $row['foto']; ?>" class="img-thumbnail" style="max-width: 60px; max-height: 60px;" alt="Foto Laporan">
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $row['lokasi'] ?? '-'; ?></td>
                                        <td>
                                            <?php 
                                            $status_class = '';
                                            if ($row['status'] == 'menunggu') $status_class = 'status-menunggu';
                                            if ($row['status'] == 'diproses') $status_class = 'status-diproses';
                                            if ($row['status'] == 'selesai') $status_class = 'status-selesai';
                                            ?>
                                            <span class="<?php echo $status_class; ?> fw-bold">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_laporan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="hapus_laporan.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Anda belum membuat laporan. <a href="buat_laporan.php">Buat laporan pertama Anda</a>.
                    </div>
                <?php endif; ?>    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>