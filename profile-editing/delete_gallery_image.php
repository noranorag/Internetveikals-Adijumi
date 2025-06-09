<?php
require '../database/db_connection.php';

header('Content-Type: application/json; charset=utf-8'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$galleryId = $_POST['gallery_ID'] ?? null;

if (!$galleryId) {
    echo json_encode(['success' => false, 'error' => 'Galerijas ID nav norādīts.']);
    exit();
}



try {
    $stmt = $conn->prepare("DELETE FROM gallery_images WHERE gallery_ID = ?");
    $stmt->bind_param('i', $galleryId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Neizdevās dzēst bildi.']);
    }
    

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Kļūda serverī: ' . $e->getMessage()]);
}

$conn->close();
?>