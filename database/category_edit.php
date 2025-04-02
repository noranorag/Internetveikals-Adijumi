<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $big_category = $conn->real_escape_string($_POST['big_category']);

    $sql = "UPDATE category SET name = ?, big_category = ? WHERE category_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $name, $big_category, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No rows affected.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
?>