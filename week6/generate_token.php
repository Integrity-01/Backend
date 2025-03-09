<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Define your secret key
$jwt_secret = "your_very_secret_key";

// Encode a JWT
$payload = [
    'iss' => "http://localhost", // Issuer
    'aud' => "http://localhost", // Audience
    'iat' => time(),             // Issued at
    'exp' => time() + 3600,      // Expiry (1 hour)
    'data' => [
        'user_id' => 1,
        'username' => 'admin',
        'role' => 'admin'
    ]
];

$jwt = JWT::encode($payload, $jwt_secret, 'HS256');
echo "Encoded JWT: " . $jwt . "\n";