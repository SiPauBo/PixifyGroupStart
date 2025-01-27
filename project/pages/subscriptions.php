<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/create_payment.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['paymentadvanced'])) {
    createpayment(4.99, "Advanced Plan", "advanced");
}
if (isset($_GET['paymentpremium'])) {
    createpayment(14.99, "Premium Plan", "premium");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixify - Subscription Plans</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>

<?php include "../includes/navbar.php" ?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-5">Our Subscriptions</h2>
    <div class="row justify-content-center">

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Free Plan</h5>
                    <span class="badge bg-success mb-3">Free</span>
                    <p class="card-text">
                        Start your creative journey for free! Publish <strong>up to 30 posts</strong> without spending. If you hit the limit, simply swap out an older post for a new one.
                    </p>
                    <p class="fw-bold">Cost: <span class="text-success">Absolutely Free!</span></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Advanced Plan</h5>
                    <span class="badge bg-primary mb-3">Advanced</span>
                    <p class="card-text">
                        Take your creativity to the next level! Share <strong>up to 250 posts</strong>, giving you plenty of room to showcase your photography and designs.
                    </p>
                    <p class="fw-bold">Cost: <span class="text-primary">€4.99</span></p>
                    <form action="" method="get">
                        <input type="hidden" name="paymentadvanced" value="advanced">
                        <button type="submit" class="btn btn-custom">Get Advanced</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Premium Plan</h5>
                    <span class="badge bg-warning mb-3">Premium</span>
                    <p class="card-text">
                        Go all in no limits, no compromises! Publish an <strong>unlimited number of posts</strong> and build a professional portfolio with ease.
                    </p>
                    <p class="fw-bold">Cost: <span class="text-warning">€14.99</span></p>
                    <form action="" method="get">
                        <input type="hidden" name="paymentpremium" value="premium">
                        <button type="submit" class="btn btn-custom">Get Premium</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
<?php include "../includes/footer.php" ?>
</html>
