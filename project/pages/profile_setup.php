<?php
session_start();
include '../includes/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ensure database connection is established
if (!$connection) {
    die("Database connection error.");
}

$user_id = $_SESSION['user_id'];

// Fetch user profile data from the database
$sql = "SELECT bio, profile_picture, social_links FROM users WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Decode social links JSON
$social_links = json_decode($user['social_links'] ?? '{}', true);
$twitter = $social_links['twitter'] ?? '';
$instagram = $social_links['instagram'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <?php include '../includes/font.php'; ?>
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Complete Your Profile</h2>
        <form action="../includes/profile_setup_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="existing_profile_picture" value="<?php echo htmlspecialchars($user['profile_picture'] ?? ''); ?>">
            
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                <?php if (!empty($user['profile_picture'])): ?>
                    <div class="mt-2">
                        <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail" width="150">
                    </div>
                <?php endif; ?>
            </div>

            <h5 class="mb-3">Social Media Links</h5>

            <div class="mb-3">
                <label for="twitter" class="form-label">Twitter Username</label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" class="form-control" id="twitter" name="twitter" value="<?php echo htmlspecialchars($twitter); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="instagram" class="form-label">Instagram Username</label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" class="form-control" id="instagram" name="instagram" value="<?php echo htmlspecialchars($instagram); ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Profile</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
