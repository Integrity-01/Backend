<?php
// protected_endpoint.php

require 'config.php';
require 'vendor/autoload.php'; // Load Composer autoload

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Get the token from the Authorization header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Authorization header missing']);
    exit();
}

$authHeader = $headers['Authorization'];
$token = str_replace('Bearer ', '', $authHeader);

try {
    // Decode and validate the token
    $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

    // Token is valid, grant access
    echo json_encode([
        'message' => 'Welcome to the protected endpoint!',
        'user_id' => $decoded->user_id,
        'username' => $decoded->username
    ]);
} catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Invalid or expired token']);
}
?>