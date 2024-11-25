<?php
function renderPostCard($post) {
    // Check if the image URL is a valid URL or a local file
    $imageSource = filter_var($post['image_url'], FILTER_VALIDATE_URL)
        ? $post['image_url']  // Use URL if valid
        : '../uploads/' . $post['image_url'];  // Otherwise, use local file path

    $profilePicture = !empty($post['profile_picture']) 
        ? '../uploads/' . htmlspecialchars($post['profile_picture']) 
        : '../uploads/default_profile.png';
    ?>
    <div class="image-card">
        <a href="post_details.php?post_id=<?php echo $post['id']; ?>" class="card-link">
            <img src="<?php echo htmlspecialchars($imageSource); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="card-image">
        </a>
        <div class="card-hover-info">
            <div class="card-details">
                <img src="<?php echo $profilePicture; ?>" alt="User" class="user-image">
                <span class="username"><?php echo htmlspecialchars($post['username']); ?></span>
            </div>
        </div>
    </div>
    <?php
}
?>
