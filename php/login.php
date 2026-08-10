<?php

session_start();

require_once "database.php";

// Temporary login credentials for testing
$email = "john@example.com";
$password = "mypassword123";

// Find user by email
$stmt = $conn->prepare(
    "SELECT * FROM users WHERE email = ?"
);

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check whether user exists
if (!$user) {
    die("User not found.");
}

// Check password
if (!password_verify($password, $user['password'])) {
    die("Incorrect password.");
}

// Login successful
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

echo "Login successful!<br>";
echo "Welcome, " . $_SESSION['name'] . "<br>";
echo "Role: " . $_SESSION['role'];