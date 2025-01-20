<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Sicherstellen, dass der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Abrufen der gekauften Artikel
$purchasesQuery = "
    SELECT 
        p.id AS post_id, p.title, p.description, p.image_url, p.price, p.created_at 
    FROM purchases pur
    JOIN posts p ON pur.post_id = p.id
    WHERE pur.user_id = ?
    ORDER BY pur.created_at DESC;
";

$stmt = $connection->prepare($purchasesQuery);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$purchases = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $purchases[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixify - Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f5f8fc;
        }
        .inventory-container {
            margin-top: 20px;
        }
        .inventory-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .inventory-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .inventory-card-body {
            padding: 15px;
        }
        .download-btn {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
        }
        .download-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container inventory-container">
    <h1 class="text-center mb-4">Your Purchases</h1>

    <div class="row g-4">
        <?php if (empty($purchases)): ?>
            <p class="text-center">You have not purchased anything yet.</p>
        <?php else: ?>
            <?php foreach ($purchases as $purchase): ?>
                <div class="col-md-4">
                    <div class="inventory-card">
                        <img src="<?php echo htmlspecialchars($purchase['image_url']); ?>" alt="<?php echo htmlspecialchars($purchase['title']); ?>">
                        <div class="inventory-card-body">
                            <h5><?php echo htmlspecialchars($purchase['title']); ?></h5>
                            <p><?php echo htmlspecialchars($purchase['description']); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($purchase['price'], 2); ?></p>
                            <a href="../uploads/<?php echo htmlspecialchars($purchase['image_url']); ?>" download class="btn download-btn">Download</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
