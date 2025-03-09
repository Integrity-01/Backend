<?php
// config.php

$host = 'localhost';
$dbname = 'jwt_system'; // Database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// JWT Secret Key (keep this secure in a real application)
define('JWT_SECRET', 'your-secret-key');
?>