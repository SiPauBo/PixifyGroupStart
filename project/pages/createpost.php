<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category_id = intval($_POST['category']);
    $price = floatval($_POST['price']); // Capture the price input
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    $targetDir = "../uploads/";
    $imageFile = $_FILES['image']['name'];
    $targetFilePath = $targetDir . basename($imageFile);
    $uploadSuccess = move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);

    if ($uploadSuccess) {
        $imageUrl = basename($imageFile); // Save only the file name in the database

        // Insert post data into the database including price
        $insertPostQuery = "
            INSERT INTO posts (user_id, title, description, image_url, price, created_at) 
            VALUES ('$user_id', '$title', '$description', '$imageUrl', '$price', NOW())
        ";
        $connection->query($insertPostQuery) or die($connection->error);

        // Insert category association
        $postId = $connection->insert_id;
        $insertCategoryQuery = "INSERT INTO post_categories (post_id, category_id) VALUES ('$postId', '$category_id')";
        $connection->query($insertCategoryQuery) or die($connection->error);

        header('Location: discover.php');
        exit();
    } else {
        $error = "File upload failed. Please try again.";
    }
}

// Fetch categories for the dropdown
$categoriesQuery = "SELECT id, category_name FROM categories";
$categoriesResult = $connection->query($categoriesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - Pixify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <?php include '../includes/font.php'; ?>
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1>Create a New Post</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="createpost.php" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price (in EUR)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" placeholder="Enter price" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
