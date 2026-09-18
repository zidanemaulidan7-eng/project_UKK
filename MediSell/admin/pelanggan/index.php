<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php';
role_diizinkan(['Admin','Karyawan/Kasir']);
$page_title='Data Pelanggan';
$q=trim($_GET['q'] ?? '');
$stmt=$conn->prepare("SELECT * FROM pelanggan WHERE nama_pelanggan LIKE CONCAT('%',?,'%') OR no_hp LIKE CONCAT('%',?,'%') ORDER BY id_pelanggan DESC");
$stmt->bind_param('ss',$q,$q); $stmt->execute(); $data=$stmt->get_result();
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Data Pelanggan</h1><a class="btn primary" href="tambah.php">+ Tambah Pelanggan</a></div>
<form class="search" method="get"><input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari nama/no. HP"><button class="btn">Cari</button></form>
<div class="panel"><table><thead><tr><th>ID</th><th>Nama</th><th>No. HP</th><th>Alamat</th><th>Aksi</th></tr></thead><tbody>
<?php while($row=$data->fetch_assoc()): ?><tr>
<td><?= $row['id_pelanggan'] ?></td><td><?= htmlspecialchars($row['nama_pelanggan']) ?></td><td><?= htmlspecialchars($row['no_hp']) ?></td><td><?= htmlspecialchars($row['alamat']) ?></td>
<td class="actions"><a class="btn small" href="edit.php?id=<?= $row['id_pelanggan'] ?>">Edit</a><a class="btn small danger" href="hapus.php?id=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Hapus pelanggan ini?')">Hapus</a></td>
</tr><?php endwhile; ?></tbody></table></div>
<?php include '../../partials/footer.php'; ?>
