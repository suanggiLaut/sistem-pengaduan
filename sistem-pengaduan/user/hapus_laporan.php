<?php
include '../config/koneksi.php';

session_start();
if ($_SESSION['level'] != 'user') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$laporan_id = $_GET['id'] ?? 0;

$query = "SELECT foto FROM laporan WHERE id = $laporan_id AND user_id = $user_id";
$result = mysqli_query($koneksi, $query);
$laporan = mysqli_fetch_assoc($result);

if ($laporan && !empty($laporan['foto'])) {
    $file_path = "../uploads/" . $laporan['foto'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$delete_query = "DELETE FROM laporan WHERE id = $laporan_id AND user_id = $user_id";
mysqli_query($koneksi, $delete_query);

header("Location: dashboard.php");
exit();
?>