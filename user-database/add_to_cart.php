<?php
session_start();
include '../database/db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_ID'], $data['quantity'])) {
    $productID = intval($data['product_ID']);
    $quantity = intval($data['quantity']);
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Set to NULL for guest users
    $sessionID = session_id();

    error_log("add_to_cart.php: Received product_ID: $productID, quantity: $quantity, userID: $userID, sessionID: $sessionID");

    // Check if the specific product is already in the cart
    $stmt = $conn->prepare("
        SELECT * FROM cart 
        WHERE ((ID_user = ? AND ID_user IS NOT NULL) OR (session_ID = ? AND ID_user IS NULL)) 
        AND ID_product = ?
    ");
    $stmt->bind_param("ssi", $userID, $sessionID, $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if the specific product is already in the cart
        $stmt = $conn->prepare("
            UPDATE cart 
            SET quantity = quantity + ? 
            WHERE ((ID_user = ? AND ID_user IS NOT NULL) OR (session_ID = ? AND ID_user IS NULL)) 
            AND ID_product = ?
        ");
        $stmt->bind_param("issi", $quantity, $userID, $sessionID, $productID);
        $stmt->execute();
        error_log("add_to_cart.php: Updated cart for product ID: $productID, quantity: $quantity");
    } else {
        // Insert new product into the cart
        $stmt = $conn->prepare("
            INSERT INTO cart (session_ID, ID_user, ID_product, quantity, added_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssii", $sessionID, $userID, $productID, $quantity);
        $stmt->execute();
        error_log("add_to_cart.php: Inserted into cart: product ID: $productID, quantity: $quantity");
    }

    echo json_encode(['success' => true]);
} else {
    error_log("add_to_cart.php: Invalid input received.");
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}
?>