<?php
session_start();

// Validate the CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid CSRF token. Possible CSRF attack detected.");
}

// Process the form data securely
$name = htmlspecialchars(trim($_POST['name']));
$message = htmlspecialchars(trim($_POST['message']));

// Log the message (or save it to a database)
$log_message = "Name: $name, Message: $message\n";
file_put_contents('messages.log', $log_message, FILE_APPEND);

// Clear the old CSRF token and generate a new one
unset($_SESSION['csrf_token']);
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

echo "Message submitted successfully!";
?>