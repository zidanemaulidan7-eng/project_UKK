<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Pemilik']);
$page_title='Laporan Penjualan';
$tgl1=$_GET['tgl1']??date('Y-m-01'); $tgl2=$_GET['tgl2']??date('Y-m-d');
$stmt=$conn->prepare("SELECT COUNT(*) jumlah_transaksi, COALESCE(SUM(total),0) total_penjualan FROM transaksi WHERE DATE(tanggal_transaksi) BETWEEN ? AND ?");
$stmt->bind_param('ss',$tgl1,$tgl2); $stmt->execute(); $ringkas=$stmt->get_result()->fetch_assoc();

$stmt=$conn->prepare("SELECT DATE(tanggal_transaksi) tanggal,COUNT(*) jumlah_transaksi,SUM(total) total FROM transaksi WHERE DATE(tanggal_transaksi) BETWEEN ? AND ? GROUP BY DATE(tanggal_transaksi) ORDER BY tanggal DESC");
$stmt->bind_param('ss',$tgl1,$tgl2); $stmt->execute(); $data=$stmt->get_result();

include '../../partials/header.php'; include '../../partials/sidebar.php';
?>
<div class="topbar"><h1>Laporan Penjualan</h1></div>
<form class="filter" method="get"><label>Dari <input type="date" name="tgl1" value="<?= htmlspecialchars($tgl1) ?>"></label><label>Sampai <input type="date" name="tgl2" value="<?= htmlspecialchars($tgl2) ?>"></label><button class="btn primary">Tampilkan</button></form>
<div class="cards two"><div class="card"><span>Jumlah Transaksi</span><strong><?= $ringkas['jumlah_transaksi'] ?></strong></div><div class="card"><span>Total Penjualan</span><strong>Rp <?= number_format($ringkas['total_penjualan'],0,',','.') ?></strong></div></div>
<div class="panel"><h2>Rekap Harian</h2><table><thead><tr><th>Tanggal</th><th>Jumlah Transaksi</th><th>Total Penjualan</th></tr></thead><tbody>
<?php while($row=$data->fetch_assoc()): ?><tr><td><?= $row['tanggal'] ?></td><td><?= $row['jumlah_transaksi'] ?></td><td>Rp <?= number_format($row['total'],0,',','.') ?></td></tr><?php endwhile; ?>
</tbody></table></div>
<?php include '../../partials/footer.php'; ?>
