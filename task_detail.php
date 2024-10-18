<?php
session_start();
include 'service/database.php';

// Check if user role is set in session
if (isset($_SESSION['role'])) {
    $user_role = $_SESSION['role'];
} else {
    // Handle the case where the user role is not set, e.g., redirect to login
    echo "User role not found. Please log in.";
    exit;
}

// Check if the task ID is set in the URL
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Fetch task details from the database
    $query = "SELECT * FROM class_tasks WHERE id = '$task_id'";
    $result = $db->query($query);

    if ($result && $result->num_rows > 0) {
        $task_data = $result->fetch_assoc(); // Get the task details
    } else {
        echo "Tugas tidak ditemukan!";
        exit;
    }
} else {
    echo "ID tugas tidak ditemukan!";
    exit;
}

// Fetch employee's submission and grade (if user is 'pegawai')
if ($user_role === 'pegawai') {
    $employee_id = $_SESSION['user_id']; // Assuming you store the employee's ID in the session
    $submission_query = "SELECT * FROM submissions WHERE task_id = '$task_id' AND employee_id = '$employee_id'";
    $submission_result = $db->query($submission_query);

    if ($submission_result && $submission_result->num_rows > 0) {
        $submission_data = $submission_result->fetch_assoc();
    } else {
        echo "Anda belum mengirimkan tugas.";
        $submission_data = null;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas</title>
    <link rel="stylesheet" href="navbar-sidebar.css">
    <style>
        /* styles.css */

        /* Reset margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Wrapper */
        .wrapper {
            margin-top: 60px;
            margin-left: 250px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Content Area */
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content h2,
        .content h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .content p {
            color: #555;
            margin-bottom: 15px;
        }

        /* Table for Task Submissions */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3DCAFD;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Input Groups */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .input-group input[type="file"],
        .input-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Submit Button */
        .btn {
            background-color: #3DCAFD;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #32b1e0;
        }

        /* Style for Links */
        a {
            color: #3DCAFD;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .wrapper {
                padding: 15px;
            }

            .content {
                padding: 15px;
            }

            .input-group input[type="file"],
            .input-group input[type="text"] {
                padding: 8px;
            }

            .btn {
                padding: 8px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar and Sidebar -->
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="wrapper">
        <main class="content">
            <h2>Detail Tugas: <?= $task_data['task_name'] ?></h2>
            <p><strong>Deskripsi:</strong> <?= $task_data['task_description'] ?></p>
            <?php if ($task_data['attachment_path']): ?>
                <p><strong>Lampiran:</strong> <a href="<?= $task_data['attachment_path'] ?>" target="_blank">Lihat Lampiran</a></p>
            <?php endif; ?>

            <?php if ($user_role === 'pegawai' && $submission_data): ?>
                <h3>Pengiriman Tugas Anda:</h3>
                <p><strong>Status Pengiriman:</strong> Terkirim</p>

                <!-- Check and Display Employee's Submitted Attachment -->
                <?php if (isset($submission_data['submission_path']) && $submission_data['submission_path']): ?>
                    <p><strong>Lampiran Pengiriman:</strong> <a href="<?= $submission_data['submission_path'] ?>" target="_blank">Lihat Lampiran</a></p>
                <?php else: ?>
                    <p><strong>Lampiran Pengiriman:</strong> Tidak ada lampiran</p>
                <?php endif; ?>

                <?php if ($submission_data['grade']): ?>
                    <p><strong>Nilai:</strong> <?= $submission_data['grade'] ?></p>
                <?php else: ?>
                    <p><strong>Nilai:</strong> Belum dinilai</p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($user_role === 'pengajar'): ?>
                <!-- Show some actions specific to teachers, like editing or deleting the task -->
                <a href="edit_task.php?task_id=<?= $task_data['id'] ?>">Edit Tugas</a>
            <?php endif; ?>
            <?php if ($user_role === 'pegawai'): ?>
                <h3>Submit Tugas Anda</h3>
                <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="task_id" value="<?= $task_data['id'] ?>">
                    <div class="input-group">
                        <label for="assignment">Lampiran Tugas:</label>
                        <input type="file" name="assignment" id="assignment" required>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="submit_assignment" class="btn">Kirim Tugas</button>
                    </div>
                </form>
            <?php endif; ?>
            <?php if ($user_role === 'pengajar'): ?>
                <h3>Tugas yang Dikumpulkan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Lampiran Tugas</th>
                            <th>Tanggal Dikirim</th>
                            <th>Nilai</th>
                            <th>Berikan Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_submissions = "SELECT submissions.*, users.username AS employee_name 
                                  FROM submissions 
                                  JOIN users ON submissions.employee_id = users.id 
                                  WHERE task_id = '$task_id'";
                        $result_submissions = $db->query($query_submissions);

                        if ($result_submissions->num_rows > 0):
                            while ($submission = $result_submissions->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $submission['employee_name'] ?></td>
                                    <td><a href="<?= $submission['submission_path'] ?>" target="_blank">Lihat Tugas</a></td>
                                    <td><?= $submission['submitted_at'] ?></td>
                                    <td><?= $submission['grade'] ? $submission['grade'] : 'Belum dinilai' ?></td>
                                    <td>
                                        <form action="grade_submission.php" method="POST">
                                            <input type="hidden" name="submission_id" value="<?= $submission['id'] ?>">
                                            <input type="text" name="grade" placeholder="Masukkan nilai" required>
                                            <button type="submit" name="submit_grade" class="btn">Simpan Nilai</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Belum ada tugas yang dikumpulkan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </div>
    <script src="scripts.js"></script>
</body>

</html>