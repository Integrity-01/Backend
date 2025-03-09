<?php
session_start();
require 'functions.php';

// Redirect to home if already logged in
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Load users from the file
    $users = loadUsers();

    if (isset($users[$username])) {
        $user = &$users[$username]; // Use a reference to update the user data

        // Check if the account is locked
        if ($user['lockout_time'] > time()) {
            $remainingTime = $user['lockout_time'] - time();
            echo "Account locked. Try again in $remainingTime seconds.";
            exit;
        }

        // Validate password
        if (validatePassword($password, $user['password'])) {
            // Reset failed attempts on successful login
            $user['failed_attempts'] = 0;
            $user['lockout_time'] = 0;

            // Save updated user data
            saveUsers($users);

            // Save user data in session
            $_SESSION['user'] = [
                'username' => $username,
            ];

            header('Location: home.php');
            exit;
        } else {
            // Increment failed attempts
            $user['failed_attempts']++;

            // Lock the account after 3 failed attempts
            if ($user['failed_attempts'] >= 3) {
                $user['lockout_time'] = time() + 300; // Lock for 5 minutes
                echo "Account locked for 5 minutes.";
            } else {
                echo "Invalid password. Attempts remaining: " . (3 - $user['failed_attempts']);
            }

            // Save updated user data
            saveUsers($users);
            exit;
        }
    } else {
        echo "User not found.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>