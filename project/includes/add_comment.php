<?php/*
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
exit();*/
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Sanitize input
    $user_id = $_SESSION['user_id'];
    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
    $parent_id = filter_input(INPUT_POST, 'parent_id', FILTER_VALIDATE_INT) ?? 0;
    $comment = trim(htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8'));

    if ($post_id && !empty($comment)) {
        $query = "INSERT INTO comments (post_id, user_id, comment, created_at, parent_id) VALUES (?, ?, ?, NOW(), ?)";
        if ($stmt = $connection->prepare($query)) {
            $stmt->bind_param('iisi', $post_id, $user_id, $comment, $parent_id);
            if ($stmt->execute()) {
                $stmt->close();
                $connection->close();
                header("Location: ../pages/post_details.php?post_id=" . htmlspecialchars($post_id));
                exit();
            } else {
                die("Error executing query: " . $stmt->error);
            }
        } else {
            die("Error preparing statement: " . $connection->error);
        }
    } else {
        header("Location: ../pages/post_details.php?post_id=" . htmlspecialchars($post_id) . "&error=Invalid input");
        exit();
    }
} else {
    header("Location: ../pages/discover.php");
    exit();
}
?>
