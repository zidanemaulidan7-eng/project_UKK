<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Karyawan/Kasir']);
$page_title='Stok Produk'; $q=trim($_GET['q']??'');
$stmt=$conn->prepare("SELECT kode_produk,nama_produk,harga,stok FROM produk WHERE kode_produk LIKE CONCAT('%',?,'%') OR nama_produk LIKE CONCAT('%',?,'%') ORDER BY nama_produk");
$stmt->bind_param('ss',$q,$q); $stmt->execute(); $data=$stmt->get_result();
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Stok Produk</h1></div>
<form class="search" method="get"><input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari produk"><button class="btn">Cari</button></form>
<div class="panel"><table><thead><tr><th>Kode</th><th>Produk</th><th>Harga</th><th>Stok</th><th>Status</th></tr></thead><tbody>
<?php while($row=$data->fetch_assoc()): $status=$row['stok']<=5?'Stok Menipis':'Tersedia'; ?><tr>
<td><?= htmlspecialchars($row['kode_produk']) ?></td><td><?= htmlspecialchars($row['nama_produk']) ?></td><td>Rp <?= number_format($row['harga'],0,',','.') ?></td><td><?= $row['stok'] ?></td><td><span class="badge"><?= $status ?></span></td>
</tr><?php endwhile; ?></tbody></table></div>
<?php include '../../partials/footer.php'; ?>
