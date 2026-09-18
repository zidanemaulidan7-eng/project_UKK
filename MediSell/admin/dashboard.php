<?php
require_once '../config/auth.php';
require_once '../config/koneksi.php';
wajib_login();

$page_title = 'Dashboard';

$produk = $conn->query(
    "SELECT COUNT(*) total FROM produk"
)->fetch_assoc()['total'];

$pelanggan = $conn->query(
    "SELECT COUNT(*) total FROM pelanggan"
)->fetch_assoc()['total'];

$transaksi = $conn->query(
    "SELECT COUNT(*) total FROM transaksi"
)->fetch_assoc()['total'];

$stok = $conn->query(
    "SELECT COALESCE(SUM(stok),0) total FROM produk"
)->fetch_assoc()['total'];

include '../partials/header.php';
include '../partials/sidebar.php';
?>

<div class="main-content">

    <!-- HEADER -->
    <div class="topbar">
        <div>
            <h1>Dashboard</h1>
            <p>Selamat datang di MediSell</p>
        </div>

        <div class="admin-info">
            Administrator
            <strong>Admin</strong>
        </div>
    </div>


    <!-- STATISTIK -->
    <div class="cards">

        <div class="card">
            <div class="card-title">
                Jumlah Produk
            </div>

            <strong><?= $produk ?></strong>

            <span>Produk tersedia</span>
        </div>


        <div class="card">
            <div class="card-title">
                Jumlah Pelanggan
            </div>

            <strong><?= $pelanggan ?></strong>

            <span>Pelanggan terdaftar</span>
        </div>


        <div class="card">
            <div class="card-title">
                Jumlah Transaksi
            </div>

            <strong><?= $transaksi ?></strong>

            <span>Total transaksi</span>
        </div>


        <div class="card">
            <div class="card-title">
                Total Stok
            </div>

            <strong><?= $stok ?></strong>

            <span>Stok produk</span>
        </div>

    </div>


    <!-- TRANSAKSI TERBARU -->
    <div class="panel">

        <div class="panel-header">
            <div>
                <h2>Transaksi Terbaru</h2>
                <p>Daftar transaksi terakhir</p>
            </div>

            <a href="riwayat/" class="btn">
                Lihat Semua
            </a>
        </div>


        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                <?php

                $q = $conn->query("
                    SELECT
                        t.id_transaksi,
                        t.tanggal_transaksi,
                        COALESCE(
                            p.nama_pelanggan,
                            'Umum'
                        ) pelanggan,
                        t.total

                    FROM transaksi t

                    LEFT JOIN pelanggan p
                        ON p.id_pelanggan = t.id_pelanggan

                    ORDER BY t.id_transaksi DESC

                    LIMIT 5
                ");

                if ($q->num_rows > 0):

                    while ($row = $q->fetch_assoc()):
                ?>

                    <tr>

                        <td>
                            #<?= $row['id_transaksi'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $row['tanggal_transaksi']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $row['pelanggan']
                            ) ?>
                        </td>

                        <td>
                            <strong>
                                Rp <?= number_format(
                                    $row['total'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </strong>
                        </td>

                    </tr>

                <?php
                    endwhile;

                else:
                ?>

                    <tr>
                        <td colspan="4" class="empty">
                            Belum ada transaksi.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../partials/footer.php'; ?>