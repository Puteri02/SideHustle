<?php
session_start();
include("connection.php");

// Debugging statements
var_dump($_POST);
var_dump($_FILES);

// Ensure only logged-in users can access the dashboard
if (!isset($_SESSION['User_ID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['User_ID'];

if (!isset($_POST['job-id']) || empty($_POST['job-id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Job ID not provided.']);
    exit;
}

$job_id = $_POST['job-id'];
$name = $_POST['full-name'];
$email = $_POST['email'];
$resume = $_FILES['resume']['name'];
$resume_temp = $_FILES['resume']['tmp_name'];

// Check if the user has already applied for this job
$check_query = "SELECT * FROM job_applications WHERE Job_ID = ? AND User_ID = ?";
$check_stmt = $con->prepare($check_query);
$check_stmt->bind_param("ii", $job_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already applied for this job.']);
    exit;
}

$target_dir = "uploads/"; // Ensure this directory is writable by the web server
$target_file = $target_dir . basename($resume);

if (move_uploaded_file($resume_temp, $target_file)) {
    $insert_query = "INSERT INTO job_applications (Job_ID, User_ID, Resume, Applied_At) VALUES (?, ?, ?, NOW())";
    $insert_stmt = $con->prepare($insert_query);
    $insert_stmt->bind_param("iis", $job_id, $user_id, $target_file);

    if ($insert_stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully.']);
    } else {
        error_log('Error applying for the job: ' . htmlspecialchars($insert_stmt->error));
        echo json_encode(['status' => 'error', 'message' => 'Error applying for the job.']);
    }

    $insert_stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error uploading resume.']);
}

$con->close();
?>
