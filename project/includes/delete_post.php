<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    header('Location: ../pages/reports.php?error=Invalid request');
    exit();
}

$post_id = intval($_GET['post_id']);

try {
    // Begin transaction
    $connection->begin_transaction();

    // Delete related records from dependent tables
    $deleteComments = $connection->prepare("DELETE FROM comments WHERE post_id = ?");
    $deleteComments->bind_param("i", $post_id);
    $deleteComments->execute();

    $deleteLikes = $connection->prepare("DELETE FROM likes WHERE post_id = ?");
    $deleteLikes->bind_param("i", $post_id);
    $deleteLikes->execute();

    $deletePostCategories = $connection->prepare("DELETE FROM post_categories WHERE post_id = ?");
    $deletePostCategories->bind_param("i", $post_id);
    $deletePostCategories->execute();

    // Now delete the post
    $deletePost = $connection->prepare("DELETE FROM posts WHERE id = ?");
    $deletePost->bind_param("i", $post_id);
    $deletePost->execute();

    // Commit transaction
    $connection->commit();

    header('Location: ../pages/reports.php?success=Post deleted successfully.');
} catch (mysqli_sql_exception $e) {
    $connection->rollback(); // Rollback in case of error
    header('Location: ../pages/reports.php?error=Failed to delete the post.');
}

$connection->close();
?>
