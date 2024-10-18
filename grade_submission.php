<?php
session_start();
include 'service/database.php';

// Check if the form was submitted and the necessary data is available
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'], $_POST['grade'])) {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];

    // Update the submission with the grade
    $query = "UPDATE submissions SET grade = '$grade' WHERE id = '$submission_id'";
    
    if ($db->query($query)) {
        // Retrieve the task ID to redirect properly
        $query_task = "SELECT task_id FROM submissions WHERE id = '$submission_id'";
        $result_task = $db->query($query_task);

        if ($result_task && $result_task->num_rows > 0) {
            $task_data = $result_task->fetch_assoc();
            $task_id = $task_data['task_id'];

            // Redirect back to the task detail page with the correct task ID
            header("Location: task_detail.php?task_id=$task_id");
            exit;
        } else {
            echo "Tugas tidak ditemukan!";
            exit;
        }
    } else {
        echo "Gagal memperbarui nilai.";
    }
} else {
    echo "ID tugas atau nilai tidak valid!";
    exit;
}
?>
