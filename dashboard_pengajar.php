<?php
session_start();

// Cek apakah user sudah login dan memiliki peran pengajar
if (!isset($_SESSION['is_login']) || $_SESSION['role'] != 'pengajar') {
    header("Location: login.php");
    exit();
}

// Ambil data kelas yang dibuat oleh pengajar ini
include 'service/database.php';
$pengajar_id = $_SESSION['user_id'];
$sql = "SELECT * FROM classes WHERE pengajar_id = '$pengajar_id'";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        /* Content wrapper */
        .wrapper {
            margin-top: 60px;
            /* To offset the height of the fixed navbar */
            padding: 20px;
            transition: margin-left 0.3s ease;
            /* Smooth transition when shifting */
            margin-left: 0;
            /* Default position */
        }

        .wrapper.shifted {
            margin-left: 250px;
            /* Shift content to the right by the width of the sidebar */
        }

        /* Style for class cards */
        .class-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .class-card {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: calc(33.333% - 40px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .class-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        .class-card p {
            font-size: 1rem;
            color: #666;
            line-height: 1.4;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .class-card {
                width: calc(50% - 40px);
            }

            .wrapper.shifted {
                margin-left: 200px;
                /* Shift content by smaller sidebar width */
            }
        }

        @media (max-width: 480px) {
            .class-card {
                width: 100%;
            }
            
            .wrapper.shifted {
                margin-left: 200px;
            }
        }
    </style>
</head>

<body>
    <!-- Sertakan Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Sertakan Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="wrapper">
        <main class="content">
            <?php if ($result->num_rows > 0): ?>
                <div class="class-gallery">
                    <?php while ($class = $result->fetch_assoc()): ?>
                        <div class="class-card">
                            <h3><?= $class['class_name'] ?></h3>
                            <p><?= $class['class_description'] ?></p>
                            <a href="class_detail.php?class_id=<?= $class['id'] ?>" class="btn">Lihat Detail</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Anda belum membuat kelas.</p>
            <?php endif; ?>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>

</html>
<?php
$db->close();
?>