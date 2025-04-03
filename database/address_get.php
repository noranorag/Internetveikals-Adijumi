<?php
require 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT address_ID, country, city, street, house, apartment, postal_code FROM address WHERE address_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Adrese netika atrasta!']);
    }

    $stmt->close();
}

mysqli_close($conn);
?>