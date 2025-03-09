<?php
// update_user.php

require 'config.php';
require 'middleware.php'; // Include the middleware for JWT validation

header('Content-Type: application/json');

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Only PUT requests are allowed']);
    exit();
}

// Authenticate the user (e.g., only admins or the user themselves can update)
$decoded = authenticate(['user', 'admin']); // Allow both users and admins to update

// Get the input data from the request
$input = json_decode(file_get_contents('php://input'), true);

// Validate the input
if (empty($input['email'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Email is required']);
    exit();
}

// Simulated database (replace with a real database)
$users = [
    'admin' => [
        'password' => password_hash('admin123', PASSWORD_BCRYPT),
        'email' => 'admin@example.com',
        'role' => 'admin'
    ],
    'user' => [
        'password' => password_hash('user123', PASSWORD_BCRYPT),
        'email' => 'user@example.com',
        'role' => 'user'
    ]
];

// Check if the user exists
if (!isset($users[$decoded->username])) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'User not found']);
    exit();
}

// Update the user's email
$users[$decoded->username]['email'] = $input['email']; // Update email

// Simulate saving to a database (replace with actual database logic)
// For example: $db->updateUser($decoded->username, $input['email']);

// Return a success response
echo json_encode([
    'message' => 'User updated successfully',
    'user' => [
        'username' => $decoded->username,
        'email' => $input['email']
    ]
]);
?>