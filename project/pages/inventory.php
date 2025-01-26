<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch purchased posts for the user
$query = "
    SELECT 
        p.id, p.title, p.image_url, p.description, pu.purchase_date
    FROM purchases pu
    JOIN posts p ON pu.post_id = p.id
    WHERE pu.user_id = ?
    ORDER BY pu.purchase_date DESC
";

$stmt = $connection->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$purchases = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">My Purchases</h1>

        <?php if (!empty($purchases)): ?>
            <div class="row mt-4">
                <?php foreach ($purchases as $purchase): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../uploads/<?php echo htmlspecialchars($purchase['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($purchase['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($purchase['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($purchase['description']); ?></p>
                                <p class="card-text"><small class="text-muted">Purchased on: <?php echo date("F j, Y", strtotime($purchase['purchase_date'])); ?></small></p>
                                <a href="../uploads/<?php echo htmlspecialchars($purchase['image_url']); ?>" download class="btn btn-primary">Download</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No purchases found.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
