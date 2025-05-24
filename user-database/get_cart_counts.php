<?php
session_start();
include '../database/db_connection.php';

header('Content-Type: application/json');

$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$sessionID = session_id();

error_log("get_cart_count.php: Fetching cart count for userID: $userID, sessionID: $sessionID");

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_items 
    FROM cart 
    WHERE (ID_user = ? AND ID_user != 0) OR (session_ID = ? AND ID_user = 0)
");
$stmt->bind_param("is", $userID, $sessionID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $cartCount = $row['total_items'] ?? 0;
    error_log("get_cart_count.php: Cart count fetched: $cartCount");
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
} else {
    error_log("get_cart_count.php: Failed to fetch cart count.");
    echo json_encode(['success' => false, 'error' => 'Failed to fetch cart count.']);
}
?>