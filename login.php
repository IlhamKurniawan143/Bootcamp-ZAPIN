<?php
include "service/database.php";
session_start();

$login_message = " ";

if (isset($_SESSION["is_login"])) {
    if ($_SESSION["role"] == 'pegawai') {
        header("Location: dashboard_pegawai.php");
    } elseif ($_SESSION["role"] == 'pengajar') {
        header("Location: dashboard_pengajar.php");
    }
    exit();
}

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hash_password = hash('sha256', $password);

    // Query untuk memeriksa apakah pengguna ada dengan username, email, dan password yang cocok
    $sql = "SELECT * FROM users WHERE username='$username' AND email='$email' AND password='$hash_password'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Menyimpan data pengguna dalam session
        $data = $result->fetch_assoc();
        
        $_SESSION["user_id"] = $data["id"];  // Menyimpan user_id
        $_SESSION["username"] = $data["username"];
        $_SESSION['email'] = $email; // Simpan email pengguna
        $_SESSION["role"] = $data["role"];
        $_SESSION["is_login"] = true;

        // Arahkan pengguna berdasarkan peran (role)
        if ($data['role'] == 'pegawai') {
            header("Location: dashboard_pegawai.php");
        } elseif ($data['role'] == 'pengajar') {
            header("Location: dashboard_pengajar.php");
        } else {
            $login_message = "Role tidak valid!";
        }
    } else {
        $login_message = "Akun tidak ditemukan";
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAPIN-Masuk Akun</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <I><?= $login_message ?></I>
    <div class="container">
        <div class="left-side">
            <!-- Menambahkan logo-logo di atas gambar utama -->
            <img src="img/LOGO ZAPIN.png" alt="Logo ZAPIN" class="logo logo-zapin">
        </div>
        <div class="right-side">
            <form action="login.php" method="POST" class="login-email">
                <p class="header-text" style="font-size: 3rem;">Masuk Akun</p>
                <div class="input-group">
                    <input type="username" placeholder="Masukkan username" name="username" />
                </div>
                <div class="input-group">
                    <input type="email" placeholder="Masukkan email" name="email" />
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Masukkan password" name="password" />
                </div>
                <p class="login-register-text">
                    <a href="forgot-password.php">Lupa Password?</a>
                </p>
                <div class="input-group">
                    <button class="btn" type="submit" name="Login">Masuk</button>
                </div>
                <p class="login-register-tex">Belum punya akun? <a href="daftar akun.php">Daftar</a></p>
            </form>
        </div>
    </div>
</body>
</html>