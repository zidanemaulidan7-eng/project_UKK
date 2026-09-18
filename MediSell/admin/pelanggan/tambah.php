<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Karyawan/Kasir']);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $nama=trim($_POST['nama_pelanggan']); $hp=trim($_POST['no_hp']); $alamat=trim($_POST['alamat']);
    $stmt=$conn->prepare("INSERT INTO pelanggan(nama_pelanggan,no_hp,alamat) VALUES(?,?,?)");
    $stmt->bind_param('sss',$nama,$hp,$alamat); $stmt->execute(); header('Location: index.php'); exit;
}
$page_title='Tambah Pelanggan'; include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Tambah Pelanggan</h1></div><div class="panel form-panel"><form method="post">
<label>Nama Pelanggan</label><input name="nama_pelanggan" required>
<label>No. HP</label><input name="no_hp">
<label>Alamat</label><textarea name="alamat"></textarea>
<button class="btn primary">Simpan</button><a class="btn" href="index.php">Kembali</a>
</form></div><?php include '../../partials/footer.php'; ?>
