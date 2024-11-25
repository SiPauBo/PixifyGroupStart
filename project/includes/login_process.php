<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: ../pages/login.php?message=" . urlencode("Please fill in all fields."));
        exit();
    }

    $query = "SELECT id, username, password_hash, first_time_login, is_admin FROM users WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashedPassword, $firstTimeLogin, $isAdmin);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Check if the user is an admin
            if ($isAdmin) {
                $_SESSION['admin_logged_in'] = true;
                header("Location: ../pages/admin_dashboard.php"); // Redirect to admin dashboard
                exit();
            }

            // Redirect based on first-time login status
            if ($firstTimeLogin) {
                header("Location: ../pages/profile_setup.php"); // Redirect to profile creation
            } else {
                header("Location: ../pages/index.php"); // Redirect to homepage
            }
            exit();
        } else {
            header("Location: ../pages/login.php?message=" . urlencode("Incorrect password."));
            exit();
        }
    } else {
        header("Location: ../pages/login.php?message=" . urlencode("No account found with that email."));
        exit();
    }

    $stmt->close();
    $connection->close();
} else {
    header("Location: ../pages/login.php?message=" . urlencode("Invalid request method."));
    exit();
}
?>
