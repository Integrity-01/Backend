<?php
// File to store user data
$usersFile = 'users.json';

// Load users from the file
function loadUsers() {
    global $usersFile;
    if (file_exists($usersFile)) {
        $data = file_get_contents($usersFile);
        return json_decode($data, true) ?? [];
    }
    return [];
}

// Save users to the file
function saveUsers($users) {
    global $usersFile;
    file_put_contents($usersFile, json_encode($users));
}

// Function to validate password
function validatePassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Function to enforce password policies
function isPasswordStrong($password) {
    // Minimum 8 characters, at least 1 uppercase, 1 lowercase, 1 number, and 1 special character
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to get user by username
function getUser($username) {
    $users = loadUsers();
    return $users[$username] ?? null;
}

// Function to save user
function saveUser($username, $hashedPassword) {
    $users = loadUsers();
    $users[$username] = [
        'password' => $hashedPassword,
        'failed_attempts' => 0,
        'lockout_time' => 0,
    ];
    saveUsers($users);
}
?>