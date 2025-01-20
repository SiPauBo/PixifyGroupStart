<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $report_reason = trim($_POST['report_reason']);
    $severity = trim($_POST['severity']);

    if (!empty($report_reason) && !empty($severity)) {
        $query = "INSERT INTO reports (post_id, user_id, report_reason, created_at, status, severity) VALUES (?, ?, ?, NOW(), 'open', ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('iiss', $post_id, $user_id, $report_reason, $severity);

        if ($stmt->execute()) {
            header("Location: ../pages/post_details.php?post_id=$post_id&success=Report submitted");
        } else {
            header("Location: ../pages/post_details.php?post_id=$post_id&error=Failed to submit report");
        }
        $stmt->close();
    }
}
?>
