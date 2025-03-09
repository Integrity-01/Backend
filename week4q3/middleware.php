<?php
// middleware.php

require 'config.php';
require 'vendor/autoload.php'; // Load Composer autoload

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Middleware function to authenticate requests and enforce role-based access control.
 *
 * @param array|string $requiredRoles The role(s) required to access the endpoint.
 * @return object The decoded JWT payload if authentication is successful.
 */
function authenticate($requiredRoles) {
    // Ensure $requiredRoles is an array
    if (!is_array($requiredRoles)) {
        $requiredRoles = [$requiredRoles];
    }

    // Get the token from the Authorization header
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Authorization header missing']);
        exit();
    }

    // Extract the token from the header
    $authHeader = $headers['Authorization'];
    if (!str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Invalid Authorization header format']);
        exit();
    }

    $token = substr($authHeader, 7); // Remove 'Bearer ' prefix

    try {
        // Decode and validate the token
        $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

        // Check if the user's role is allowed
        if (!in_array($decoded->role, $requiredRoles)) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'Access denied: Insufficient permissions']);
            exit();
        }

        // Return the decoded token for further use
        return $decoded;
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Invalid or expired token']);
        exit();
    }
}
?>