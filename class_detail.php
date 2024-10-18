<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['is_login'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'service/database.php';

$class_id = $_GET['class_id'] ?? null;
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

if ($class_id) {
    // Fetch class details
    $sql = "SELECT class_name, class_description, pengajar_id FROM classes WHERE id = '$class_id'";
    $result = $db->query($sql);
    $class_data = $result->fetch_assoc();

    // Fetch members of the class
    $members_sql = "SELECT u.username, u.role FROM users u 
                    JOIN class_members cm ON u.id = cm.pegawai_id 
                    WHERE cm.class_id = '$class_id'";
    $members_result = $db->query($members_sql);

    // Fetch tasks of the class
    $tasks_sql = "SELECT * FROM class_tasks WHERE class_id = '$class_id'";
    $tasks_result = $db->query($tasks_sql);

    // Handle task creation if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task']) && $user_role === 'pengajar') {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $attachment_path = null;

        // Handle file upload
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["attachment"]["name"]);
            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
                $attachment_path = $target_file;
            }
        }

        // Insert task into the database
        $insert_sql = "INSERT INTO class_tasks (class_id, task_name, task_description, attachment_path) 
                       VALUES ('$class_id', '$task_name', '$task_description', '$attachment_path')";

        if ($db->query($insert_sql)) {
            echo "Tugas berhasil dibuat!";
            header("Refresh:0"); // Refresh the page to display the newly created task
        } else {
            echo "Gagal membuat tugas.";
        }
    }
} else {
    // Redirect if no class_id is provided
    header("Location: dashboard_$user_role.php");
    exit();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .wrapper {
            margin-left: 225px;
            margin-top: 50px;
            padding: 20px;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 1.2rem;
            color: #666;
        }

        /* Styling for the tabs */
        .tabs {
            margin-top: 20px;
        }

        .tab-link {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 1rem;
        }

        .tab-link:hover {
            background-color: #555;
        }

        #tasks {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #tasks h3 {
            color: #333;
            margin-bottom: 15px;
        }

        #tasks ul {
            list-style-type: none;
            padding-left: 0;
        }

        #tasks ul li {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        #tasks ul li:hover {
            background-color: #f1f1f1;
        }

        #tasks ul li a {
          
            text-decoration: none;
            font-weight: bold;
        }

        #tasks ul li a:hover {
            text-decoration: underline;
        }

        #tasks ul li .btn {
            display: inline-block;
            margin-top: 10px;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        #tasks ul li .btn:hover {
            background-color: #32b1e0;
        }

        .tab-content {
            display: none;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }

        /* Default open content styling */
        .tab-content:first-child {
            display: block;
        }

        /* Members and tasks list styling */
        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 1rem;
            color: #333;
        }

        /* Hover effect on list items */
        ul li:hover {
            background-color: #f9f9f9;
        }

        /* Create Task Tab Content */
        #create_task {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Header */
        #create_task h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Input Group Styles */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .input-group input[type="text"],
        .input-group textarea,
        .input-group input[type="file"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        /* Change input border color on focus */
        .input-group input[type="text"]:focus,
        .input-group textarea:focus,
        .input-group input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Mobile responsiveness */
        @media screen and (max-width: 768px) {
            .wrapper {
                margin-left: 0;
                padding: 10px;
            }

            .tab-link {
                font-size: 0.9rem;
                padding: 8px 15px;
            }

            h2 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }

            #create_task {
                padding: 15px;
            }

            #create_task h3 {
                font-size: 22px;
            }

            .btn {
                width: 50%;
                padding: 12px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar and Sidebar -->
    <?php include 'navbar.php'; ?>
    <?php require 'sidebar.php'; ?>

    <!-- sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul>
            <li><a href="dashboard_pegawai.php">Beranda</a></li>
            <li><a href="gabung_kelas.php">Gabung Kelas</a></li>
            <li><a href="daftar_kelas.php">Daftar Kelas</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
    </aside>

    <div class="wrapper">
        <main class="content">
            <h2>Detail Kelas: <?= $class_data['class_name'] ?></h2>
            <p><strong>Deskripsi:</strong> <?= $class_data['class_description'] ?></p>

            <!-- Tabs for Members and Tasks -->
            <div class="tabs">
                <button class="tab-link" onclick="openTab('members')">Anggota</button>
                <button class="tab-link" onclick="openTab('tasks')">Tugas Kelas</button>
                <?php if ($user_role === 'pengajar'): ?>
                    <button class="tab-link" onclick="openTab('create_task')">Buat Tugas</button>
                <?php endif; ?>
            </div>

            <!-- Members Section -->
            <div id="members" class="tab-content">
                <h3>Anggota Kelas</h3>
                <ul>
                    <?php while ($member = $members_result->fetch_assoc()): ?>
                        <li><?= $member['username'] ?> - <?= $member['role'] ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Tasks Section -->
            <div id="tasks" class="tab-content">
                <h3>Tugas Kelas</h3>
                <ul>
                    <?php while ($task = $tasks_result->fetch_assoc()): ?>
                        <li>
                            <a href="task_detail.php?task_id=<?= $task['id'] ?>"><?= $task['task_name'] ?></a> - <?= $task['task_description'] ?>
                            <?php if ($task['attachment_path']): ?>
                                <br><a href="<?= $task['attachment_path'] ?>" target="_blank">Lihat Lampiran</a>
                            <?php endif; ?>
                            <br><a href="edit_task.php?task_id=<?= $task['id'] ?>" class="btn">Edit</a> <!-- Edit Link -->
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <!-- Task Creation Form for Teachers -->
            <?php if ($user_role === 'pengajar'): ?>
                <div id="create_task" class="tab-content">
                    <h3>Buat Tugas Baru</h3>
                    <form action="class_detail.php?class_id=<?= $class_id ?>" method="POST" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="task_name">Nama Tugas:</label>
                            <input type="text" name="task_name" id="task_name" required>
                        </div>
                        <div class="input-group">
                            <label for="task_description">Deskripsi Tugas:</label>
                            <textarea name="task_description" id="task_description" required></textarea>
                        </div>
                        <div class="input-group">
                            <label for="attachment">Lampiran:</label>
                            <input type="file" name="attachment" id="attachment">
                        </div>
                        <div class="input-group">
                            <button type="submit" name="create_task" class="btn">Buat Tugas</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function openTab(tabName) {
            var i;
            var x = document.getElementsByClassName("tab-content");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            document.getElementById(tabName).style.display = "block";
        }

        // Open the members tab by default
        openTab('members');
    </script>
    <script src="scripts.js"></script>
</body>

</html>