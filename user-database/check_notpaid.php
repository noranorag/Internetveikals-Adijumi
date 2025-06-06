<?php
include '../database/db_connection.php';

$thresholdTime = date('Y-m-d H:i:s', strtotime('-12 hours'));

$query = "
    UPDATE orders 
    SET status = 'Neapmaksāts' 
    WHERE status = 'Jauns' AND created_at <= ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $thresholdTime);
$stmt->execute();

$stmt->close();
?>