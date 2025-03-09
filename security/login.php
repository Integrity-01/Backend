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

try {
    // Fetch the user from the database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        exit;
    }

    // Generate a token (you can use a library like `firebase/php-jwt` for more secure tokens)
    $token = bin2hex(random_bytes(32)); // Simple token generation

    // Store the token in the database
    $stmt = $pdo->prepare('UPDATE users SET token = :token WHERE id = :id');
    $stmt->execute(['token' => $token, 'id' => $user['id']]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'token' => $token
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    http_response_code(500); // Internal Server Error
}
?>