<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setId = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($setId > 0) {
        $query = "UPDATE sets SET active = 'deleted' WHERE set_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $setId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete the set.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid set ID.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}

$conn->close();
?>