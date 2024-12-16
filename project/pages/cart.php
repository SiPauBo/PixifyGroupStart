<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixify - Cart</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="../css/styles.css"/>
  <style>
    body {
      background-color: #f5f8fc;
   
    }
    
  


    .cart-container {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cart-title {
      font-weight: bold;
      color: #333;
    }

    .cart-item {
      border-bottom: 1px solid #e0e0e0;
      padding: 15px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .item-details {
      flex: 1;
      margin-left: 15px;
    }

    .item-details p {
      margin: 0;
    }

    .item-title {
      font-weight: bold;
      color: #333;
    }

    .remove-link {
      color: red;
      text-decoration: none;
    }

    .remove-link:hover {
      text-decoration: underline;
    }

    .checkout-btn {
      background-color: #6173F4;
      color: #fff;
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: bold;
      border: none;
    }

    .checkout-btn:hover {
      background-color: #4f5ccf;
    }


  </style>
  <?php include '../includes/font.php'; ?>
</head>
<body>

    <?php include "../includes/navbar.php"?>

  <div class="container my-5">
    <h2 class="text-center cart-title">Your Cart</h2>
    <p class="text-center text-muted">Items you've added to your cart are displayed here.</p>

    <div class="cart-container mx-auto my-4" style="max-width: 800px;">
      <!-- Item 1 -->
      <div class="cart-item">
        <img src="https://via.placeholder.com/80" alt="Item Thumbnail" class="rounded">
        <div class="item-details">
          <p class="item-title">Stock Image 1</p>
          <p class="text-muted">High-resolution image - License included</p>
        </div>
        <div class="text-end">
          <p class="fw-bold">€15.00</p>
          <a href="#" class="remove-link">Remove</a>
        </div>
      </div>

      <!-- More items can be added here -->

      <!-- Checkout Section -->
      <div class="d-flex justify-content-between align-items-center mt-4">
        <h5>Total: <span class="text-primary">€15.00</span></h5>
        <button class="btn checkout-btn">Proceed to Checkout</button>
      </div>
    </div>
  </div>

 

</body>
<?php include "../includes/footer.php"?>
</html>
