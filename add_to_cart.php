
<?php
session_start();
include 'database/db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_ID'], $data['quantity'])) {
    $productID = intval($data['product_ID']);
    $quantity = intval($data['quantity']);
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sessionID = session_id();

    // Check if the specific product is already in the cart
    $stmt = $conn->prepare("
        SELECT * FROM cart 
        WHERE ((ID_user = ? AND ID_user != 0) OR (session_ID = ? AND ID_user = 0)) 
        AND ID_product = ?
    ");
    $stmt->bind_param("isi", $userID, $sessionID, $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if the specific product is already in the cart
        $stmt = $conn->prepare("
            UPDATE cart 
            SET quantity = quantity + ? 
            WHERE ((ID_user = ? AND ID_user != 0) OR (session_ID = ? AND ID_user = 0)) 
            AND ID_product = ?
        ");
        $stmt->bind_param("iisi", $quantity, $userID, $sessionID, $productID);
    } else {
        // Insert new product into the cart
        $stmt = $conn->prepare("
            INSERT INTO cart (session_ID, ID_user, ID_product, quantity, added_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $sessionIDValue = $userID === 0 ? $sessionID : null;
        $userIDValue = $userID !== 0 ? $userID : null;
        $stmt->bind_param("siii", $sessionIDValue, $userIDValue, $productID, $quantity);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}
?>