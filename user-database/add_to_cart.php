<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../database/db_connection.php';

header('Content-Type: application/json');

// Decode the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_ID'], $data['quantity'])) {
    $productID = intval($data['product_ID']);
    $quantity = intval($data['quantity']);
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; 
    $sessionID = session_id();

    error_log("User ID: " . ($userID ?? 'NULL'));
    error_log("Session ID: " . $sessionID);

    if ($userID) {
        // Logged-in user: Use user_ID for cart operations
        $stmt = $conn->prepare("
            SELECT * FROM cart 
            WHERE ID_user = ? AND ID_product = ?
        ");
        $stmt->bind_param("ii", $userID, $productID);
    } else {
        // Non-logged-in user: Use session_ID for cart operations
        $stmt = $conn->prepare("
            SELECT * FROM cart 
            WHERE session_ID = ? AND ID_product = ?
        ");
        $stmt->bind_param("si", $sessionID, $productID);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product already exists in the cart, update quantity
        error_log("Product already in cart. Updating quantity.");
        if ($userID) {
            $stmt = $conn->prepare("
                UPDATE cart 
                SET quantity = quantity + ? 
                WHERE ID_user = ? AND ID_product = ?
            ");
            $stmt->bind_param("iii", $quantity, $userID, $productID);
        } else {
            $stmt = $conn->prepare("
                UPDATE cart 
                SET quantity = quantity + ? 
                WHERE session_ID = ? AND ID_product = ?
            ");
            $stmt->bind_param("isi", $quantity, $sessionID, $productID);
        }
        $stmt->execute();
    } else {
        // Product not in cart, add new entry
        error_log("Product not in cart. Adding new entry.");
        if ($userID) {
            $stmt = $conn->prepare("
                INSERT INTO cart (ID_user, ID_product, quantity, added_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->bind_param("iii", $userID, $productID, $quantity);
        } else {
            $stmt = $conn->prepare("
                INSERT INTO cart (session_ID, ID_product, quantity, added_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->bind_param("sii", $sessionID, $productID, $quantity);
        }
        $stmt->execute();
    }

    // Calculate total cart quantity
    if ($userID) {
        $stmt = $conn->prepare("
            SELECT SUM(quantity) AS total_quantity 
            FROM cart 
            WHERE ID_user = ?
        ");
        $stmt->bind_param("i", $userID);
    } else {
        $stmt = $conn->prepare("
            SELECT SUM(quantity) AS total_quantity 
            FROM cart 
            WHERE session_ID = ?
        ");
        $stmt->bind_param("s", $sessionID);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $cartCount = 0;
    if ($row = $result->fetch_assoc()) {
        $cartCount = $row['total_quantity'] ?? 0;
    }

    error_log("Cart count: " . $cartCount);

    // Return success response with updated cart count
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
} else {
    // Invalid input
    error_log("Invalid input: " . print_r($data, true));
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}
?>