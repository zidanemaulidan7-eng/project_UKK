<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const BASE_URL = '/MediSell';

function wajib_login(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }
}

function role_diizinkan(array $roles): void {
    wajib_login();
    if (!in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(403);
        exit('Akses ditolak. Anda tidak memiliki hak akses ke halaman ini.');
    }
}
