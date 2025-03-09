<?php
// rate_limited_endpoint.php
session_start(); // Start the session to track requests

header('Content-Type: application/json'); // Set the response type to JSON

// Rate limiting settings
$maxRequests = 10; // Maximum number of requests allowed
$timeFrame = 60; // Time frame in seconds (e.g., 1 minute)

// Initialize the request count and timestamp if not set
if (!isset($_SESSION['request_count'])) {
    $_SESSION['request_count'] = 0;
    $_SESSION['first_request_time'] = time();
}

// Check if the time frame has expired
if (time() - $_SESSION['first_request_time'] > $timeFrame) {
    // Reset the request count and timestamp
    $_SESSION['request_count'] = 0;
    $_SESSION['first_request_time'] = time();
}

// Increment the request count
$_SESSION['request_count']++;

// Check if the user has exceeded the request limit
if ($_SESSION['request_count'] > $maxRequests) {
    http_response_code(429); // Too Many Requests
    echo json_encode(['error' => 'Rate limit exceeded. Please try again later.']);
    exit();
}

// Handle GET and POST requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Process GET request
    echo json_encode(['message' => 'GET request processed', 'requests_remaining' => $maxRequests - $_SESSION['request_count']]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process POST request
    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['message'] ?? 'No message provided';

    echo json_encode(['message' => 'POST request processed', 'requests_remaining' => $maxRequests - $_SESSION['request_count'], 'data' => $message]);
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>