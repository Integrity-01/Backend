<?php
// login.php

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? true : false;

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['last_activity'] = time(); // Set last activity time

        // Update last login in the database
        $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);

        // Set cookies for user preferences
        setcookie('dark_mode', $user['dark_mode'], time() + (86400 * 30), "/"); // 30 days
        setcookie('last_login', $user['last_login'], time() + (86400 * 30), "/"); // 30 days

        // Handle "Remember Me" feature
        if ($remember_me) {
            // Generate a unique token for the user
            $token = bin2hex(random_bytes(32)); // Secure random token
            $hashed_token = password_hash($token, PASSWORD_DEFAULT);

            // Store the token in the database
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$hashed_token, $user['id']]);

            // Set an encrypted cookie with the token
            setcookie('remember_me', $token, time() + (86400 * 30), "/"); // 30 days
        }

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <label for="remember_me">Remember Me:</label>
        <input type="checkbox" name="remember_me" id="remember_me"><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>