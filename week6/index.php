<?php
session_start();
require 'vendor/autoload.php';

// Configuration
$jwt_secret = "your_very_secret_key"; // Replace with your secret key

// Include middleware
require 'middleware/auth.php';
require 'middleware/logging.php';
require 'middleware/rate_limit.php';

// Simulate API Gateway routing
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Apply middleware
if (!applyAuthMiddleware($jwt_secret)) {
    exit; // Stop processing if authentication fails
}

if (!applyLoggingMiddleware()) {
    exit; // Stop processing if logging fails
}

if (!applyRateLimitMiddleware()) {
    exit; // Stop processing if rate limit is exceeded
}

// Route to API endpoints
if ($uri === '/api/users' && $method === 'GET') {
    require 'api/users.php';
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found.']);
}