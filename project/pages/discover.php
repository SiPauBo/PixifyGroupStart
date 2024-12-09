<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';
include '../includes/post_card.php';
include '../includes/floater.php';

$section = isset($_GET['section']) ? $_GET['section'] : 'discover';

if ($section === 'discover') {
    $postsQuery = "
        SELECT p.id, p.title, p.image_url, u.username, u.profile_picture
        FROM posts p
        JOIN users u ON p.user_id = u.id    
        ORDER BY p.created_at DESC
        LIMIT 20;
    ";
} elseif ($section === 'for-you') {
    $user_id = $_SESSION['user_id'] ?? 0;
    $postsQuery = "
        SELECT p.id, p.title, p.image_url, u.username, u.profile_picture
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE u.id != $user_id
        ORDER BY RAND()
        LIMIT 20;
    ";
}

$resulting = $connection->query($postsQuery) or die($connection->error);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/post_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <?php include '../includes/font.php'; ?>
    <style>
        
        </style>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4 text-center">Discover</h1>

        <!-- Toggle Buttons -->
        <div class="toggle-buttons text-center">
            <a href="discover.php?section=discover" class="<?php echo $section === 'discover' ? 'active' : ''; ?>">Discover</a>
            <a href="discover.php?section=for-you" class="<?php echo $section === 'for-you' ? 'active' : ''; ?>">For You</a>
        </div>

        <!-- Posts Container -->
        <div id="posts-container" class="d-flex flex-wrap justify-content-center"> 
            <?php if ($resulting->num_rows > 0): ?>
                <?php while ($post = $resulting->fetch_assoc()): ?>
                    <?php renderPostCard($post); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No posts available. Check back later!</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
