<?php
require 'database/db_connection.php';

try {
    // Update products where reserved is 1 and the reservation time exceeds 24 hours
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