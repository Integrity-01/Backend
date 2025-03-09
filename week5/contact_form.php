<?php
// contact_form.php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit();
    }

    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $message = strip_tags($message); // Remove HTML tags

    // Process the message (e.g., save to a file or send an email)
    echo json_encode(['message' => 'Message received: ' . $message]);
} else {
    $csrfToken = bin2hex(random_bytes(32)); // Generate CSRF token
    $_SESSION['csrf_token'] = $csrfToken;

    // Display the form
    echo "<form method='POST'>
            <input type='hidden' name='csrf_token' value='$csrfToken'>
            <textarea name='message'></textarea>
            <button type='submit'>Submit</button>
          </form>";
}
?>