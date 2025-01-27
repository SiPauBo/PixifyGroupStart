<?php
session_start();
include '../includes/db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<div class='alert alert-danger text-center'>User not logged in.</div>");
}

$user_id = $_SESSION['user_id'];

// Check for required PayPal parameters
if (isset($_GET['plan'], $_GET['paymentId'], $_GET['PayerID'])) {
    $plan = $_GET['plan'];  // 'free', 'advanced', 'premium'
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];

    // Validate the plan before updating the database
    $allowed_plans = ['free', 'advanced', 'premium'];
    if (!in_array($plan, $allowed_plans)) {
        die("<div class='alert alert-danger text-center'>Invalid subscription plan.</div>");
    }

    // Update the user's subscription plan
    $updateQuery = "UPDATE users SET subscription_plan = ? WHERE id = ?";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bind_param('si', $plan, $user_id);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Subscription Update</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
    <div class="container py-5 text-center">
        <div class="card shadow-lg p-5 border-0">
            <h1 class="mb-4"><i class="fas fa-user-check text-success"></i> Subscription Update</h1>
            <?php
            if ($stmt->execute()) {
                echo "<p class='lead'>Your subscription has been successfully updated to: <strong class='text-primary'>" . htmlspecialchars($plan) . "</strong></p>";
                echo "<a href='../pages/index.php' class='btn btn-success mt-3'><i class='fas fa-home'></i> Go to Homepage</a>";
            } else {
                echo "<p class='text-danger'>Error updating subscription. Please try again later.</p>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    $stmt->close();
    $connection->close();
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Failed</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
    <div class="container py-5 text-center">
        <div class="alert alert-danger shadow-lg p-5">
            <h1 class="mb-4"><i class="fas fa-exclamation-circle"></i> Payment Failed</h1>
            <p class="lead">Missing payment details. Please try again.</p>
            <a href='../pages/index.php' class='btn btn-danger'><i class='fas fa-arrow-left'></i> Return to Homepage</a>
        </div>
    </div>
    </body>
    </html>
    <?php
}
?>
