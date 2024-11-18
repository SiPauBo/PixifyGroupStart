<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bio = trim($_POST['bio']);
$social_links = trim($_POST['social_links']);
$profile_picture = $_FILES['profile_picture'];

// Set social_links to an empty JSON object if it's empty
if (empty($social_links)) {
    $social_links = '{}';
} elseif (json_decode($social_links) === null) {
    die("Invalid JSON format for social links.");
}

// Set the upload directory using an absolute path
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/Pixify/project/uploads/";
$profile_picture_name = null;

if (isset($profile_picture) && $profile_picture['size'] > 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($profile_picture['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        die("Only JPG, PNG, and GIF files are allowed.");
    }

    // Create a unique file name for the uploaded profile picture
    $profile_picture_name = "profile_" . $user_id . "_" . time() . "." . pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
    $target_file = $upload_dir . $profile_picture_name;

    // Attempt to move the uploaded file to the uploads directory
    if (!move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
        die("Failed to upload the profile picture.");
    }
}

// Update user profile in the database
$update_query = "UPDATE users SET bio = ?, profile_picture = ?, social_links = ?, first_time_login = 0 WHERE id = ?";
$stmt = $connection->prepare($update_query);
$stmt->bind_param("sssi", $bio, $profile_picture_name, $social_links, $user_id);

if ($stmt->execute()) {
    header("Location: ../pages/index.php"); // Redirect to start page after profile setup
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>
