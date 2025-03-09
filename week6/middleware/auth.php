<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function applyAuthMiddleware($jwt_secret) {
    try {
        // Check Authorization header
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Missing token.']);
            return false;
        }

        // Extract token
        $authHeader = $headers['Authorization'];
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Invalid token format.']);
            return false;
        }

        $token = $matches[1];

        // Decode and verify token
        $decoded = JWT::decode($token, new Key($jwt_secret, 'HS256'));

        // Store user ID in session
        $_SESSION['user_id'] = $decoded->data->user_id;

        return true;
    } catch (Exception $e) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Invalid or expired token.']);
        return false;
    }
}