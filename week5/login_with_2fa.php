<?php
// login_with_2fa.php
session_start();
require 'vendor/autoload.php'; // Load Composer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

// Simulated user database
$users = [
    'admin' => [
        'password' => password_hash('admin123', PASSWORD_BCRYPT),
        'email' => 'admin@example.com' // Replace with the user's email
    ]
];

// Validate credentials
if (!isset($users[$username]) || !password_verify($password, $users[$username]['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
    exit();
}

// Generate OTP
$otp = rand(100000, 999999); // 6-digit OTP
$_SESSION['otp'] = $otp; // Store OTP in session for verification
$_SESSION['username'] = $username; // Store username for verification

// Send OTP via email
$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2; // Enable debugging
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'olowoyeyeopeyemi6@gmail.com'; // Replace with your email
    $mail->Password = 'whiy gdjo hweu newc'; // Replace with your email password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('olowoyeyeopeyemi6@gmail.com', 'Your App'); // Replace with your email
    $mail->addAddress($users[$username]['email']); // User's email

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Login';
    $mail->Body = "Your OTP is: <b>$otp</b>";

    $mail->send();
    echo json_encode(['message' => 'OTP sent to your email']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send OTP: ' . $e->getMessage()]);
}
?>