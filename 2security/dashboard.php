<?php
// dashboard.php

session_start();
require 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set session expiration to 10 minutes (600 seconds)
$inactive = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time(); // Update last activity time

// Fetch user data from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Retrieve user preferences from cookies
$dark_mode = isset($_COOKIE['dark_mode']) ? $_COOKIE['dark_mode'] : 'off';
$last_login = isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : 'Never';

// Prevent session hijacking
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body.dark-mode { background-color: #333; color: #fff; }
    </style>
</head>
<body class="<?php echo $dark_mode === 'on' ? 'dark-mode' : ''; ?>">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>Dark Mode: <?php echo $dark_mode === 'on' ? 'Enabled' : 'Disabled'; ?></p>
    <p>Last Login: <?php echo htmlspecialchars($last_login); ?></p>

    <form action="update_preferences.php" method="POST">
    <label for="dark_mode">Dark Mode:</label>
    <select name="dark_mode" id="dark_mode">
        <option value="on" <?php echo $dark_mode === 'on' ? 'selected' : ''; ?>>On</option>
        <option value="off" <?php echo $dark_mode === 'off' ? 'selected' : ''; ?>>Off</option>
    </select><br><br>
    <button type="submit">Save Preferences</button>
</form>

    <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</body>
</html>