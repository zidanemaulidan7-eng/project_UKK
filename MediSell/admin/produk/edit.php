<?php
require_once '../../config/auth.php';
require_once '../../config/koneksi.php';
role_diizinkan(['Admin']);
$id=(int)($_GET['id'] ?? 0);
$stmt=$conn->prepare("SELECT * FROM produk WHERE id_produk=?");
$stmt->bind_param('i',$id); $stmt->execute(); $data=$stmt->get_result()->fetch_assoc();
if(!$data) die('Produk tidak ditemukan.');

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $kode=trim($_POST['kode_produk']); $nama=trim($_POST['nama_produk']);
    $harga=(float)$_POST['harga']; $stok=(int)$_POST['stok'];
    $stmt=$conn->prepare("UPDATE produk SET kode_produk=?,nama_produk=?,harga=?,stok=? WHERE id_produk=?");
    $stmt->bind_param('ssdii',$kode,$nama,$harga,$stok,$id);
    $stmt->execute(); header('Location: index.php'); exit;
}
$page_title='Edit Produk';
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Edit Produk</h1></div>
<div class="panel form-panel"><form method="post">
<label>Kode Produk</label><input name="kode_produk" value="<?= htmlspecialchars($data['kode_produk']) ?>" required>
<label>Nama Produk</label><input name="nama_produk" value="<?= htmlspecialchars($data['nama_produk']) ?>" required>
<label>Harga</label><input type="number" name="harga" value="<?= $data['harga'] ?>" min="0" required>
<label>Stok</label><input type="number" name="stok" value="<?= $data['stok'] ?>" min="0" required>
<button class="btn primary">Simpan Perubahan</button><a class="btn" href="index.php">Kembali</a>
</form></div>
<?php include '../../partials/footer.php'; ?>
