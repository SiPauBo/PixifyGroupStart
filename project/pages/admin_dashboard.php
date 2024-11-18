 <?php
session_start();
include '../includes/db_connection.php';

// Check if admin is logged in (assuming you have session-based authentication)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="admin_dashboard.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_users.php">
                                Manage Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_content.php">
                                Manage Content
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="site_settings.php">
                                Site Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            Export
                        </button>
                    </div>
                </div>

                <!-- Statistics Section -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">
                                    <?php
                                    // Query to count users
                                    $result = $connection->query("SELECT COUNT(*) AS count FROM users");
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Posts</h5>
                                <p class="card-text">
                                    <?php
                                    // Query to count posts
                                    $result = $connection->query("SELECT COUNT(*) AS count FROM posts");
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">New Comments</h5>
                                <p class="card-text">
                                    <?php
                                    // Query to count new comments (e.g., in the last 24 hours)
                                    $result = $connection->query("SELECT COUNT(*) AS count FROM comments WHERE created_at >= NOW() - INTERVAL 1 DAY");
                                    $row = $result->fetch_assoc();
                                    echo $row['count'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Options -->
                <h3>Management Options</h3>
                <div class="row">
                    <div class="col-md-4">
                        <a href="manage_users.php" class="btn btn-outline-primary w-100">Manage Users</a>
                    </div>
                    <div class="col-md-4">
                        <a href="manage_content.php" class="btn btn-outline-success w-100">Manage Content</a>
                    </div>
                    <div class="col-md-4">
                        <a href="site_settings.php" class="btn btn-outline-warning w-100">Site Settings</a>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
