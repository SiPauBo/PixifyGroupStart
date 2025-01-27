<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']); 

// Fetch user information if logged in
$subscription_plan = 'free'; // Default to free plan
$profile_picture = '../images/user-default.png';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $result = $connection->query("SELECT profile_picture, subscription_plan FROM users WHERE id = $user_id");
    $user = $result->fetch_assoc();

    $profile_picture = !empty($user['profile_picture']) ? '../uploads/' . htmlspecialchars($user['profile_picture']) : $profile_picture;
    $subscription_plan = $user['subscription_plan'] ?? 'free'; // Default if null
}

$hasPurchases = false;
if ($is_logged_in) {
    $purchasesQuery = "SELECT COUNT(*) AS purchase_count FROM purchases WHERE user_id = ?";
    $stmt = $connection->prepare($purchasesQuery);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $hasPurchases = $data['purchase_count'] > 0;
    $stmt->close();
}

// Determine badge details based on the subscription plan
$plan_badges = [
    'free' => ['label' => 'Free', 'class' => 'badge bg-success', 'icon' => '🟩'],
    'advanced' => ['label' => 'Advanced', 'class' => 'badge bg-primary', 'icon' => '🔷'],
    'premium' => ['label' => 'Premium', 'class' => 'badge bg-warning', 'icon' => '⭐']
];

$badge = $plan_badges[$subscription_plan] ?? $plan_badges['free'];

// Calculate the number of items in the cart
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<nav class="navbar navbar-expand-lg bg-light">
    <div class="container">
        <a class="navbar-brand text-2xl text-blue-600" href="../pages/index.php">Pixify</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="discover.php">Discover</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subscriptions.php">Subscriptions</a>
                </li>
                
                <?php if ($is_logged_in && $hasPurchases): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory.php">My Purchases</a>
                    </li>
                <?php endif; ?>

                <?php if ($is_logged_in): ?>
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../pages/admin_dashboard.php">Admin Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item d-flex align-items-center">
                        <span class="<?php echo $badge['class']; ?> me-2"><?php echo $badge['icon']; ?> <?php echo $badge['label']; ?></span>
                        <a class="nav-link" href="userpage.php">
                            <img src="<?php echo $profile_picture; ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../includes/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="../pages/signup.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
