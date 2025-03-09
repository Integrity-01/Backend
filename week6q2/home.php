<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: http://localhost/week6q2/github_login.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Profile Picture">
    <a href="http://localhost/week6q2/logout.php">Logout</a>
</body>
</html>