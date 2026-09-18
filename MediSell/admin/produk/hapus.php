<?php
require_once '../../config/auth.php';
require_once '../../config/koneksi.php';
role_diizinkan(['Admin']);
$id=(int)($_GET['id'] ?? 0);
$stmt=$conn->prepare("DELETE FROM produk WHERE id_produk=?");
$stmt->bind_param('i',$id); $stmt->execute();
header('Location: index.php'); exit;
