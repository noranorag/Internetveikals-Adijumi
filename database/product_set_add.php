<?php
require 'db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_product = isset($_POST['id_product']) ? intval($_POST['id_product']) : null;
    $id_set = isset($_POST['id_set']) ? intval($_POST['id_set']) : null;

    if (!$id_product || !$id_set) {
        echo json_encode(['success' => false, 'error' => 'Invalid product or set ID.']);
        exit();
    }

    // Corrected table name
    $query = "INSERT INTO product_sets (ID_product, ID_set) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ii', $id_product, $id_set);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added to the set successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add product to the set.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Database query preparation failed.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>