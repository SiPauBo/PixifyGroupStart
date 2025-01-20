<?php
session_start();

// Check if index is provided in URL and cart exists
if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    // Remove the item at the given index
    unset($_SESSION['cart'][$_GET['index']]);

    // Re-index the array to prevent undefined index issues
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Redirect back to cart page
echo '<script>window.location.href="../pages/cart.php";</script>';
exit();

exit();
?>
