<?php
include 'service/database.php';

$message = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $security_answer = hash('sha256', $_POST['security_answer']);
    
    // Cek apakah username dan jawaban keamanan cocok
    $sql = "SELECT * FROM users WHERE username='$username' AND security_answer='$security_answer'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        // Beri izin untuk mereset password
        header("Location: reset-password.php?username=$username");
    } else {
        $message = "Jawaban keamanan tidak cocok atau username salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
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
            width: 70%;
            height: 60%;
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
            width: 100%;
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
    <form action="forgot-password.php" method="POST">
        <div class="container">
            <h2>Lupa Password</h2>
            <div class="input-group">
                <input type="text" placeholder="Masukkan username" name="username" required>
            </div>
            <div class="input-group">
                <label for="security_question">Pilih Pertanyaan Keamanan</label>
                <select name="security_question" required>
                    <option value="Siapa nama hewan peliharaan pertama Anda?">Siapa nama hewan peliharaan pertama Anda?</option>
                    <option value="Di mana kota kelahiran ibu Anda?">Di mana kota kelahiran ibu Anda?</option>
                    <option value="Apa makanan favorit Anda?">Apa makanan favorit Anda?</option>
                </select>
            </div>
            <div class="input-group">
                <input type="text" placeholder="Jawaban keamanan" name="security_answer" required>
            </div>
            <div class="input-group">
                <button class="btn" href="login.php" onclick="window.location.href='login.php'">Kembali</button>
                <button class="btn" type="submit" name="submit">Verifikasi</button>
            </div>
        </div>
    </form>
    <p><?= $message ?></p>
</body>
</html>
