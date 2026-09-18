# MediSell — Sistem Manajemen Penjualan

Project PHP Native + MySQL + HTML + CSS + JavaScript.

## Fitur
- Login dan hak akses: Admin, Karyawan/Kasir, Pemilik
- Dashboard
- CRUD produk
- CRUD pelanggan
- Pengecekan stok
- Transaksi penjualan multi-produk
- Pengurangan stok otomatis
- Riwayat transaksi
- Laporan penjualan berdasarkan periode

## Cara menjalankan
1. Install XAMPP.
2. Salin folder `MediSell` ke `C:/xampp/htdocs/`.
3. Jalankan Apache dan MySQL dari XAMPP.
4. Buka phpMyAdmin.
5. Import file `database/medisell.sql`.
6. Pastikan database bernama `medisell`.
7. Buka `http://localhost/MediSell/`.

## Akun demo
- Admin: `admin` / `password`
- Karyawan/Kasir: `kasir` / `password`
- Pemilik: `pemilik` / `password`

## Catatan
Kode ini dibuat sebagai starter project UKK. Untuk penggunaan nyata, tambahkan CSRF protection, validasi yang lebih ketat, audit log, dan pengaturan server yang aman.
