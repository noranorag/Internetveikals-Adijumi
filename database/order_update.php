<?php
require 'db_connection.php';

$orderId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';
$deliveryNumber = isset($_POST['delivery_number']) ? $conn->real_escape_string($_POST['delivery_number']) : null;

if ($orderId > 0 && !empty($status)) {
    $currentStatusQuery = "SELECT status FROM orders WHERE order_ID = ?";
    $stmtCurrentStatus = $conn->prepare($currentStatusQuery);
    $stmtCurrentStatus->bind_param('i', $orderId);
    $stmtCurrentStatus->execute();
    $currentStatusResult = $stmtCurrentStatus->get_result();
    $currentStatus = $currentStatusResult->fetch_assoc()['status'];

    $query = "UPDATE orders SET status = ?, delivery_number = ? WHERE order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $status, $deliveryNumber, $orderId);

    if ($stmt->execute()) {
        if ($currentStatus === 'Jauns' && $status === 'Pieņemts') {
            $orderItemsQuery = "SELECT ID_product, quantity FROM order_items WHERE ID_order = ?";
            $stmtOrderItems = $conn->prepare($orderItemsQuery);
            $stmtOrderItems->bind_param('i', $orderId);
            $stmtOrderItems->execute();
            $orderItemsResult = $stmtOrderItems->get_result();

            while ($item = $orderItemsResult->fetch_assoc()) {
                $productId = $item['ID_product'];
                $quantity = $item['quantity'];

                $updateProductQuery = "UPDATE product SET reserved = 0, stock_quantity = stock_quantity - ? WHERE product_ID = ?";
                $stmtUpdateProduct = $conn->prepare($updateProductQuery);
                $stmtUpdateProduct->bind_param('ii', $quantity, $productId);
                $stmtUpdateProduct->execute();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Pasūtījuma statuss veiksmīgi atjaunināts!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Neizdevās atjaunināt pasūtījuma statusu!']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Nepareizi ievadīti dati!']);
}

$conn->close();
?>