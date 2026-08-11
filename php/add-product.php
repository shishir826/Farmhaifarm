<?php

require_once "auth.php";
require_once "database.php";

// Make sure the logged-in user is a seller
if ($_SESSION['role'] !== 'seller') {
    http_response_code(403);
    die("Only sellers can add products.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = $_POST['price'] ?? '';
$stock = $_POST['stock'] ?? '';

if ($name === '' || $price === '' || $stock === '') {
    die("Name, price and stock are required.");
}

if (!is_numeric($price) || $price < 0) {
    die("Invalid price.");
}

if (!is_numeric($stock) || $stock < 0) {
    die("Invalid stock.");
}

// Find the seller belonging to the logged-in user
$stmt = $conn->prepare(
    "SELECT id FROM sellers
     WHERE user_id = ? AND status = 'approved'"
);

$stmt->execute([$_SESSION['user_id']]);

$seller = $stmt->fetch();

if (!$seller) {
    die("You are not an approved seller.");
}

$sellerId = $seller['id'];

// Add product
$stmt = $conn->prepare(
    "INSERT INTO products
     (seller_id, name, description, price, stock)
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->execute([
    $sellerId,
    $name,
    $description,
    $price,
    $stock
]);

echo "Product added successfully!";