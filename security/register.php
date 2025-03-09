<?php
require 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

if (!$username || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
    exit;
}

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->execute(['username' => $username, 'password' => $hashedPassword]);

    echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) { // Duplicate entry error
        echo json_encode(['status' => 'error', 'message' => 'Username already exists']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>