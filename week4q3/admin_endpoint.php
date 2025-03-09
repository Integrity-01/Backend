<?php
// admin_endpoint.php

require 'middleware.php';

// Only admins can access this endpoint
$decoded = authenticate('admin');

// If the middleware passes, proceed with the request
echo json_encode([
    'message' => 'Welcome, Admin!',
    'user_id' => $decoded->user_id,
    'username' => $decoded->username
]);
?>