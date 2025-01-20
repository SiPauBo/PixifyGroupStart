<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    header('Location: reports.php');
    exit();
}

$post_id = intval($_GET['post_id']);

// Fetch post details
$postQuery = "
    SELECT p.id, p.title, p.description, p.image_url, p.created_at, u.username, u.profile_picture
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
";
$stmt = $connection->prepare($postQuery);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$postResult = $stmt->get_result();
$post = $postResult->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit();
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
    <h1 class="text-center"><?php echo htmlspecialchars($post['title']); ?></h1>
    <div class="card">
        <img src="../uploads/<?php echo htmlspecialchars($post['image_url']); ?>" class="card-img-top" alt="Post Image">
        <div class="card-body">
            <p><?php echo htmlspecialchars($post['description']); ?></p>
            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
            <p><strong>Posted on:</strong> <?php echo date("F j, Y", strtotime($post['created_at'])); ?></p>
            <a href="reports.php" class="btn btn-secondary">Return to Reports</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
