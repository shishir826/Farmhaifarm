<?php

session_start();

require_once "database.php";

header("Content-Type: application/json");


// ==========================================
// CHECK LOGIN
// ==========================================

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login before placing an order."
    ]);

    exit;
}


$userId = (int) $_SESSION["user_id"];


// ==========================================
// GET CART FROM JAVASCRIPT
// ==========================================

$cartJson = $_POST["cart"] ?? "";

if ($cartJson === "") {

    echo json_encode([
        "success" => false,
        "message" => "Cart data was not received."
    ]);

    exit;
}


$cart = json_decode($cartJson, true);


if (!is_array($cart) || empty($cart)) {

    echo json_encode([
        "success" => false,
        "message" => "Your cart is empty."
    ]);

    exit;
}


try {

    // ======================================
    // GET USER DETAILS
    // ======================================

    $stmt = $conn->prepare("
        SELECT
            id,
            name,
            email,
            phone,
            address
        FROM users
        WHERE id = ?
    ");

    $stmt->execute([$userId]);

    $user = $stmt->fetch();


    if (!$user) {

        echo json_encode([
            "success" => false,
            "message" => "User not found."
        ]);

        exit;
    }


    // ======================================
    // START TRANSACTION
    // ======================================

    $conn->beginTransaction();


    $totalAmount = 0;

    $orderItems = [];


    // ======================================
    // PROCESS CART
    // ======================================

    foreach ($cart as $item) {

        $productId = (int) ($item["productId"] ?? 0);

        $productName = trim(
            $item["productName"] ?? ""
        );

        $quantity = (int) (
            $item["quantity"] ?? 0
        );

        // Price comes directly from localStorage
        $rate = (float) (
            $item["price"] ?? 0
        );


        // -------------------------------
        // Basic validation
        // -------------------------------

        if ($productId <= 0) {
            throw new Exception("Invalid product ID.");
        }

        if ($productName === "") {
            throw new Exception("Product name is missing.");
        }

        if ($quantity <= 0) {
            throw new Exception(
                "Invalid quantity for " . $productName
            );
        }

        if ($rate < 0) {
            throw new Exception(
                "Invalid price for " . $productName
            );
        }


        // -------------------------------
        // Calculate item amount
        // -------------------------------

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


    // ======================================
    // INSERT INTO orders
    // ======================================

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
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
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


    // ======================================
    // GET NEW ORDER ID
    // ======================================

    $orderId = $conn->lastInsertId();


    // ======================================
    // INSERT INTO order_items
    // ======================================

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
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
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


    // ======================================
    // SAVE EVERYTHING
    // ======================================

    $conn->commit();


    // ======================================
    // SUCCESS
    // ======================================

    echo json_encode([

        "success" => true,

        "message" => "Order placed successfully.",

        "order_id" => $orderId,

        "total_amount" => $totalAmount

    ]);

} catch (Exception $e) {


    // ======================================
    // ROLLBACK
    // ======================================

    if ($conn->inTransaction()) {

        $conn->rollBack();
    }


    echo json_encode([

        "success" => false,

        "message" => $e->getMessage()

    ]);
}
?>