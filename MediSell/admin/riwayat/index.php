<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Karyawan/Kasir']);
$page_title='Riwayat Transaksi';
$tgl1=$_GET['tgl1']??''; $tgl2=$_GET['tgl2']??'';
$sql="SELECT t.id_transaksi,t.tanggal_transaksi,COALESCE(p.nama_pelanggan,'Umum') pelanggan,t.total,u.nama_user
      FROM transaksi t LEFT JOIN pelanggan p ON p.id_pelanggan=t.id_pelanggan
      JOIN users u ON u.id_user=t.id_user WHERE 1=1";
$params=[];$types='';
if($tgl1){$sql.=" AND DATE(t.tanggal_transaksi)>=?";$params[]=$tgl1;$types.='s';}
if($tgl2){$sql.=" AND DATE(t.tanggal_transaksi)<=?";$params[]=$tgl2;$types.='s';}
$sql.=" ORDER BY t.id_transaksi DESC";
$stmt=$conn->prepare($sql); if($params)$stmt->bind_param($types,...$params); $stmt->execute(); $data=$stmt->get_result();
include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Riwayat Transaksi</h1></div>
<?php if(isset($_GET['success'])): ?><div class="alert success">Transaksi berhasil disimpan.</div><?php endif; ?>
<form class="filter" method="get"><label>Dari <input type="date" name="tgl1" value="<?= htmlspecialchars($tgl1) ?>"></label><label>Sampai <input type="date" name="tgl2" value="<?= htmlspecialchars($tgl2) ?>"></label><button class="btn primary">Filter</button></form>
<div class="panel"><table><thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Kasir</th><th>Total</th></tr></thead><tbody>
<?php while($row=$data->fetch_assoc()): ?><tr><td>#<?= $row['id_transaksi'] ?></td><td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td><td><?= htmlspecialchars($row['pelanggan']) ?></td><td><?= htmlspecialchars($row['nama_user']) ?></td><td>Rp <?= number_format($row['total'],0,',','.') ?></td></tr><?php endwhile; ?>
</tbody></table></div>
<?php include '../../partials/footer.php'; ?>
