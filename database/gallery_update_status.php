<?php
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userGalleryId = $_POST['gallery_id'] ?? null; 
    $status = $_POST['status'] ?? null;

    // Log input values
    error_log("gallery_id: $userGalleryId, status: $status");

    if (!$userGalleryId || !$status) {
        error_log("Invalid input: gallery_id or status missing.");
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        exit();
    }

    // Validate the status
    $validStatuses = ['approved', 'denied', 'onhold']; // Updated "declined" to "denied"
    if (!in_array($status, $validStatuses)) {
        error_log("Invalid status: $status");
        echo json_encode(['success' => false, 'error' => 'Invalid status.']);
        exit();
    }

    // Update the status in the database
    $stmt = $conn->prepare("
        UPDATE gallery_images
        INNER JOIN user_gallery ON user_gallery.ID_gallery = gallery_images.gallery_ID
        SET gallery_images.approved = ?
        WHERE user_gallery.user_gallery_ID = ?
    ");
    $stmt->bind_param("si", $status, $userGalleryId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            error_log("Status updated successfully: gallery_id=$userGalleryId, status=$status");
            echo json_encode(['success' => true]);
        } else {
            error_log("No rows were updated for gallery_id=$userGalleryId.");
            echo json_encode(['success' => false, 'error' => 'No rows were updated.']);
        }
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>