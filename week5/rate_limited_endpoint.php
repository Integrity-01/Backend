<?php
// rate_limited_endpoint.php
session_start();

header('Content-Type: application/json');

$requests = $_SESSION['requests'] ?? 0;

if ($requests >= 10) {
    http_response_code(429); // Too Many Requests
    echo json_encode(['error' => 'Too many requests. Please try again later.']);
    exit();
}

$_SESSION['requests'] = $requests + 1;

// Process the request
echo json_encode(['message' => 'Request processed']);
?>