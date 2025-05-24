<?php
session_start();
include '../database/db_connection.php';

header('Content-Type: application/json');

if (isset($_GET['product_ID'])) {
    $productID = intval($_GET['product_ID']);

    $stmt = $conn->prepare("SELECT name, image, price FROM product WHERE product_ID = ?");
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Product not found.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
}
?>