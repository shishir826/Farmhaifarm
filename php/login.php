<?php

session_start();

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method.");
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    die("Email and password are required.");
}

// Find user by email
$stmt = $conn->prepare(
    "SELECT * FROM users WHERE email = ?"
);

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Invalid email or password.");
}

// Check password
if (!password_verify($password, $user['password'])) {
    die("Invalid email or password.");
}

// Create a new session ID
session_regenerate_id(true);

// Store user information in session
$_SESSION['user_id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

echo "Login successful!";