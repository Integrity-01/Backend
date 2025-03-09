<?php
// guest_dashboard.php

session_start();
require 'config.php';

// Check if the user is logged in and is a guest
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guest') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
</head>
<body>
    <h1>Welcome, Guest!</h1>
    <p>You can only view this page.</p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>