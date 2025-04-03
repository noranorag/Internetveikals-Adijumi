<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM product WHERE product_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        $product['image'] = htmlspecialchars($product['image']); 

        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Prece netika atrasta!']);
    }

    $stmt->close();
}
?>