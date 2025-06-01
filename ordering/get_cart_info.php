<?php
session_start();
require '../database/db_connection.php';

header('Content-Type: application/json');

try {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Query to fetch cart data for logged-in users
        $sql = "SELECT 
                    c.quantity, 
                    p.name AS product_name, 
                    p.price AS product_price 
                FROM cart c
                INNER JOIN product p ON c.ID_product = p.product_ID
                WHERE c.ID_user = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $userId);
    } else {
        // Use session_id for guest users
        $sessionId = session_id();

        // Query to fetch cart data for guest users
        $sql = "SELECT 
                    c.quantity, 
                    p.name AS product_name, 
                    p.price AS product_price 
                FROM cart c
                INNER JOIN product p ON c.ID_product = p.product_ID
                WHERE c.session_ID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('s', $sessionId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    echo json_encode($cartItems); // Return the cart data as JSON
} catch (Exception $e) {
    // Log the error and return an error response
    error_log("Error in get_cart_info.php: " . $e->getMessage());
    echo json_encode(['error' => 'An error occurred while fetching cart items.']);
}
?>