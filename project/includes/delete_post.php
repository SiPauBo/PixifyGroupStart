<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';



$post_id = intval($_GET['id']);

// Prepare and execute the deletion query
$query = "DELETE FROM posts WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    header('Location: ../pages/manage_content.php?success=Post deleted successfully.'); 
} else {
    header('Location: ../pages/manage_content.php?error=Failed to delete the post.');
}

$stmt->close();
$connection->close();
?>
