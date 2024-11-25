<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);
    $parent_id = intval($_POST['parent_id']); // Correctly handle parent ID
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $query = "INSERT INTO comments (post_id, user_id, comment, created_at, parent_id) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('iisi', $post_id, $user_id, $comment, $parent_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../pages/post_details.php?post_id=$post_id");
exit();
?>
