<?php
session_start();

require_once '../config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        $error = 'Username dan password wajib diisi.';

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header('Location: ../admin/dashboard.php');
                exit;

            } else {

                $error = 'Username atau password salah.';
            }

        } else {

            $error = 'Username atau password salah.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - MediSell</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>

<body class="login-page">

    <div class="login-container">

        <div class="login-box">

            <div class="login-logo">
                MediSell
            </div>

            <div class="login-subtitle">
                Sistem Penjualan
            </div>

            <h1>Login</h1>

            <?php if ($error): ?>

                <div class="alert">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <button type="submit"
                        class="login-button">

                    LOGIN

                </button>

            </form>

            <p class="login-info">
                Silakan login dengan akun yang sudah dibuat.
            </p>

        </div>

    </div>

</body>

</html>