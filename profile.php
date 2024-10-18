<?php
session_start();

// Verifikasi apakah pengguna telah login
if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit();
}

// Jika data sesi tertentu tidak tersedia, berikan nilai default
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Tidak diketahui';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Tidak diketahui';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Tidak diketahui';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      /* Style for the pop-up */
      .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .popup-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        max-width: 90%;
        position: relative;
      }

      .popup-content h2 {
        margin-bottom: 20px;
      }

      .popup-content .input-group {
        margin-bottom: 15px;
      }

      .popup-content .btn {
        margin-top: 15px;
      }

      .close-popup {
        background: #ff5e5e;
        color: white;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        float: right;
      }

      .btn {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      .btn:hover {
        background-color: #0056b3;
      }

      .input-group {
        flex: 1; /* Sisa ruang digunakan untuk konten */
      }

      .popup-overlay {
        z-index: 1000;
      }

      .popup-content {
        z-index: 1001;
        position: relative;
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Profile Pop-up -->
    <div id="profilePopup" class="popup-overlay">
      <div class="popup-content">
        <button class="close-popup" onclick="closePopup()">X</button>
        <h2>Profil Anda</h2>
        <div class="input-group">
          <label>Nama:</label>
          <p><?= $_SESSION['username']; ?></p>
        </div>
        <div class="input-group">
          <label>Email:</label>
          <p><?= $_SESSION['email']; ?></p>
        </div>
        <div class="input-group">
          <label>Role:</label>
          <p><?= $_SESSION['role']; ?></p>
        </div>
        <form action="logout.php" method="POST">
          <button type="submit" class="btn">Logout</button>
        </form>
      </div>
    </div>

    <script>
      function openPopup() {
        document.getElementById("profilePopup").style.display = "flex";
      }

      function closePopup() {
        document.getElementById("profilePopup").style.display = "none";
      }
    </script>
  </body>
</html>
