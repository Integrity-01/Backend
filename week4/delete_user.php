<?php
// delete_user.php

session_start();
require 'config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Delete the user
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    echo "User deleted successfully!";
} else {
    die("User ID not provided.");
}

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>