<?php
// guest_endpoint.php

require 'middleware.php';

// Only guests can access this endpoint
$decoded = authenticate('guest');

// If the middleware passes, proceed with the request
echo json_encode([
    'message' => 'Welcome, Guest!',
    'user_id' => $decoded->user_id,
    'username' => $decoded->username
]);
?>