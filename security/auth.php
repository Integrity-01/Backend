<?php
require 'db.php';

header('Content-Type: application/json');

// Retrieve the Authorization header
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader) {
    echo json_encode(['status' => 'error', 'message' => 'Token is missing']);
    http_response_code(401); // Unauthorized
    exit;
}

// Extract the token from the Bearer prefix
if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid token format']);
    http_response_code(401); // Unauthorized
    exit;
}

try {
    // Validate the token against the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE token = :token');
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
        http_response_code(401); // Unauthorized
        exit;
    }

    // Access granted
    echo json_encode(['status' => 'success', 'message' => 'Access granted to protected resource']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>