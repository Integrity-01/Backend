<?php
session_start();
require 'functions.php';

// Redirect to home if already logged in
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate password strength
    if (!isPasswordStrong($password)) {
        echo "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.";
        exit;
    }

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Save the user
    saveUser($username, $hashedPassword);

    echo "Registration successful. <a href='login.php'>Login here</a>.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>