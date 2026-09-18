<?php
require_once '../../config/auth.php';
require_once '../../config/koneksi.php';
role_diizinkan(['Admin']);
$page_title = 'Data Produk';

$keyword = trim($_GET['q'] ?? '');
$stmt = $conn->prepare("SELECT * FROM produk WHERE kode_produk LIKE CONCAT('%',?,'%') OR nama_produk LIKE CONCAT('%',?,'%') ORDER BY id_produk DESC");
$stmt->bind_param('ss', $keyword, $keyword);
$stmt->execute();
$data = $stmt->get_result();

include '../../partials/header.php';
include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Data Produk</h1><a class="btn primary" href="tambah.php">+ Tambah Produk</a></div>
<form class="search" method="get"><input name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari kode/nama produk"><button class="btn" type="submit">Cari</button></form>
<div class="panel">
<table>
<thead><tr><th>Kode</th><th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
<tbody>
<?php while ($row=$data->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['kode_produk']) ?></td>
<td><?= htmlspecialchars($row['nama_produk']) ?></td>
<td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
<td><?= $row['stok'] ?></td>
<td class="actions"><a class="btn small" href="edit.php?id=<?= $row['id_produk'] ?>">Edit</a><a class="btn small danger" href="hapus.php?id=<?= $row['id_produk'] ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php include '../../partials/footer.php'; ?>
