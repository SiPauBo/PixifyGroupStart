<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if post_id is provided in the URL
if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    header('Location: discover.php'); // Redirect to Discover if no post ID
    exit();
}

$post_id = intval($_GET['post_id']);

// Fetch post details
$postQuery = "
    SELECT 
        p.id, p.title, p.description, p.image_url, p.created_at, u.username, u.profile_picture,
        COUNT(l.id) AS like_count
    FROM posts p
    LEFT JOIN likes l ON p.id = l.post_id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = $post_id
    GROUP BY p.id
";
$postResult = $connection->query($postQuery);
$post = $postResult->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit();
}

// Fetch comments for the post (including parent-child relationships)
$commentsQuery = "
    SELECT c.id, c.comment, c.created_at, c.parent_id, u.username, u.profile_picture
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = $post_id
    ORDER BY c.parent_id ASC, c.created_at ASC
";
$commentsResult = $connection->query($commentsQuery);

// Organize comments into parent-child structure
$comments = [];
while ($row = $commentsResult->fetch_assoc()) {
    if (!$row['parent_id']) {
        $comments[$row['id']] = $row; // Parent comments
        $comments[$row['id']]['children'] = [];
    } else {
        $comments[$row['parent_id']]['children'][] = $row; // Child comments
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f0f4f8;
            color: #333;
        }
        .post-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .post-image {
            max-width: 70%;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .fullscreen-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 10;
        }
        .post-details {
            width: 28%;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .comment-container {
            margin-top: 20px;
        }
        .comment-box {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nested-comments {
            margin-left: 20px;
            border-left: 3px solid #ddd;
            padding-left: 10px;
        }
        textarea {
            resize: none;
        }
    </style>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Post Details Section -->
        <div class="post-container">
            <!-- Image Section -->
            <div class="post-image">
                <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid" id="postImage">
                <button class="btn btn-primary fullscreen-btn" onclick="toggleFullscreen()">Fullscreen</button>
            </div>

            <!-- Post Details -->
            <div class="post-details">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><i class="bi bi-calendar"></i> Posted on: <?php echo date("F j, Y", strtotime($post['created_at'])); ?></p>
                <p><i class="bi bi-heart-fill text-danger"></i> <?php echo $post['like_count']; ?> Likes</p>
                <p><?php echo htmlspecialchars($post['description']); ?></p>
                <div class="d-flex align-items-center mt-3">
                    <img src="../images/<?php echo htmlspecialchars($post['profile_picture'] ?? 'default_profile.png'); ?>" alt="User" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                    <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comment-container">
            <h3>Comments</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="../includes/add_comment.php" method="POST" class="comment-form mb-4">
                    <textarea class="form-control" name="comment" rows="3" placeholder="Add a comment..." required></textarea>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <input type="hidden" name="parent_id" value="0"> <!-- Top-level comment -->
                    <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Log in</a> to leave a comment.</p>
            <?php endif; ?>

            <!-- Render Comments -->
            <?php foreach ($comments as $parent): ?>
                <div class="comment-box">
                    <div class="d-flex align-items-center mb-2">
                        <img src="../images/<?php echo htmlspecialchars($parent['profile_picture'] ?? 'default_profile.png'); ?>" alt="User" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                        <strong><?php echo htmlspecialchars($parent['username']); ?></strong>
                    </div>
                    <p class="mb-1"><?php echo htmlspecialchars($parent['comment']); ?></p>
                    <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($parent['created_at'])); ?></small>
                    <form action="../includes/add_comment.php" method="POST" class="mt-3">
                        <textarea class="form-control" name="comment" rows="2" placeholder="Reply..." required></textarea>
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <input type="hidden" name="parent_id" value="<?php echo $parent['id']; ?>"> <!-- Set parent ID -->
                        <button type="submit" class="btn btn-secondary btn-sm mt-2">Reply</button>
                    </form>

                    <!-- Render Nested Comments -->
                    <?php if (!empty($parent['children'])): ?>
                        <div class="nested-comments">
                            <?php foreach ($parent['children'] as $child): ?>
                                <div class="comment-box">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="../images/<?php echo htmlspecialchars($child['profile_picture'] ?? 'default_profile.png'); ?>" alt="User" class="rounded-circle me-2" style="width: 30px; height: 30px;">
                                        <strong><?php echo htmlspecialchars($child['username']); ?></strong>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars($child['comment']); ?></p>
                                    <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($child['created_at'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function toggleFullscreen() {
            const img = document.getElementById('postImage');
            if (!document.fullscreenElement) {
                img.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    </script>
</body>
</html>
