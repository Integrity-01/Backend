<?php
session_start();

// GitHub OAuth credentials
$clientID = 'Ov23liAkvER6P3nYnm2x'; // Your GitHub Client ID
$clientSecret = 'f0f1ff3f46625da7f532f66d52255e16c7e279fb'; // Your GitHub Client Secret
$redirectURI = 'http://localhost/week6q2/github_callback.php'; // Updated callback URL

// Step 1: Handle the OAuth callback
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange authorization code for access token
    $tokenURL = 'https://github.com/login/oauth/access_token';
    $tokenParams = [
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'code' => $code,
        'redirect_uri' => $redirectURI,
    ];

    $ch = curl_init($tokenURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenParams));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);
    if (isset($tokenData['access_token'])) {
        // Step 2: Fetch user info using the access token
        $userInfoURL = 'https://api.github.com/user';
        $ch = curl_init($userInfoURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $tokenData['access_token'],
            'User-Agent: MyApp', // GitHub requires a user agent
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $userInfo = curl_exec($ch);
        curl_close($ch);

        $userData = json_decode($userInfo, true);
        if (isset($userData['login'])) {
            // Save user data in session
            $_SESSION['user'] = [
                'username' => $userData['login'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'avatar' => $userData['avatar_url'],
            ];

            // Redirect to the home page or dashboard
            header('Location: http://localhost/week6q2/home.php');
            exit;
        }
    }
}

// If something goes wrong, redirect to the login page
header('Location: http://localhost/week6q2/github_login.php');
exit;
?>