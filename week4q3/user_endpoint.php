<?php
// user_endpoint.php

require 'middleware.php';

// Only users can access this endpoint
$decoded = authenticate('user');

// If the middleware passes, proceed with the request
echo json_encode([
    'message' => 'Welcome, User!',
    'user_id' => $decoded->user_id,
    'username' => $decoded->username
]);
?>