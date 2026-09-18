<?php
require_once '../../config/auth.php';
require_once '../../config/koneksi.php';
role_diizinkan(['Admin']);

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $kode=trim($_POST['kode_produk']);
    $nama=trim($_POST['nama_produk']);
    $harga=(float)$_POST['harga'];
    $stok=(int)$_POST['stok'];

    $stmt=$conn->prepare("INSERT INTO produk(kode_produk,nama_produk,harga,stok) VALUES(?,?,?,?)");
    $stmt->bind_param('ssdi',$kode,$nama,$harga,$stok);
    if ($stmt->execute()) { header('Location: index.php'); exit; }
    $error='Gagal menambah produk.';
}
$page_title='Tambah Produk';
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Tambah Produk</h1></div>
<div class="panel form-panel">
<?php if(isset($error)): ?><div class="alert danger"><?= $error ?></div><?php endif; ?>
<form method="post">
<label>Kode Produk</label><input name="kode_produk" required>
<label>Nama Produk</label><input name="nama_produk" required>
<label>Harga</label><input type="number" name="harga" min="0" required>
<label>Stok</label><input type="number" name="stok" min="0" required>
<button class="btn primary" type="submit">Simpan</button>
<a class="btn" href="index.php">Kembali</a>
</form>
</div>
<?php include '../../partials/footer.php'; ?>
