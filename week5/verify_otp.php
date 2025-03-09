<?php
// verify_otp.php
session_start();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$userEnteredOTP = $input['otp'] ?? '';

if (!isset($_SESSION['otp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'OTP not generated']);
    exit();
}

if ($userEnteredOTP == $_SESSION['otp']) {
    // OTP is valid
    unset($_SESSION['otp']); // Clear OTP from session
    echo json_encode(['message' => 'Login successful', 'username' => $_SESSION['username']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid OTP']);
}
?>