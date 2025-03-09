<?php
// login.php

require 'config.php';
require 'vendor/autoload.php'; // Load Composer autoload

use Firebase\JWT\JWT;

// Get the raw JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if the required fields are present
if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Username and password are required']);
    exit();
}

$username = $data['username'];
$password = $data['password'];

// Fetch user from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login successful
    $payload = [
        'user_id' => $user['id'],
        'username' => $user['username'],
        'exp' => time() + 3600 // Token expires in 1 hour
    ];

    // Generate JWT token
    $jwt = JWT::encode($payload, JWT_SECRET, 'HS256');

    // Return the token as JSON
    echo json_encode(['token' => $jwt]);
} else {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password']);
}
?>