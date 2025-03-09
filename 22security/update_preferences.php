<?php
// update_preferences.php

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Get the dark mode preference from the form
    $dark_mode = $_POST['dark_mode'];

    // Update the dark mode preference in the database
    $stmt = $pdo->prepare("UPDATE users SET dark_mode = ? WHERE id = ?");
    $stmt->execute([$dark_mode, $_SESSION['user_id']]);

    // Update the dark mode cookie
    setcookie('dark_mode', $dark_mode, time() + (86400 * 30), "/");

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>