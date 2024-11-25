<?php
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <?php include '../includes/font.php'; ?>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Complete Your Profile</h2>
        <form action="../includes/profile_setup_process.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            </div>
            <div class="mb-3">
                <label for="social_links" class="form-label">Social Links (JSON format)</label>
                <input type="text" class="form-control" id="social_links" name="social_links" placeholder='{"twitter": "link", "instagram": "link"}'>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Profile</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
