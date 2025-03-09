<?php
session_start();

// Redirect to home if already logged in
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Login System</h1>
    <p><a href="login.php">Login</a></p>
    <p><a href="register.php">Register</a></p>
</body>
</html>