<?php
// user_dashboard.php

session_start();
require 'config.php';

// Check if the user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Fetch the current user's data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <h2>Edit Your Profile</h2>
    <form method="POST" action="update_profile.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password"><br><br>
        <button type="submit">Update Profile</button>
    </form>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>