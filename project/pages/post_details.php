<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if post_id is provided in the URL
if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    header('Location: discover.php');
    exit();
}

$post_id = intval($_GET['post_id']);

// Fetch post details
$postQuery = "
    SELECT 
        p.id, 
        p.title, 
        p.description, 
        p.image_url, 
        p.created_at, 
        u.id AS user_id,
        u.username, 
        u.profile_picture, 
        COALESCE(p.price, 0) AS price,
        COALESCE(COUNT(l.id), 0) AS like_count
    FROM posts p
    LEFT JOIN likes l ON p.id = l.post_id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
    GROUP BY p.id, u.username, u.profile_picture, p.created_at
";

$stmt = $connection->prepare($postQuery);
$stmt->bind_param('i', $post_id);
$stmt->execute();
$postResult = $stmt->get_result();
$post = $postResult->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// Fetch comments for the post
$commentsQuery = "
    SELECT c.id, c.comment, c.created_at, c.parent_id, u.username, u.profile_picture
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.post_id = ?
    ORDER BY c.parent_id ASC, c.created_at ASC
";

$commentStmt = $connection->prepare($commentsQuery);
$commentStmt->bind_param('i', $post_id);
$commentStmt->execute();
$commentsResult = $commentStmt->get_result();

$comments = [];
while ($row = $commentsResult->fetch_assoc()) {
    if (!$row['parent_id']) {
        $comments[$row['id']] = $row;
        $comments[$row['id']]['children'] = [];
    } else {
        $comments[$row['parent_id']]['children'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <img id="postImage" src="<?php echo htmlspecialchars('../uploads/' . $post['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($post['title']); ?>" 
                 class="img-fluid rounded"
                 onclick="toggleFullscreen(this)">
            <button class="btn btn-primary mt-3" onclick="toggleFullscreen(document.getElementById('postImage'))">Fullscreen</button>
        </div>
        <div class="col-md-4">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><strong>Posted on:</strong> <?php echo date("F j, Y", strtotime($post['created_at'])); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($post['price'], 2); ?></p>
            <p><?php echo htmlspecialchars($post['description']); ?></p>
            <p><i class="bi bi-heart-fill text-danger"></i> <?php echo $post['like_count']; ?> Likes</p>

            <form action="../includes/like_post.php" method="POST">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button type="submit" class="btn btn-outline-danger mt-2">
                    <i class="bi bi-heart-fill"></i> Like
                </button>
            </form>

            <div class="d-flex align-items-center mt-3">
                <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>">
                    <img src="../uploads/<?php echo htmlspecialchars($post['profile_picture']); ?>" 
                         alt="User" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                </a>
                <strong><?php echo htmlspecialchars($post['username']); ?></strong>
            </div>

            <form action="../includes/add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($post['id']); ?>">
                <input type="hidden" name="product_title" value="<?php echo htmlspecialchars($post['title']); ?>">
                <input type="hidden" name="product_description" value="<?php echo htmlspecialchars($post['description']); ?>">
                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars('../uploads/' . $post['image_url']); ?>">
                <input type="hidden" name="product_price" value="<?php echo $post['price']; ?>">
                <button type="submit" class="btn btn-primary mt-3">Add to Cart</button>
            </form>
        </div>
    </div>

    <h3 class="mt-5">Comments</h3>
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="../includes/add_comment.php" method="POST">
            <textarea class="form-control mb-3" name="comment" placeholder="Add a comment..." required></textarea>
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="hidden" name="parent_id" value="0">
            <button type="submit" class="btn btn-primary">Post Comment</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to leave a comment.</p>
    <?php endif; ?>

    <?php foreach ($comments as $comment): ?>
        <div class="mt-3 p-3 border rounded bg-light">
            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
            <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></small>

            <form action="../includes/add_comment.php" method="POST" class="mt-2">
                <textarea class="form-control" name="comment" placeholder="Reply..." required></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                <button type="submit" class="btn btn-secondary btn-sm mt-2">Reply</button>
            </form>

            <?php foreach ($comment['children'] as $reply): ?>
                <div class="mt-2 ms-4 p-2 border rounded bg-white">
                    <strong><?php echo htmlspecialchars($reply['username']); ?></strong>
                    <p><?php echo htmlspecialchars($reply['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
function toggleFullscreen(image) {
    if (!document.fullscreenElement) {
        image.requestFullscreen().catch(err => console.error("Error attempting to enable fullscreen", err));
    } else {
        document.exitFullscreen();
    }
}
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
