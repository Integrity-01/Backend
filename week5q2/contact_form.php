<?php
// contact_form.php
session_start(); // Start the session to store the CSRF token

header('Content-Type: application/json'); // Set the response type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403); // Forbidden
        echo json_encode(['error' => 'Invalid CSRF token']);
        exit();
    }

    // Sanitize user input to prevent XSS
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $message = strip_tags($_POST['message']); // Remove HTML tags

    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'All fields are required']);
        exit();
    }

    // Process the message (e.g., save to database or send email)
    // For now, we'll just return a success message
    echo json_encode(['message' => 'Message received', 'name' => $name, 'email' => $email, 'message' => $message]);
} else {
    // Generate CSRF token
    $csrfToken = bin2hex(random_bytes(32)); // Generate a random token
    $_SESSION['csrf_token'] = $csrfToken; // Store token in session

    // Display the form
    echo "<form method='POST'>
            <input type='hidden' name='csrf_token' value='$csrfToken'>
            <label for='name'>Name:</label>
            <input type='text' name='name' id='name' required><br>
            <label for='email'>Email:</label>
            <input type='email' name='email' id='email' required><br>
            <label for='message'>Message:</label>
            <textarea name='message' id='message' required></textarea><br>
            <button type='submit'>Submit</button>
          </form>";
}
?>