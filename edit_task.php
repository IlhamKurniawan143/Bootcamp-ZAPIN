<?php
session_start();
include 'service/database.php';

// Ensure that the user is logged in and the role is set in the session
if (isset($_SESSION['role'])) {
    $user_role = $_SESSION['role'];
} else {
    echo "Anda harus login untuk mengedit tugas.";
    exit;
}

// Check if the user is a pengajar and if task_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['task_id']) && $user_role === 'pengajar') {
    $task_id = $_GET['task_id'];

    // Fetch current task data
    $query = "SELECT * FROM class_tasks WHERE id = '$task_id'";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $task_data = $result->fetch_assoc();
    } else {
        echo "Tugas tidak ditemukan!";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task']) && $user_role === 'pengajar') {
    // Handle task update
    $task_id = $_POST['task_id'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $attachment_path = null;

    // Handle file upload if there is a new attachment
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);

        // Check if directory exists, otherwise create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $attachment_path = $target_file;
        } else {
            echo "Gagal mengunggah lampiran.";
        }
    }

    // Update the task in the database
    if ($attachment_path) {
        // If there is a new attachment
        $update_sql = "UPDATE class_tasks 
                       SET task_name = '$task_name', task_description = '$task_description', attachment_path = '$attachment_path'
                       WHERE id = '$task_id'";
    } else {
        // If no new attachment is uploaded
        $update_sql = "UPDATE class_tasks 
                       SET task_name = '$task_name', task_description = '$task_description'
                       WHERE id = '$task_id'";
    }

    if ($db->query($update_sql)) {
        echo "Tugas berhasil diperbarui!";
        header("Location: class_detail.php?class_id=" . $task_data['class_id']);
        exit;
    } else {
        echo "Gagal memperbarui tugas.";
    }
} else {
    echo "ID tugas tidak ditemukan atau Anda tidak memiliki izin!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        /* styles.css */

        /* Body and Wrapper */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .wrapper {
            display: flex;
            min-height: 100px;
            width: 50%;
            margin-top: 60px;
            margin-left: 250px;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            border-radius: 10px;
        }

        /* Form Styling */
        form {
            max-width: 600px;
            margin: 0 auto;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        button.btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button.btn:hover {
            background-color: #218838;
        }

        /* Current Attachment Styling */
        p {
            margin: 10px 0;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Navbar and Sidebar -->
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="wrapper">
        <main class="content">
            <h2>Edit Tugas: <?= $task_data['task_name'] ?></h2>
            <form action="edit_task.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="task_id" value="<?= $task_id ?>">

                <div class="input-group">
                    <label for="task_name">Nama Tugas:</label>
                    <input type="text" name="task_name" id="task_name" value="<?= $task_data['task_name'] ?>" required>
                </div>
                <div class="input-group">
                    <label for="task_description">Deskripsi Tugas:</label>
                    <textarea name="task_description" id="task_description" required><?= $task_data['task_description'] ?></textarea>
                </div>
                <div class="input-group">
                    <label for="attachment">Lampiran:</label>
                    <input type="file" name="attachment" id="attachment">
                    <?php if ($task_data['attachment_path']): ?>
                        <p>Lampiran saat ini: <a href="<?= $task_data['attachment_path'] ?>" target="_blank">Lihat Lampiran</a></p>
                    <?php endif; ?>
                </div>
                <div class="input-group">
                    <button type="submit" name="update_task" class="btn">Perbarui Tugas</button>
                </div>
            </form>
        </main>
    </div>
    <script src="scripts.js"></script>
</body>

</html>