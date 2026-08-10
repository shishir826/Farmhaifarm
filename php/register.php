<?php

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

// Check whether email already exists
$stmt = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->execute([$email]);

if ($stmt->fetch()) {
    die("Email already exists.");
}

// Hash password
$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

// Create customer account
$role = "customer";

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