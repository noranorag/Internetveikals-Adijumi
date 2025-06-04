<?php
require 'db_connection.php';

$orderId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

if ($orderId > 0 && !empty($status)) {
    $query = "UPDATE orders SET status = ? WHERE order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $orderId);

    if ($stmt->execute()) {
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