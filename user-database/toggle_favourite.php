<?php
include '../database/db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) { 
    error_log('User is not logged in.');
    echo json_encode(['success' => false, 'message' => 'Lūdzu, piesakieties, lai pievienotu favorītiem.']);
    exit;
}

$userID = $_SESSION['user_id']; 
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['product_ID'])) {
    error_log('Product ID not provided.');
    echo json_encode(['success' => false, 'message' => 'Produkta ID nav norādīts.']);
    exit;
}

$productID = $data['product_ID'];

error_log("toggle_favourite.php called for user_id: $userID, product_ID: $productID");

$query = "SELECT * FROM favourites WHERE user_ID = ? AND product_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $userID, $productID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    error_log("Product is already in favourites. Removing...");
    $query = "DELETE FROM favourites WHERE user_ID = ? AND product_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userID, $productID);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Favorīts noņemts.']);
} else {
    error_log("Product is not in favourites. Adding...");
    $query = "INSERT INTO favourites (user_ID, product_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userID, $productID);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Favorīts pievienots.']);
}
?>