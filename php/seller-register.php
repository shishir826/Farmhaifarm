<?php

require_once "auth.php";
require_once "database.php";

$userId = $_SESSION['user_id'];

$shopName = "Test Farm Store";
$address = "Kathmandu";
$phone = "9800000000";

// Check if the user already has a seller application
$stmt = $conn->prepare(
    "SELECT id, status FROM sellers WHERE user_id = ?"
);

$stmt->execute([$userId]);

$existingSeller = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existingSeller) {
    die("You already have a seller application.");
}

// Create seller application
$stmt = $conn->prepare(
    "INSERT INTO sellers (user_id, shop_name, address, phone)
     VALUES (?, ?, ?, ?)"
);

$stmt->execute([
    $userId,
    $shopName,
    $address,
    $phone
]);

echo "Seller application submitted successfully.";