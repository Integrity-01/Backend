<?php
// config.php

$host = 'localhost';
$dbname = 'user_auth_system'; // New database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the "Remember Me" cookie exists
if (isset($_COOKIE['remember_me']) && !isset($_SESSION['user_id'])) {
    $token = $_COOKIE['remember_me'];

    // Fetch the user with the matching token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token IS NOT NULL");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        if (password_verify($token, $user['remember_token'])) {
            // Token is valid, log the user in
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time(); // Set last activity time
            break;
        }
    }
}
?>