<?php
session_start();

// Cek apakah user sudah login dan memiliki peran pegawai
if (!isset($_SESSION['is_login']) || $_SESSION['role'] != 'pegawai') {
    header("Location: login.php");
    exit();
}

include 'service/database.php';

$pegawai_id = $_SESSION['id'];
$sql = "SELECT classes.* FROM classes 
        JOIN class_members ON classes.id = class_members.class_id 
        WHERE class_members.pegawai_id = '$pegawai_id'";
$result = $db->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .class-box {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .class-box h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .class-box p {
            font-size: 1rem;
            color: #666;
        }
    </style>
</head>

<body>
    <!-- Sertakan Navbar -->
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="wrapper">
        <main class="content">
            <h2>Daftar Kelas Anda</h2>
            <div class="class-grid">
                <?php if (count($classes) > 0): ?>
                    <?php foreach ($classes as $class): ?>
                        <div class="class-box">
                            <h3><?= $class['class_name'] ?></h3>
                            <p><?= $class['class_description'] ?></p>
                            <a href="class_detail.php?class_id=<?= $class['id'] ?>" class="btn">Lihat Detail</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Anda belum bergabung ke kelas mana pun.</p>
                <?php endif; ?>
            </div>

        </main>
    </div>
    
    <script src="scripts.js"></script>

</body>

</html>