<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

$user_id = intval($_GET['id']);

// Prevent deletion of the admin account itself
if ($user_id == $_SESSION['user_id']) {
    header('Location: ../pages/manage_users.php?error=You cannot delete your own account.');
    exit();
}

// Start a transaction to ensure data consistency
$connection->begin_transaction();

try {
    // Delete related data in other tables (e.g., comments, likes, posts)
    $connection->query("DELETE FROM comments WHERE user_id = $user_id");
    $connection->query("DELETE FROM likes WHERE user_id = $user_id");
    $connection->query("DELETE FROM posts WHERE user_id = $user_id");
    $connection->query("DELETE FROM followers WHERE follower_id = $user_id OR followed_id = $user_id");

    // Delete the user
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Commit the transaction
        $connection->commit();
        header('Location: ../pages/manage_users.php?success=User deleted successfully.');
    } else {
        throw new Exception("Failed to delete the user.");
    }

    $stmt->close();
} catch (Exception $e) {
    // Roll back the transaction in case of an error
    $connection->rollback();
    header('Location: ../pages/manage_users.php?error=' . $e->getMessage());
}

$connection->close();
?>
