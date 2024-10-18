<?php
// Make sure the user is logged in and is a pengajar
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['role'] != 'pengajar') {
    header("Location: login.php");
    exit();
}

// Handle class creation logic
include 'service/database.php';

$create_message = "";
if (isset($_POST['create'])) {
    $class_name = $_POST['class_name'];
    $class_description = $_POST['class_description'];
    $pengajar_id = $_SESSION['user_id'];

    // Generate a unique class code
    $class_code = substr(md5(uniqid(rand(), true)), 0, 8); // Generates an 8-character unique code

    if (!empty($class_name) && !empty($class_description)) {
        $sql = "INSERT INTO classes (class_name, class_description, pengajar_id, class_code) 
                VALUES ('$class_name', '$class_description', '$pengajar_id', '$class_code')";
        
        if ($db->query($sql)) {
            $create_message = "Kelas berhasil dibuat! Kode Kelas: $class_code";
        } else {
            $create_message = "Gagal membuat kelas.";
        }
    } else {
        $create_message = "Nama kelas dan deskripsi harus diisi!";
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kelas</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Basic page styles */
        body {
            display: flex;
            height: 100vh;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 2rem;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 1.5rem 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 0.5rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #1abc9c;
        }

        /* Main Content */
        .wrapper {
            margin-left: 250px;
            /* Adjust based on sidebar width */
            padding: 2rem;
            flex-grow: 1;
        }

        .content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Styling */
        input[type="text"],
        textarea {
            width: 100%;
            padding: 1rem;
            margin: 0.5rem 0 1.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #1abc9c;
            outline: none;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        /* Button Styling */
        button.btn {
            padding: 1rem 2rem;
            background-color: #1abc9c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button.btn:hover {
            background-color: #16a085;
        }

        /* Message Style */
        i {
            color: red;
            margin-bottom: 1rem;
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .wrapper {
                margin-left: 200px;
                padding: 1rem;
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
            <h2>Buat Kelas Baru</h2>
            <i><?= $create_message ?></i>
            <form action="buat_kelas.php" method="POST">
                <div class="input-group">
                    <label for="class_name">Nama Kelas</label>
                    <input type="text" name="class_name" id="class_name" required>
                </div>
                <div class="input-group">
                    <label for="class_description">Deskripsi Kelas</label>
                    <textarea name="class_description" id="class_description" required></textarea>
                </div>
                <div class="input-group">
                    <button type="submit" name="create" class="btn">Buat</button>
                    <button type="button" class="btn" onclick="window.location.href='dashboard_pengajar.php'">Batal</button>
                </div>
            </form>
        </main>
    </div>
</body>

</html>