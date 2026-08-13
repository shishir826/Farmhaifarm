<?php

session_start();

require_once "database.php";

header("Content-Type: application/json");


// =====================================================
// 1. CHECK IF USER IS LOGGED IN
// =====================================================

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login before placing an order."
    ]);

    exit;
}


// =====================================================
// 2. GET LOGGED-IN USER ID
// =====================================================

$userId = (int) $_SESSION["user_id"];


// =====================================================
// 3. GET CART DATA SENT FROM JAVASCRIPT
// =====================================================

$cartJson = $_POST["cart"] ?? "";

if ($cartJson === "") {

    echo json_encode([
        "success" => false,
        "message" => "Cart data was not received."
    ]);

    exit;
}


// Convert JSON string into PHP array
$cart = json_decode($cartJson, true);


// Check if JSON is valid
if (!is_array($cart)) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid cart data."
    ]);

    exit;
}


// Check if cart is empty
if (count($cart) === 0) {

    echo json_encode([
        "success" => false,
        "message" => "Your cart is empty."
    ]);

    exit;
}


// =====================================================
// 4. GET CUSTOMER DETAILS FROM USERS TABLE
// =====================================================

try {

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

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$user) {

        echo json_encode([
            "success" => false,
            "message" => "User account was not found."
        ]);

        exit;
    }


    // =================================================
    // 5. START DATABASE TRANSACTION
    // =================================================

    $conn->beginTransaction();


    $totalAmount = 0;

    $orderItems = [];


    // =================================================
    // 6. PROCESS CART ITEMS
    // =================================================

    foreach ($cart as $item) {

        // Get values directly from localStorage/cart
        $productId = (int) ($item["productId"] ?? 0);

        $productName = trim(
            $item["productName"] ?? ""
        );

        $quantity = (int) (
            $item["quantity"] ?? 0
        );

        $rate = (float) (
            $item["price"] ?? 0
        );


        // ---------------------------------------------
        // Basic validation
        // ---------------------------------------------

        if ($productId <= 0) {

            throw new Exception(
                "Invalid product ID."
            );
        }


        if ($productName === "") {

            throw new Exception(
                "Product name is missing."
            );
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


        // ---------------------------------------------
        // Calculate item amount
        // ---------------------------------------------

        $amount = $rate * $quantity;


        // Add to total
        $totalAmount += $amount;


        // Store item temporarily
        $orderItems[] = [

            "product_id" => $productId,

            "product_name" => $productName,

            "quantity" => $quantity,

            "rate" => $rate,

            "amount" => $amount

        ];
    }


    // =================================================
    // 7. INSERT ORDER INTO orders TABLE
    // =================================================

    $stmt = $conn->prepare("
        INSERT INTO orders
        (
            customer_id,
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


    // =================================================
    // 8. GET THE NEW ORDER ID
    // =================================================

    $orderId = $conn->lastInsertId();


    // =================================================
    // 9. INSERT CART ITEMS INTO order_items
    // =================================================

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


    // =================================================
    // 10. COMMIT EVERYTHING
    // =================================================

    $conn->commit();


    // =================================================
    // 11. SEND SUCCESS RESPONSE TO JAVASCRIPT
    // =================================================

    echo json_encode([

        "success" => true,

        "message" => "Order placed successfully.",

        "order_id" => $orderId,

        "total_amount" => $totalAmount

    ]);

} catch (Exception $e) {


    // =================================================
    // ROLLBACK IF SOMETHING FAILED
    // =================================================

    if ($conn->inTransaction()) {

        $conn->rollBack();
    }


    echo json_encode([

        "success" => false,

        "message" => $e->getMessage()

    ]);

}
?>