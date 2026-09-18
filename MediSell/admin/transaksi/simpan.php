<?php
require_once '../../config/auth.php';
require_once '../../config/koneksi.php';
role_diizinkan(['Karyawan/Kasir']);

$id_pelanggan = (int)($_POST['id_pelanggan'] ?? 0);
$ids = $_POST['id_produk'] ?? [];
$qtys = $_POST['jumlah'] ?? [];

if ($id_pelanggan <= 0 || !is_array($ids) || !is_array($qtys) || count($ids) === 0 || count($ids) !== count($qtys)) {
    exit('Data transaksi tidak lengkap.');
}

$conn->begin_transaction();

try {
    // Gabungkan produk yang sama agar stok tidak dikurangi dua kali secara tidak semestinya.
    $requested = [];
    foreach ($ids as $i => $id) {
        $id = (int)$id;
        $qty = (int)($qtys[$i] ?? 0);
        if ($id <= 0 || $qty <= 0) {
            throw new Exception('Produk atau jumlah tidak valid.');
        }
        $requested[$id] = ($requested[$id] ?? 0) + $qty;
    }

    // Kunci baris produk selama transaksi sehingga pengecekan stok aman.
    $check = $conn->prepare('SELECT id_produk, nama_produk, harga, stok FROM produk WHERE id_produk = ? FOR UPDATE');
    $items = [];
    $total = 0;

    foreach ($requested as $id_produk => $jumlah) {
        $check->bind_param('i', $id_produk);
        $check->execute();
        $produk = $check->get_result()->fetch_assoc();

        if (!$produk) {
            throw new Exception('Produk dengan ID ' . $id_produk . ' tidak ditemukan.');
        }
        if ($jumlah > (int)$produk['stok']) {
            throw new Exception('Stok produk "' . $produk['nama_produk'] . '" tidak mencukupi.');
        }

        $harga = (float)$produk['harga'];
        $subtotal = $harga * $jumlah;
        $total += $subtotal;

        $items[] = [
            'id_produk' => $id_produk,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'subtotal' => $subtotal
        ];
    }

    $id_user = (int)$_SESSION['user']['id_user'];

    $stmt = $conn->prepare(
        'INSERT INTO transaksi (tanggal_transaksi, id_user, id_pelanggan, total)
         VALUES (NOW(), ?, ?, ?)'
    );
    $stmt->bind_param('iid', $id_user, $id_pelanggan, $total);
    $stmt->execute();
    $id_transaksi = $conn->insert_id;

    $detail = $conn->prepare(
        'INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga, subtotal)
         VALUES (?, ?, ?, ?, ?)'
    );
    $update = $conn->prepare('UPDATE produk SET stok = stok - ? WHERE id_produk = ?');

    foreach ($items as $item) {
        $detail->bind_param(
            'iiidd',
            $id_transaksi,
            $item['id_produk'],
            $item['jumlah'],
            $item['harga'],
            $item['subtotal']
        );
        $detail->execute();

        $update->bind_param('ii', $item['jumlah'], $item['id_produk']);
        $update->execute();
    }

    $conn->commit();
    header('Location: ../riwayat/index.php?success=1');
    exit;
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(400);
    exit('Transaksi gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
