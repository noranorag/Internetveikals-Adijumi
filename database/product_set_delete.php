<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['id_product']) ? intval($_POST['id_product']) : 0;
    $setId = isset($_POST['id_set']) ? intval($_POST['id_set']) : 0;

    if ($productId > 0 && $setId > 0) {
        $query = "UPDATE product_sets SET active = 'deleted' WHERE ID_product = ? AND ID_set = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $productId, $setId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete the product from the set.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid product or set ID.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}

$conn->close();
?>