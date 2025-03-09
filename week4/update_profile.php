<?php
// update_profile.php

session_start();
require 'config.php';

// Check if the user is logged in and is a regular user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strip_tags($_POST['username']); // Sanitize username
    $password = $_POST['password']; // Get the new password

    // If a new password is provided, hash it
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = null; // No password change
    }

    // Update the user's profile
    if ($hashed_password) {
        // Update both username and password
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $stmt->execute([$username, $hashed_password, $_SESSION['user_id']]);
    } else {
        // Update only the username
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$username, $_SESSION['user_id']]);
    }

    echo "Profile updated successfully!";
    header("Location: user_dashboard.php");
    exit();
} else {
    die("Invalid request.");
}
?>