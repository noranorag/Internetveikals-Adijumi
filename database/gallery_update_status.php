<?php
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userGalleryId = $_POST['gallery_id'] ?? null; // This is the ID from the user_gallery table
    $status = $_POST['status'] ?? null;

    if (!$userGalleryId || !$status) {
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        exit();
    }

    // Validate status
    $validStatuses = ['approved', 'declined', 'onhold'];
    if (!in_array($status, $validStatuses)) {
        echo json_encode(['success' => false, 'error' => 'Invalid status.']);
        exit();
    }

    // Update the gallery_images table using a JOIN with user_gallery
    $stmt = $conn->prepare("
        UPDATE gallery_images
        INNER JOIN user_gallery ON user_gallery.ID_gallery = gallery_images.gallery_ID
        SET gallery_images.approved = ?
        WHERE user_gallery.user_gallery_ID = ?
    ");
    $stmt->bind_param("si", $status, $userGalleryId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No rows were updated.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>