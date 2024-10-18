<?php
include "service/database.php";
session_start();

$register_message = " ";

if (isset($_SESSION["is_login"])) {
    header("location:dashboard.php");
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $role = $_POST['role'];  // Capture role selection

    // Validate all fields are filled
    if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
        $hash_password = hash('sha256', $password);
        $security_answer = hash('sha256', $_POST['security_answer']);

        try {
            $sql = "INSERT INTO users (username, email, password, security_question, security_answer, role) VALUES ('$username', '$email', '$hash_password', '$security_question', '$security_answer', '$role')";

            if ($db->query($sql)) {
                $register_message = "daftar akun berhasil, silahkan login";
            } else {
                $register_message = "daftar akun gagal, coba lagi";
            }
        } catch (mysqli_sql_exception $e) {
            $register_message = "username sudah digunakan";
        }
        $db->close();
    } else {
        $register_message = "Semua kolom harus diisi!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAPIN-Daftar Akun</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <i><?= $register_message ?></i>

    <div class="container">
        <div class="left-side">
            <img src="img/LOGO ZAPIN.png" alt="Logo ZAPIN" class="logo logo-zapin">
        </div>
        <div class="right-side">
            <form action="daftar akun.php" method="POST" class="login-email">
                <p class="header-text" style="font-size: 3rem;">Buat Akun</p>

                <div class="input-group">
                    <input type="username" placeholder="Masukkan username" name="username" required />
                </div>
                <div class="input-group">
                    <input type="email" placeholder="Masukkan email" name="email" required />
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Masukkan password" name="password" required />
                </div>

                <div class="input-group">
                    <label for="role">Daftar Sebagai:</label>
                    <select name="role" required>
                        <option value="">Pilih Peran</option>
                        <option value="pegawai">Pegawai</option>
                        <option value="pengajar">Pengajar</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="security_question">Pertanyaan Keamanan</label>
                    <select name="security_question" required>
                        <option value="Siapa nama hewan peliharaan pertama Anda?">Siapa nama hewan peliharaan pertama Anda?</option>
                        <option value="Di mana kota kelahiran ibu Anda?">Di mana kota kelahiran ibu Anda?</option>
                        <option value="Apa makanan favorit Anda?">Apa makanan favorit Anda?</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="text" placeholder="Jawaban keamanan" name="security_answer" required>
                </div>
                <div>
                    <p><a href="forgot-password.php">Lupa password?</a></p>
                </div>
                <div class="input-group">
                    <button class="btn" type="submit" name="register">Buat</button>
                </div>
                <p class="login-register-text">Sudah punya akun? <a href="login.php">Log in</a></p>
            </form>
        </div>
    </div>
</body>

</html>