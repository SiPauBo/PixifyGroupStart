<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

// Fetch all reports from the database
$query = "
    SELECT r.id, r.post_id, r.user_id, r.report_reason, r.created_at, r.status, r.severity,
           u.username, p.title
    FROM reports r
    JOIN users u ON r.user_id = u.id
    JOIN posts p ON r.post_id = p.id
    ORDER BY r.created_at DESC
";

$result = $connection->query($query);

if (!$result) {
    die("Database query failed: " . $connection->error);
}

$reports = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Manage Reports</h1>
    <?php if (!empty($reports)): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Post Title</th>
                    <th>Reported By</th>
                    <th>Reason</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['id']); ?></td>
                        <td>
                            <a href="view_report.php?post_id=<?php echo $report['post_id']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($report['title']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($report['username']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_reason']); ?></td>
                        <td class="text-<?php echo $report['severity'] == 'high' ? 'danger' : ($report['severity'] == 'medium' ? 'warning' : 'info'); ?>">
                            <?php echo htmlspecialchars(ucfirst($report['severity'])); ?>
                        </td>
                        <td><?php echo htmlspecialchars($report['status']); ?></td>
                        <td><?php echo date("F j, Y, g:i a", strtotime($report['created_at'])); ?></td>
                        <td>
                            <a href="../includes/delete_post.php?post_id=<?php echo $report['post_id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to remove this post?');">
                               Remove Post
                            </a>
                            <a href="view_report.php?post_id=<?php echo $report['post_id']; ?>" class="btn btn-primary btn-sm">View Post</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">No reports found.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
