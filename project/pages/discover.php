<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';
include '../includes/post_card.php';
include '../includes/floater.php';

$section = isset($_GET['section']) ? $_GET['section'] : 'discover';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$baseQuery = "
    SELECT p.id, p.title, p.image_url, u.username, u.profile_picture
    FROM posts p
    JOIN users u ON p.user_id = u.id
";

if (!empty($search_query)) {
    $baseQuery .= " WHERE p.title LIKE ? ";
    $stmt = $connection->prepare($baseQuery);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param('s', $search_param);
    $stmt->execute();
    $resulting = $stmt->get_result();
} else {
    if ($section === 'discover') {
        $baseQuery .= " ORDER BY p.created_at DESC LIMIT 20;";
    } elseif ($section === 'for-you') {
        $user_id = $_SESSION['user_id'] ?? 0;
        $baseQuery .= " WHERE u.id != $user_id ORDER BY RAND() LIMIT 20;";
    }
    $resulting = $connection->query($baseQuery) or die($connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <?php include '../includes/font.php'; ?>
    <style>
        body {
            background-color: #f5f8fc;
            color: #333;
        }
        .discover-container {
            padding: 20px 0;
        }
        .toggle-buttons {
            margin-bottom: 20px;
        }
        .toggle-buttons a {
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 50px;
            margin: 0 10px;
            background: #e0e5ec;
            box-shadow: 2px 2px 4px #bebebe, -2px -2px 4px #ffffff;
            transition: all 0.3s ease-in-out;
        }
        .toggle-buttons a.active {
            background: #007bff;
            color: #fff;
            box-shadow: inset 2px 2px 4px #0056b3, inset -2px -2px 4px #0095ff;
        }
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-subtitle {
            font-size: 0.9rem;
            color: #777;
        }
        .search-bar {
            max-width: 600px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container discover-container">
    <h1 class="text-center mb-4">Discover</h1>

    <!-- Search Bar -->
    <div class="search-bar text-center">
        <form action="discover.php" method="GET" class="d-flex">
            <input type="hidden" name="section" value="<?php echo htmlspecialchars($section); ?>">
            <input type="text" name="search" class="form-control" placeholder="Search posts by title..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <!-- Toggle Buttons -->
    <div class="text-center toggle-buttons">
        <a href="discover.php?section=discover" class="<?php echo $section === 'discover' ? 'active' : ''; ?>">Discover</a>
        <a href="discover.php?section=for-you" class="<?php echo $section === 'for-you' ? 'active' : ''; ?>">For You</a>
    </div>

    <!-- Posts Grid -->
    <div id="posts-container" class="row row-cols-2 row-cols-md-4 gx-2 gy-3">
        <?php if ($resulting->num_rows > 0): ?>
            <?php while ($post = $resulting->fetch_assoc()): ?>
                <div class="col">
                    <div class="card">
                        <a href="post_details.php?post_id=<?php echo $post['id']; ?>">
                            <img src="<?php echo htmlspecialchars('../uploads/' . $post['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-subtitle">By <?php echo htmlspecialchars($post['username']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No posts available. Try searching for something else!</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
