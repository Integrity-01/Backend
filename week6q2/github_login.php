<?php
session_start();

// GitHub OAuth credentials
$clientID = 'Ov23liAkvER6P3nYnm2x'; // Your GitHub Client ID
$clientSecret = 'f0f1ff3f46625da7f532f66d52255e16c7e279fb'; // Your GitHub Client Secret
$redirectURI = 'http://localhost/week6q2/github_callback.php'; // Updated callback URL

// Step 1: Redirect user to GitHub's OAuth server
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['code'])) {
    $authURL = 'https://github.com/login/oauth/authorize';
    $params = [
        'client_id' => $clientID,
        'redirect_uri' => $redirectURI,
        'scope' => 'user', // Request access to user data
        'state' => bin2hex(random_bytes(16)), // CSRF protection
    ];
    $authURL .= '?' . http_build_query($params);
    header('Location: ' . $authURL);
    exit;
}

// Step 2: Display login link
echo '<a href="http://localhost/week6q2/github_login.php">Login with GitHub</a>';
?>