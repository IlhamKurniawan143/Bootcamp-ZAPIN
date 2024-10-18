<?php
session_start();

// Ensure the user is logged in and is either pegawai or pengajar
if (!isset($_SESSION['is_login']) || ($_SESSION['role'] != 'pegawai' && $_SESSION['role'] != 'pengajar')) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'service/database.php';

$join_message = "";

if (isset($_POST['join_class'])) {
    // Ambil id user dari session
    if (!isset($_SESSION['user_id'])) {
        die("Session ID tidak ditemukan, silakan login kembali.");
    }

    $class_code = $_POST['class_code'];
    $user_id = $_SESSION['user_id']; // Pastikan session id sudah terisi dengan benar
    $role = $_SESSION['role'];

    // Memeriksa apakah kelas ada berdasarkan kode kelas
    $sql = "SELECT id FROM classes WHERE class_code = '$class_code'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $class_data = $result->fetch_assoc();
        $class_id = $class_data['id'];

        // Pastikan user_id ada di tabel users
        $check_user_sql = "SELECT id FROM users WHERE id = '$user_id'";
        $check_user_result = $db->query($check_user_sql);

        if ($check_user_result->num_rows > 0) {
            // Cek apakah user sudah bergabung dalam kelas
            $check_sql = "SELECT * FROM class_members WHERE pegawai_id = '$user_id' AND class_id = '$class_id' AND role = '$role'";
            $check_result = $db->query($check_sql);

            if ($check_result->num_rows == 0) {
                // Menambahkan user ke tabel class_members
                $insert_sql = "INSERT INTO class_members (pegawai_id, class_id, role) VALUES ('$user_id', '$class_id', '$role')";
                if ($db->query($insert_sql)) {
                    $join_message = "Berhasil bergabung ke kelas!";
                } else {
                    $join_message = "Gagal bergabung ke kelas. Error: " . $db->error;
                }
            } else {
                $join_message = "Anda sudah tergabung di kelas ini!";
            }
        } else {
            $join_message = "User tidak ditemukan!";
        }
    } else {
        $join_message = "Kode kelas tidak valid!";
    }

    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabung Kelas</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        /* Content wrapper */
        .wrapper {
            margin-top: 100px;
            /* To offset the height of the fixed navbar */
            padding: 20px;
            transition: margin-left 0.3s ease;
            /* Smooth transition when shifting */
            margin-left: 100px;
            /* Default position */
        }

        .wrapper.shifted {
            margin-left: 500px;
            /* Shift content to the right by the width of the sidebar */
        }

        /* Styling form Gabung Kelas */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .input-group input[type="text"] {
            width: 25%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .input-group button.btn {
            background-color: #3DCAFD;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .input-group button.btn:hover {
            background-color: #32b1e0;
        }

        .content h2 {
            margin-bottom: 20px;
            color: #3DCAFD;
        }

        .content i {
            display: block;
            margin-bottom: 20px;
            color: #666;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .wrapper.shifted {
                margin-left: 400px;
                /* Shift content by smaller sidebar width */
            }
        }

        @media (max-width: 480px) {
            .wrapper.shifted {
                margin-left: 400px;
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
            <h2>Gabung Kelas</h2>
            <i><?= $join_message ?></i>
            <form action="gabung_kelas.php" method="POST">
                <div class="input-group">
                    <label for="class_code">Masukkan Kode Kelas</label>
                    <input type="text" name="class_code" id="class_code" required>
                </div>
                <div class="input-group">
                    <button type="submit" name="join_class" class="btn">Gabung Kelas</button>
                </div>
            </form>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>

</html>