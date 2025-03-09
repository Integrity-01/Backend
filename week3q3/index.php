<?php
// Set SameSite cookie attributes BEFORE starting the session
session_set_cookie_params([
    'secure' => true,   // Only send over HTTPS (set to false for local testing)
    'httponly' => true, // Prevent JavaScript access
    'samesite' => 'Lax' // Prevent cross-site requests
]);

// Start the session AFTER setting cookie parameters
session_start();

// Regenerate session ID to prevent fixation attacks
if (session_status() === PHP_SESSION_ACTIVE) {
    session_regenerate_id(true);
}

// Generate a CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random 64-character token
}

$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Protected Form</title>
</head>
<body>
    <h2>Submit Your Message</h2>
    <form action="submit.php" method="post">
        <!-- Include the CSRF token as a hidden input -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        Name: <input type="text" name="name" required><br>
        Message: <textarea name="message" required></textarea><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>