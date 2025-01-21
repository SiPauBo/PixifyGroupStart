<?php
session_start();

// Check if the cart exists and clear it
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);  // Remove cart session variable
    $_SESSION['cart_count'] = 0;  // Reset cart item count
}

// Redirect back to the cart page with a success message
header('Location: ../pages/cart.php?success=Cart has been cleared successfully');
exit();
?>
