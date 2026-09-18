-- SQL MediSell
-- Khusus untuk database: db_medisell
-- Pilih database db_medisell di phpMyAdmin sebelum melakukan Import.
-- File ini TIDAK membuat database baru dan TIDAK menjalankan USE database lain.

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_user VARCHAR(100) NOT NULL,
    role ENUM('Admin','Karyawan/Kasir','Pemilik') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15),
    alamat TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_transaksi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_user INT NOT NULL,
    id_pelanggan INT NOT NULL,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transaksi_user
        FOREIGN KEY (id_user) REFERENCES users(id_user),
    CONSTRAINT fk_transaksi_pelanggan
        FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_detail_transaksi
        FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi)
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_produk
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data produk contoh
INSERT INTO produk (kode_produk, nama_produk, harga, stok)
SELECT 'P001', 'Produk A', 10000, 20
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'P001');

INSERT INTO produk (kode_produk, nama_produk, harga, stok)
SELECT 'P002', 'Produk B', 15000, 15
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'P002');

INSERT INTO produk (kode_produk, nama_produk, harga, stok)
SELECT 'P003', 'Produk C', 20000, 10
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE kode_produk = 'P003');

-- Data pelanggan contoh
INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat)
SELECT 'Pelanggan Umum', '0800000000', '-'
WHERE NOT EXISTS (SELECT 1 FROM pelanggan WHERE nama_pelanggan = 'Pelanggan Umum');

INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat)
SELECT 'Budi', '08123456789', 'Tasikmalaya'
WHERE NOT EXISTS (SELECT 1 FROM pelanggan WHERE nama_pelanggan = 'Budi');

INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat)
SELECT 'Siti', '08234567890', 'Tasikmalaya'
WHERE NOT EXISTS (SELECT 1 FROM pelanggan WHERE nama_pelanggan = 'Siti');

-- Akun demo
-- Password semua akun: password
INSERT INTO users (username, password, nama_user, role)
SELECT 'admin', '$2y$12$rRNMiwgWza1drD9dZLUtI./YeinH8r213ss4sF/M7uKoTv5ciPxs2',
       'Administrator', 'Admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

INSERT INTO users (username, password, nama_user, role)
SELECT 'kasir', '$2y$12$rRNMiwgWza1drD9dZLUtI./YeinH8r213ss4sF/M7uKoTv5ciPxs2',
       'Karyawan Kasir', 'Karyawan/Kasir'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'kasir');

INSERT INTO users (username, password, nama_user, role)
SELECT 'pemilik', '$2y$12$rRNMiwgWza1drD9dZLUtI./YeinH8r213ss4sF/M7uKoTv5ciPxs2',
       'Pemilik Usaha', 'Pemilik'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'pemilik');
