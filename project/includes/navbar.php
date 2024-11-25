<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']); 

// Fetch user information if logged in
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $result = $connection->query("SELECT profile_picture FROM users WHERE id = $user_id");
    $user = $result->fetch_assoc();
    $profile_picture = $user['profile_picture'] ?? 'default_profile.png'; // Default image if no profile picture
}
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
                    <a class="nav-link" href="#">Subscriptions</a>
                </li>

                <!-- Conditional Rendering Based on Login Status -->
                <?php if ($is_logged_in): ?>
                    <!-- Admin Dashboard Link -->
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../pages/admin_dashboard.php">Admin Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <!-- Display Cart Icon, Profile Picture, and Logout Button if Logged In -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <img src="../images/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px;">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../includes/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Display Login and Sign Up if Not Logged In -->
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
