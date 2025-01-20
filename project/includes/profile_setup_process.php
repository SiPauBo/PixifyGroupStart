<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bio = htmlspecialchars($_POST['bio']);
$twitter = htmlspecialchars($_POST['twitter']);
$instagram = htmlspecialchars($_POST['instagram']);

// Prepare social links in JSON format
$social_links = json_encode([
    'twitter' => !empty($twitter) ? "https://twitter.com/$twitter" : "",
    'instagram' => !empty($instagram) ? "https://instagram.com/$instagram" : ""
]);

// Handle profile picture upload
if (!empty($_FILES['profile_picture']['name'])) {
    $targetDir = "../uploads/";
    $imageFileName = basename($_FILES['profile_picture']['name']);
    $targetFilePath = $targetDir . $imageFileName;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
        $profile_picture = $imageFileName;
    } else {
        $profile_picture = null;
    }
}

// Update profile information in database
$updateQuery = "UPDATE users SET bio = ?, profile_picture = ?, social_links = ? WHERE id = ?";
$stmt = $connection->prepare($updateQuery);
$stmt->bind_param("sssi", $bio, $profile_picture, $social_links, $user_id);

if ($stmt->execute()) {
    header("Location: ../pages/userpage.php?success=Profile updated successfully.");
    exit();
} else {
    echo "Error updating profile: " . $connection->error;
}

$stmt->close();
$connection->close();
?>
