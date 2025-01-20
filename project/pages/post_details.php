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

// Fetch post details with updated query
$postQuery = "
    SELECT 
        p.id, 
        p.title, 
        p.description, 
        p.image_url, 
        p.created_at, 
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title'] ?? 'No title available'); ?> - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <img src="<?php echo htmlspecialchars('../uploads/' . $post['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($post['title']); ?>" 
                 class="img-fluid rounded">
        </div>
        <div class="col-md-4">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><strong>Posted on:</strong> <?php echo isset($post['created_at']) ? date("F j, Y", strtotime($post['created_at'])) : 'N/A'; ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($post['price'], 2); ?></p>
            <p><?php echo htmlspecialchars($post['description'] ?? 'No description provided.'); ?></p>
            <p><i class="bi bi-heart-fill text-danger"></i> <?php echo $post['like_count'] ?? 0; ?> Likes</p>
            <div class="d-flex align-items-center mt-3">
                <img src="../uploads/<?php echo htmlspecialchars($post['profile_picture'] ?? '../images/user-default.png'); ?>" 
                     alt="User" class="rounded-circle me-2" style="width: 50px; height: 50px;">
                <strong><?php echo htmlspecialchars($post['username'] ?? 'Unknown user'); ?></strong>
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
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
