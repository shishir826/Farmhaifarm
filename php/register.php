<?php

require_once "database.php";

$name = "John Doe";
$email = "john@example.com";
$password = "mypassword123";
$role = "customer";

// Check whether email already exists
$stmt = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->execute([$email]);

if ($stmt->fetch()) {
    die("Email already exists.");
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password, role)
     VALUES (?, ?, ?, ?)"
);

$stmt->execute([
    $name,
    $email,
    $hashedPassword,
    $role
]);

echo "User registered successfully!";