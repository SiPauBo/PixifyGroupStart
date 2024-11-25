<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';
include 'post_card.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$section = isset($_GET['section']) ? $_GET['section'] : 'discover';
$limit = 12; // Number of posts per page
$offset = ($page - 1) * $limit;

// Fetch posts based on section
if ($section === 'discover') {
    $postsQuery = "
        SELECT p.id, p.title, p.image_url, u.username, u.profile_picture
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT $limit OFFSET $offset
    ";
} elseif ($section === 'for-you') {
    $user_id = $_SESSION['user_id'] ?? 0;
    $postsQuery = "
        SELECT p.id, p.title, p.image_url, u.username, u.profile_picture
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE u.id != $user_id
        ORDER BY RAND()
        LIMIT $limit OFFSET $offset
    ";
}
$result = $connection->query($postsQuery);

// Generate HTML for each post
if ($result->num_rows > 0) {
    while ($post = $result->fetch_assoc()) {
        renderPostCard($post);
    }
}
