<?php

session_start();

require_once "database.php";

header("Content-Type: application/json");

try {

    // Check session
    if (!isset($_SESSION["user_id"])) {
        throw new Exception("SESSION ERROR: user_id is not set.");
    }

    $userId = (int) $_SESSION["user_id"];


    // Check cart
    if (!isset($_POST["cart"])) {
        throw new Exception("CART ERROR: cart was not received.");
    }

    $cart = json_decode($_POST["cart"], true);

    if (!is_array($cart)) {
        throw new Exception(
            "CART ERROR: invalid JSON. Received: " . $_POST["cart"]
        );
    }

    if (empty($cart)) {
        throw new Exception("CART ERROR: cart is empty.");
    }


    // Get user
    $stmt = $conn->prepare("
        SELECT id, name, email, phone, address
        FROM users
        WHERE id = ?
    ");

    $stmt->execute([$userId]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception(
            "USER ERROR: no user found for ID " . $userId
        );
    }


    $conn->beginTransaction();

    $totalAmount = 0;
    $orderItems = [];


    // Process cart
    foreach ($cart as $item) {

        $productId = (int)($item["productId"] ?? 0);
        $productName = trim($item["productName"] ?? "");
        $quantity = (int)($item["quantity"] ?? 0);
        $rate = (float)($item["price"] ?? 0);

        if ($productId <= 0) {
            throw new Exception("PRODUCT ERROR: invalid product ID.");
        }

        if ($productName === "") {
            throw new Exception("PRODUCT ERROR: product name missing.");
        }

        if ($quantity <= 0) {
            throw new Exception(
                "QUANTITY ERROR: invalid quantity for " . $productName
            );
        }

        $amount = $rate * $quantity;

        $totalAmount += $amount;

        $orderItems[] = [
            "product_id" => $productId,
            "product_name" => $productName,
            "quantity" => $quantity,
            "rate" => $rate,
            "amount" => $amount
        ];
    }


    // Insert order
    $stmt = $conn->prepare("
        INSERT INTO orders
        (
            user_id,
            customer_name,
            customer_email,
            customer_phone,
            delivery_address,
            total_amount,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $user["id"],
        $user["name"],
        $user["email"],
        $user["phone"],
        $user["address"],
        $totalAmount,
        "Pending"
    ]);


    $orderId = $conn->lastInsertId();


    // Insert order items
    $stmt = $conn->prepare("
        INSERT INTO order_items
        (
            order_id,
            product_id,
            product_name,
            quantity,
            rate,
            amount
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");


    foreach ($orderItems as $item) {

        $stmt->execute([
            $orderId,
            $item["product_id"],
            $item["product_name"],
            $item["quantity"],
            $item["rate"],
            $item["amount"]
        ]);
    }


    $conn->commit();


    echo json_encode([
        "success" => true,
        "message" => "Order placed successfully.",
        "order_id" => $orderId,
        "total_amount" => $totalAmount
    ]);

} catch (Throwable $e) {

    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}