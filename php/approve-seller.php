<?php

require_once "role.php";
require_once "database.php";

requireRole("admin");

$sellerId = 1; // temporary test seller ID

// Find seller application
$stmt = $conn->prepare(
    "SELECT * FROM sellers WHERE id = ?"
);

$stmt->execute([$sellerId]);

$seller = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seller) {
    die("Seller application not found.");
}

if ($seller['status'] !== 'pending') {
    die("This seller application has already been processed.");
}

// Update seller status
$stmt = $conn->prepare(
    "UPDATE sellers
     SET status = 'approved'
     WHERE id = ?"
);

$stmt->execute([$sellerId]);

// Change user's role to seller
$stmt = $conn->prepare(
    "UPDATE users
     SET role = 'seller'
     WHERE id = ?
);

$stmt->execute([$seller['user_id']]);

echo "Seller approved successfully.";