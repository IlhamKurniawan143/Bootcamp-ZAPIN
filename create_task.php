<?php
session_start();
include 'service/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task']) && $user_role === 'pengajar') {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $attachment_path = null;

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["attachment"]["name"]);

        // Check if the directory exists, otherwise create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);  // Create the directory with appropriate permissions
        }

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $attachment_path = $target_file;
        } else {
            echo "Gagal mengunggah lampiran.";
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

?>
