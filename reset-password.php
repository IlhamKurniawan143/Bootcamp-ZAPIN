<?php
include 'service/database.php';
session_start();

// Pastikan pengguna sudah login atau username dikirim melalui URL
if (!isset($_SESSION['username']) && !isset($_GET['username'])) {
    echo "Anda tidak memiliki izin untuk mengakses halaman ini.";
    exit;
}

// Ambil username dari session atau URL
$username = isset($_SESSION['username']) ? $_SESSION['username'] : $_GET['username'];

if (isset($_POST['reset_password'])) {
    // Validasi apakah password dan konfirmasi password sama
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok.";
    } else {
        // Hash password baru
        $hash_password = hash('sha256', $new_password);

        // Update password di database
        $sql = "UPDATE users SET password='$hash_password' WHERE username='$username'";

        if ($db->query($sql) === TRUE) {
            echo "Password berhasil diubah!";
            header("Location: login.php");
            exit(); // Tambahkan exit untuk menghentikan eksekusi setelah redirect
        } else {
            echo "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            justify-content: center;
            width: 35%;
            height: 40%;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            flex-direction: column;
            padding: 40px;
        }

        .header-text {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            margin: 15px 0;
            width: 100%;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 40%;
            padding: 15px;
            background-color: #3498db;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .login-register-text {
            text-align: center;
            margin-top: 20px;
        }

        .login-register-text a {
            color: #3498db;
            text-decoration: none;
        }

        .login-register-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="header-text">Reset Password</h1>
        <form action="reset-password.php?username=<?= $username ?>" method="POST">
            <div class="input-group">
                <input type="password" placeholder="Masukkan password baru" name="new_password" required>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Konfirmasi password baru" required>
            </div>
            <div class="input-group">
                <button type="submit" name="reset_password" class="btn">Reset Password</button>
            </div>
            <p class="login-register-text"><a href="login.php">Kembali ke login</a></p>
        </form>
    </div>
</body>

</html>
