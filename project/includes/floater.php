<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Define the target URL based on the login status
$redirectUrl = $is_logged_in ? 'createpost.php' : 'login.php';
?>


<button class="floater-button" onclick="window.location.href='<?php echo $redirectUrl; ?>'">
    +
</button>

