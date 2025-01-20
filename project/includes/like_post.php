<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the user has already liked the post
    $checkQuery = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $connection->prepare($checkQuery);
    $stmt->bind_param('ii', $post_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Unlike the post
        $stmt = $connection->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();
    } else {
        // Like the post
        $stmt = $connection->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();
    }

    header("Location: ../pages/post_details.php?post_id=$post_id");
    exit();
} else {
    header("Location: ../pages/post_details.php?post_id=$post_id&error=Login required");
    exit();
}
?>
