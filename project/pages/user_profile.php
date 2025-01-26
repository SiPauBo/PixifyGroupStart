<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('Location: discover.php');
    exit();
}

$user_id = intval($_GET['user_id']);

// Fetch user profile details including social links and banner
$userQuery = "
    SELECT username, profile_picture, bio, social_links, banner_image 
    FROM users 
    WHERE id = ?
";
$stmt = $connection->prepare($userQuery);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

// Decode social links JSON
$social_links = json_decode($user['social_links'], true);

// Fetch user posts
$postsQuery = "
    SELECT id, image_url, title 
    FROM posts 
    WHERE user_id = ?
    ORDER BY created_at DESC
";
$stmt = $connection->prepare($postsQuery);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$postsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($user['username']); ?> - Pixify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>

<?php include "../includes/navbar.php"; ?>

<div class="profile-banner" style="background: url('<?php echo !empty($user['banner_image']) ? '../uploads/' . $user['banner_image'] : 'https://via.placeholder.com/1200x300'; ?>') no-repeat center center; background-size: cover; height: 300px;">
</div>

<div class="container">
  <div class="profile-details text-center">
    <img src="<?php echo htmlspecialchars('../uploads/' . $user['profile_picture']); ?>" alt="Profile Picture" class="rounded-circle" width="120" height="120">
    <h2><?php echo htmlspecialchars($user['username']); ?></h2>
    <p><?php echo htmlspecialchars($user['bio'] ?? 'No bio provided.'); ?></p>

    <div class="social-buttons mt-3">
      <?php if (!empty($social_links['twitter'])): ?>
        <a href="https://twitter.com/<?php echo htmlspecialchars($social_links['twitter']); ?>" class="btn btn-primary">Twitter</a>
      <?php endif; ?>
      <?php if (!empty($social_links['instagram'])): ?>
        <a href="https://instagram.com/<?php echo htmlspecialchars($social_links['instagram']); ?>" class="btn btn-danger">Instagram</a>
      <?php endif; ?>
    </div>
  </div>

  <h2 class="text-center mt-5">User's Posts</h2>
  <div class="row g-4">
    <?php while ($post = $postsResult->fetch_assoc()): ?>
      <div class="col-md-3">
        <a href="post_details.php?post_id=<?php echo $post['id']; ?>">
          <img src="../uploads/<?php echo htmlspecialchars($post['image_url']); ?>" class="img-fluid">
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include "../includes/footer.php"; ?>
</body>
</html>
