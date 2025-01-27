<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if user_id is provided in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('Location: discover.php');
    exit();
}

$profile_user_id = intval($_GET['user_id']);  // Changed variable name to avoid conflicts

// Fetch user profile details including social links and banner
$userProfileQuery = "
    SELECT username, profile_picture, bio, social_links, banner_image 
    FROM users 
    WHERE id = ?
";
$userProfileStmt = $connection->prepare($userProfileQuery);
$userProfileStmt->bind_param('i', $profile_user_id);
$userProfileStmt->execute();
$userProfileResult = $userProfileStmt->get_result();
$profileData = $userProfileResult->fetch_assoc();

// Check if user exists
if (!$profileData) {
    die("User not found.");
}

// Decode social links JSON
$social_links = json_decode($profileData['social_links'], true);

// Fetch user posts
$userPostsQuery = "
    SELECT id, image_url, title 
    FROM posts 
    WHERE user_id = ?
    ORDER BY created_at DESC
";
$userPostsStmt = $connection->prepare($userPostsQuery);
$userPostsStmt->bind_param('i', $profile_user_id);
$userPostsStmt->execute();
$userPostsResult = $userPostsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($profileData['username'] ?? 'Unknown User'); ?> - Pixify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>

<?php include "../includes/navbar.php"; ?>

<div class="profile-banner" style="background: url('<?php echo !empty($profileData['banner_image']) ? '../uploads/' . $profileData['banner_image'] : 'https://via.placeholder.com/1200x300'; ?>') no-repeat center center; background-size: cover; height: 300px;">
</div>

<div class="container">
  <div class="profile-details text-center">
    <img src="<?php echo htmlspecialchars(!empty($profileData['profile_picture']) ? '../uploads/' . $profileData['profile_picture'] : '../images/user-default.png'); ?>" 
         alt="Profile Picture" class="rounded-circle" width="120" height="120">
    <h2><?php echo htmlspecialchars($profileData['username'] ?? 'Unknown User'); ?></h2>
    <p><?php echo htmlspecialchars($profileData['bio'] ?? 'No bio provided.'); ?></p>

    <div class="social-buttons mt-3">
      <?php if (!empty($social_links['twitter'])): ?>
        <a href="https://twitter.com/<?php echo htmlspecialchars($social_links['twitter']); ?>" class="btn btn-primary" target="_blank">Twitter</a>
      <?php endif; ?>
      <?php if (!empty($social_links['instagram'])): ?>
        <a href="https://instagram.com/<?php echo htmlspecialchars($social_links['instagram']); ?>" class="btn btn-danger" target="_blank">Instagram</a>
      <?php endif; ?>
    </div>
  </div>

  <h2 class="text-center mt-5">User's Posts</h2>
  <div class="row g-4">
    <?php if ($userPostsResult->num_rows > 0): ?>
        <?php while ($post = $userPostsResult->fetch_assoc()): ?>
          <div class="col-md-3">
            <a href="post_details.php?post_id=<?php echo $post['id']; ?>">
              <img src="../uploads/<?php echo htmlspecialchars($post['image_url']); ?>" class="img-fluid">
            </a>
          </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">No posts available.</p>
    <?php endif; ?>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
