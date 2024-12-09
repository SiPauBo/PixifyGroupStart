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
    $profile_picture = $user['profile_picture'] ?? 'user-default.png'; // Default image if no profile picture
}

?>
<style>
    
    .navbar-nav {
    justify-content: center;
    align-items: center;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container d-flex align-items-center">
        <!-- Centered Brand -->
        <a class="navbar-brand text-2xl text-blue-600 mx-auto" href="../pages/index.php">Pixify</a>

        <!-- Responsive Navbar Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <!-- Discover -->
                <li class="nav-item">
                    <a class="nav-link" href="discover.php">Discover</a>
                </li>
                <!-- Subscriptions -->
                <li class="nav-item">
                    <a class="nav-link" href="Subscription.php">Subscriptions</a>
                </li>
                
                <!-- Conditional Items -->
                <?php if ($is_logged_in): ?>
                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center" href="cart.php">
                            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?>
                            </span>
                        </a>
                    </li>

                    <!-- Profile Picture -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="profile.php">
                            <img src="<?php echo isset($profile_picture) && !empty($profile_picture) ? '../uploads/' . htmlspecialchars($profile_picture) : '../images/user-default.png'; ?>" 
                                alt="Profile" 
                                class="rounded-circle" 
                                style="width: 40px; height: 40px;">
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="../includes/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Login -->
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/login.php">Login</a>
                    </li>
                    <!-- Sign Up -->
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="../pages/signup.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

