<?php
session_start();
include '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];  // Changed variable to avoid conflicts

// Handle banner upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['banner_image'])) {
    $uploadDir = '../uploads/';
    $bannerName = 'banner_' . $logged_in_user_id . '_' . time() . '.' . pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION);
    $targetPath = $uploadDir . $bannerName;

    if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $targetPath)) {
        $updateBannerQuery = "UPDATE users SET banner_image = ? WHERE id = ?";
        $stmt = $connection->prepare($updateBannerQuery);
        $stmt->bind_param('si', $bannerName, $logged_in_user_id);
        $stmt->execute();
        $stmt->close();
        header('Location: userpage.php');
        exit();
    }
}

// Fetch user profile details including social links and banner
$userProfileQuery = "
    SELECT username, profile_picture, bio, social_links, banner_image 
    FROM users 
    WHERE id = ?
";
$stmt = $connection->prepare($userProfileQuery);
$stmt->bind_param('i', $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();
$userProfile = $result->fetch_assoc();
$stmt->close();

// Check if user data is retrieved
if (!$userProfile) {
    die("User not found.");
}

// Decode social links JSON safely
$social_links = !empty($userProfile['social_links']) ? json_decode($userProfile['social_links'], true) : [];

// Fetch user posts (gallery images)
$userPostsQuery = "
    SELECT id, image_url, title 
    FROM posts 
    WHERE user_id = ?
    ORDER BY created_at DESC
";
$stmt = $connection->prepare($userPostsQuery);
$stmt->bind_param('i', $logged_in_user_id);
$stmt->execute();
$userPostsResult = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($userProfile['username'] ?? 'Unknown User'); ?> - Pixify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css"/>
  <style>
    body {
      background-color: #f5f8fc;
    }
    .profile-banner {
      background: url('<?php echo !empty($userProfile['banner_image']) ? '../uploads/' . htmlspecialchars($userProfile['banner_image']) : 'https://via.placeholder.com/1200x300'; ?>') no-repeat center center;
      background-size: cover;
      height: 300px;
      position: relative;
    }
    .profile-banner form {
      position: absolute;
      bottom: 10px;
      right: 20px;
    }
    .profile-details {
      position: relative;
      text-align: center;
      margin-top: -60px;
    }
    .profile-details img {
      border: 5px solid #fff;
      border-radius: 50%;
      height: 120px;
      width: 120px;
    }
    .profile-name {
      font-size: 24px;
      font-weight: bold;
      color: #000;
      margin-top: 10px;
    }
    .btn-custom {
      background-color: #007bff;
      color: #fff;
      border: none; 
      padding: 10px 20px;
      border-radius: 5px; 
      font-weight: bold;
      margin-top: 10px;
    }
    .btn-custom:hover {
      background-color: #0056b3;
    }
    .gallery {
      margin-top: 50px;
    }
    .gallery img {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }
    .social-buttons a {
      display: inline-block;
      margin: 0 10px;
      color: #fff;
      font-size: 1.5rem;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 50px;
    }
    .btn-twitter { background-color: #1DA1F2; }
    .btn-instagram { background-color: #E1306C; }
    .social-buttons a:hover {
      opacity: 0.8;
    }
    footer {
      margin-top: 50px;
      font-size: 14px;
      text-align: center;
      color: gray;
    }
  </style>
  <?php include '../includes/font.php'; ?>
</head>
<body>

<?php include "../includes/navbar.php"?>

<div class="profile-banner">
  <form action="userpage.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="banner_image" accept="image/*" required>
    <button type="submit" class="btn btn-light btn-sm">Change Banner</button>
  </form>
</div>

<div class="container">
  <!-- Profile Details -->
  <div class="profile-details text-center">
    <img src="<?php echo htmlspecialchars(!empty($userProfile['profile_picture']) ? '../uploads/' . $userProfile['profile_picture'] : '../images/user-default.png'); ?>" alt="Profile Picture">
    <div class="profile-name"><?php echo htmlspecialchars($userProfile['username'] ?? 'Unknown User'); ?></div>
    <p><?php echo htmlspecialchars($userProfile['bio'] ?? 'No bio provided.'); ?></p>

    <!-- Social Media Buttons -->
    <div class="social-buttons mt-3">
      <?php if (!empty($social_links['twitter'])): ?>
        <a href="https://twitter.com/<?php echo htmlspecialchars($social_links['twitter']); ?>" class="btn-twitter" target="_blank">
          <i class="bi bi-twitter"></i> Twitter
        </a>
      <?php endif; ?>
      <?php if (!empty($social_links['instagram'])): ?>
        <a href="https://instagram.com/<?php echo htmlspecialchars($social_links['instagram']); ?>" class="btn-instagram" target="_blank">
          <i class="bi bi-instagram"></i> Instagram
        </a>
      <?php endif; ?>
    </div>

    <a href="profile_setup.php" class="btn btn-custom">Edit Profile</a>
  </div>

  <!-- Gallery Section -->
  <h2 class="text-center mt-5">My Posts</h2>
  <div class="gallery row g-4 mt-4">
    <?php if ($userPostsResult->num_rows > 0): ?>
      <?php while ($post = $userPostsResult->fetch_assoc()): ?>
        <div class="col-md-3">
          <a href="post_details.php?post_id=<?php echo $post['id']; ?>">
            <img src="../uploads/<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid">
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">No posts available.</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include "../includes/footer.php"?>
</html>
