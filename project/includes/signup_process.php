<?php

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../pages/signup.php?message=" . urlencode("Please fill in all fields."));
        exit();
    }

    // Check if email already exists
    $emailCheckQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $connection->prepare($emailCheckQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: ../pages/signup.php?message=" . urlencode("This email is already registered. Please try another."));
        exit();
    }
    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $insertQuery = "INSERT INTO users (username, email, password_hash, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $connection->prepare($insertQuery);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id; // Store user ID for profile setup
        header("Location: ../pages/login.php?message=" . urlencode("Account created successfully! You can now log in."));
        exit();
    } else {
        header("Location: ../pages/signup.php?message=" . urlencode("Error: " . $stmt->error));
        exit();
    }

    $stmt->close();
    $connection->close();
} else {
    header("Location: ../pages/signup.php?message=" . urlencode("Invalid request method."));
    exit();
}
?>
