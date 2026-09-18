<?php
require_once '../../config/auth.php'; require_once '../../config/koneksi.php'; role_diizinkan(['Admin','Karyawan/Kasir']);
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?"); $stmt->bind_param('i',$id); $stmt->execute();
header('Location: index.php'); exit;
