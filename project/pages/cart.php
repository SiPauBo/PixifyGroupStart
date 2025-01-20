<?php
session_start();
include '../includes/navbar.php';
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
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Remove</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $key => $item): 
                    $item_price = isset($item['price']) ? floatval($item['price']) : 0.00;  // Ensure price key exists
                    $total_price += $item_price;
                ?>
                    <tr>
                        <td class="text-center">
                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? '../images/placeholder.png'); ?>" 
                                 class="img-fluid rounded" 
                                 alt="Product Image" 
                                 style="max-width: 150px; height: auto;"
                                 onerror="this.onerror=null; this.src='../images/placeholder.png';">
                        </td>
                        <td><?php echo htmlspecialchars($item['title'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($item['description'] ?? 'No description available.'); ?></td>
                        <td class="text-center">$<?php echo number_format($item_price, 2); ?></td>
                        <td class="text-center">
                            <a href="../includes/remove_from_cart.php?index=<?php echo $key; ?>" 
                               class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h4>Total Price: <span class="text-success">$<?php echo number_format($total_price, 2); ?></span></h4>
            <a href="checkout.php" class="btn btn-success btn-lg">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
