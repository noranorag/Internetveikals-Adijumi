<?php
require 'db_connection.php';

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    exit();
}

$imageId = intval($_POST['id']);
$status = $_POST['status'];

if (!in_array($status, ['approved', 'declined'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid status.']);
    exit();
}

$query = "UPDATE gallery_images SET approved = ? WHERE gallery_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $imageId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update status.']);
}

$stmt->close();
$conn->close();
?>