<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header('Location: ../pages/manage_users.php?error=Invalid request');
    exit();
}

$user_id = intval($_GET['user_id']);

try {
    $connection->begin_transaction();

    // Delete related comments, posts, and other dependent records
    $connection->query("DELETE FROM comments WHERE user_id = $user_id");
    $connection->query("DELETE FROM posts WHERE user_id = $user_id");
    $connection->query("DELETE FROM likes WHERE user_id = $user_id");
    $connection->query("DELETE FROM reports WHERE user_id = $user_id");

    // Now delete the user
    $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $connection->commit();

    header('Location: ../pages/manage_users.php?success=User deleted successfully.');
} catch (mysqli_sql_exception $e) {
    $connection->rollback();
    header('Location: ../pages/manage_users.php?error=Failed to delete user.');
}

$connection->close();
?>
