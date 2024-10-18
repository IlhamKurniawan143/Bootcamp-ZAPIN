<?php
session_start();
include 'service/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_assignment'])) {
    $task_id = $_POST['task_id'];
    $employee_id = $_SESSION['user_id']; // Assuming the employee's user_id is stored in session

    $submission_path = null;

    // Handle file upload
    if (isset($_FILES['assignment']) && $_FILES['assignment']['error'] === 0) {
        $target_dir = "submissions/";
        $target_file = $target_dir . basename($_FILES["assignment"]["name"]);

        // Check if the directory exists, otherwise create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($_FILES["assignment"]["tmp_name"], $target_file)) {
            $submission_path = $target_file;

            // Insert submission into the database
            $insert_sql = "INSERT INTO submissions (task_id, employee_id, submission_path, submitted_at) 
                           VALUES ('$task_id', '$employee_id', '$submission_path', NOW())";

            if ($db->query($insert_sql)) {
                echo "Tugas berhasil dikirim!";
            } else {
                echo "Gagal menyimpan tugas.";
            }
        } else {
            echo "Gagal mengunggah file.";
        }
    }
}
?>
