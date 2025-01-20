<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['product_id'], $_POST['product_title'], $_POST['product_description'], $_POST['product_image'], $_POST['product_price'])) {
        die("Invalid request. Please try again.");
    }

    $product_id = htmlspecialchars($_POST['product_id']);
    $product_title = htmlspecialchars($_POST['product_title']);
    $product_description = htmlspecialchars($_POST['product_description']);
    $product_image = htmlspecialchars($_POST['product_image']);
    $product_price = floatval($_POST['product_price']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id' => $product_id,
        'title' => $product_title,
        'description' => $product_description,
        'image_url' => $product_image,
        'price' => $product_price
    ];

    $_SESSION['cart_count'] = count($_SESSION['cart']);
    header('Location: ../pages/cart.php');
    exit();
}
?>
