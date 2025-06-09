<?php
require 'database/db_connection.php';

try {
    $query = "
        UPDATE product 
        SET reserved = 0 
        WHERE reserved = 1 AND TIMESTAMPDIFF(HOUR, reservation_time, NOW()) >= 24
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error in check_reserved.php: " . $e->getMessage());
}
?>