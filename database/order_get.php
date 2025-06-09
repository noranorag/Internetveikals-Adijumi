<?php
require 'db_connection.php';

$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($orderId > 0) {
    $query = "SELECT * FROM orders WHERE order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $orderResult = $stmt->get_result();

    if ($orderResult->num_rows > 0) {
        $order = $orderResult->fetch_assoc();

        $order['delivery_number'] = $order['delivery_number'];

        $itemsQuery = "SELECT oi.ID_product, oi.quantity, oi.price, p.name AS product_name
                       FROM order_items oi
                       JOIN product p ON oi.ID_product = p.product_ID
                       WHERE oi.ID_order = ?";
        $itemsStmt = $conn->prepare($itemsQuery);
        $itemsStmt->bind_param('i', $orderId);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();

        $items = [];
        while ($item = $itemsResult->fetch_assoc()) {
            $items[] = $item;
        }

        $order['items'] = $items;

        echo json_encode(['success' => true, 'order' => $order]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Pasūtījums netika atrasts!']);
    }

    $stmt->close();
    $itemsStmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Nederīgs pasūtījuma ID!']);
}

$conn->close();
?>