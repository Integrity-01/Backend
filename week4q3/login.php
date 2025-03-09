<?php
// login.php

echo "Current directory: " . __DIR__ . "\n"; // Debugging: Print current directory

require 'config.php'; // Ensure this path is correct
require 'vendor/autoload.php'; // Load Composer autoload

use Firebase\JWT\JWT;

header('Content-Type: application/json');

// Simulated user database (replace with a real database query)
$users = [
    'admin' => [
        'password' => password_hash('admin123', PASSWORD_BCRYPT), // Hashed password
        'role' => 'admin'
    ],
    'user' => [
        'password' => password_hash('user123', PASSWORD_BCRYPT), // Hashed password
        'role' => 'user'
    ],
    'guest' => [
        'password' => password_hash('guest123', PASSWORD_BCRYPT), // Hashed password
        'role' => 'guest'
    ]
];

// Get input data from the request
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

// Validate credentials
if (!isset($users[$username]) || !password_verify($password, $users[$username]['password'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid username or password']);
    exit();
}

// Generate JWT token
$payload = [
    'user_id' => $username,
    'username' => $username,
    'role' => $users[$username]['role'],
    'iat' => time(), // Issued at
    'exp' => time() + 3600 // Expiration time (1 hour)
];

$jwt = JWT::encode($payload, JWT_SECRET, 'HS256');

// Return the token
echo json_encode([
    'message' => 'Login successful',
    'token' => $jwt
]);
?>