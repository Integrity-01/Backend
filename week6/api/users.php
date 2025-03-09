<?php
session_start();

// Simulate fetching user data
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not authenticated.']);
    exit;
}

echo json_encode([
    'message' => 'User data fetched successfully.',
    'user_id' => $user_id
]);