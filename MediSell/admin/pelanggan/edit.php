<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Karyawan/Kasir']);
$id=(int)($_GET['id']??0); $stmt=$conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan=?"); $stmt->bind_param('i',$id); $stmt->execute(); $data=$stmt->get_result()->fetch_assoc();
if(!$data) die('Pelanggan tidak ditemukan.');
if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama=trim($_POST['nama_pelanggan']); $hp=trim($_POST['no_hp']); $alamat=trim($_POST['alamat']);
    $stmt=$conn->prepare("UPDATE pelanggan SET nama_pelanggan=?,no_hp=?,alamat=? WHERE id_pelanggan=?");
    $stmt->bind_param('sssi',$nama,$hp,$alamat,$id); $stmt->execute(); header('Location: index.php'); exit;
}
$page_title='Edit Pelanggan'; include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Edit Pelanggan</h1></div><div class="panel form-panel"><form method="post">
<label>Nama Pelanggan</label><input name="nama_pelanggan" value="<?= htmlspecialchars($data['nama_pelanggan']) ?>" required>
<label>No. HP</label><input name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>">
<label>Alamat</label><textarea name="alamat"><?= htmlspecialchars($data['alamat']) ?></textarea>
<button class="btn primary">Simpan Perubahan</button><a class="btn" href="index.php">Kembali</a>
</form></div><?php include '../../partials/footer.php'; ?>
