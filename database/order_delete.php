<?php
require 'db_connection.php';

if (isset($_POST['id'])) {
    $orderId = intval($_POST['id']);
    $query = $conn->prepare("DELETE FROM orders WHERE order_ID = ?");
    $query->bind_param("i", $orderId);

    if ($query->execute()) {
        if ($query->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No rows affected.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $query->error]);
    }

    $query->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>