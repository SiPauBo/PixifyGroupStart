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
$twitter = trim($_POST['twitter']);
$instagram = trim($_POST['instagram']);
$existing_profile_picture = $_POST['existing_profile_picture'];

// Process social media links
$social_links = [
    'twitter' => (!empty($twitter) && !str_starts_with($twitter, 'http')) ? "https://twitter.com/$twitter" : $twitter,
    'instagram' => (!empty($instagram) && !str_starts_with($instagram, 'http')) ? "https://instagram.com/$instagram" : $instagram
];
$social_links_json = json_encode($social_links);

// Handle profile picture upload
if (!empty($_FILES['profile_picture']['name'])) {
    $targetDir = "../uploads/";
    $imageFileName = basename($_FILES['profile_picture']['name']);
    $targetFilePath = $targetDir . $imageFileName;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
        $profile_picture = $imageFileName;
    } else {
        $profile_picture = $existing_profile_picture;
    }
} else {
    $profile_picture = $existing_profile_picture;
}

// Update profile information in database
$updateQuery = "UPDATE users SET bio = ?, profile_picture = ?, social_links = ? WHERE id = ?";
$stmt = $connection->prepare($updateQuery);
$stmt->bind_param("sssi", $bio, $profile_picture, $social_links_json, $user_id);

if ($stmt->execute()) {
    header("Location: ../pages/userpage.php?success=Profile updated successfully.");
    exit();
} else {
    echo "Error updating profile: " . $connection->error;
}

$stmt->close();
$connection->close();
?>
