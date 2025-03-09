<?php
// messages.php

session_start();
require 'config.php';

// Fetch messages from the database
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <?php foreach ($messages as $message): ?>
        <div>
            <h3><?php echo htmlspecialchars($message['name']); ?></h3>
            <p><?php echo htmlspecialchars($message['email']); ?></p>
            <p><?php echo htmlspecialchars($message['message']); ?></p>
            <small><?php echo htmlspecialchars($message['created_at']); ?></small>
            <hr>
        </div>
    <?php endforeach; ?>
    <p><a href="contact.php">Back to Contact Form</a></p>
</body>
</html>