<?php
// contact.php

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags($_POST['name']); // Remove HTML tags
    $email = strip_tags($_POST['email']); // Remove HTML tags
    $message = htmlspecialchars($_POST['message']); // Convert special characters to HTML entities

    // Insert the message into the database
    $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);

    echo "Message sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <label for="message">Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea><br><br>
        <button type="submit">Submit</button>
    </form>
    <p><a href="messages.php">View Messages</a></p>
</body>
</html>