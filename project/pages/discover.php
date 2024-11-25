<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';
include '../includes/post_card.php';

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

$result = $connection->query($postsQuery) or die($connection->error);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #posts-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .image-card {
            width: calc(20% - 10px); /* 5 cards per row */
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .image-card img {
            width: 100%;
            height: auto;
            display: block;
        }
        .image-card:hover {
            transform: scale(1.05);
        }
        .card-hover-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
            display: none;
        }
        .image-card:hover .card-hover-info {
            display: block;
        }
        .toggle-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .toggle-buttons a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            color: var(--blue-500);
            border: 2px solid var(--blue-500);
            border-radius: 5px;
            background-color: var(--blue-100);
            transition: all 0.3s ease;
        }
        .toggle-buttons a.active {
            background-color: var(--blue-500);
            color: white;
        }
        .toggle-buttons a:hover {
            background-color: var(--blue-600);
            color: white;
        }
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
            <?php if ($result->num_rows > 0): ?>
                <?php while ($post = $result->fetch_assoc()): ?>
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
