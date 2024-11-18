<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page with a logout message
header("Location: ../pages/login.php?message=" . urlencode("You have been logged out."));
exit();
?>
