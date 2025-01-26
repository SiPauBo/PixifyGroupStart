<?php
session_start();
include '../includes/navbar.php';
include '../includes/db_connection.php';
require_once '../includes/create_payment.php';

if (isset($_GET['cash'])) {
    $a = $_GET['cash'];

    // Add entry to the purchases table
    if (isset($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $userId = intval($_SESSION['user_id']); // Assuming the user's ID is stored in the session
            $postId = intval($item['post_id']);    // Assuming 'post_id' is in the cart items

            $query = "INSERT INTO purchases (user_id, post_id) VALUES (?, ?)";
            $stmt = $connection->prepare($query);
            if ($stmt) {
                $stmt->bind_param("ii", $userId, $postId);
                $stmt->execute();
                $stmt->close();
            } else {
                die("Error preparing query: " . $connection->error);
            }
        }
    }
    // Call the payment method
    createpayment($a, "Your Cart");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Your Shopping Cart</h2>

    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <p class="text-center">Your cart is empty.</p>
    <?php else:
        $total_price = 0;  // Initialize total price
        ?>
        <div class="list-group">
            <?php foreach ($_SESSION['cart'] as $key => $item):
                $item_price = isset($item['price']) ? floatval($item['price']) : 0.00;  // Ensure price key exists
                $total_price += $item_price;
                ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo htmlspecialchars($item['image_url'] ?? '../images/placeholder.png'); ?>"
                             class="img-fluid rounded me-3"
                             alt="Product Image"
                             style="width: 100px; height: auto;"
                             onerror="this.onerror=null; this.src='../images/placeholder.png';">
                        <div>
                            <h5><?php echo htmlspecialchars($item['title'] ?? 'N/A'); ?></h5>
                            <p class="text-muted mb-1">Author: <?php echo htmlspecialchars($item['author'] ?? 'Unknown'); ?></p>
                            <p class="fw-bold">$<?php echo number_format($item_price, 2); ?></p>
                        </div>
                    </div>
                    <a href="../includes/remove_from_cart.php?index=<?php echo $key; ?>"
                       class="btn btn-danger btn-sm">Remove</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h4>Total: <span class="text-success">$<?php echo number_format($total_price, 2); ?></span></h4>
            <div>
                <a href="../includes/clear_cart.php" class="btn btn-outline-secondary me-2">Clear All</a>
                <a href="cart.php?cash=<?php $total_price?>" class="btn btn-success btn-lg">Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>