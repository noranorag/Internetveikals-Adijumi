<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit();
}

if (!isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'No image file provided.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$target_dir = "../images/";
$image_name = basename($_FILES['image']['name']);
$target_file = $target_dir . $image_name;
$image_path = "images/" . $image_name;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
    echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
    exit();
}

$query = "INSERT INTO gallery_images (image) VALUES (?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $image_path);

if ($stmt->execute()) {
    $gallery_id = $stmt->insert_id;

    $query = "INSERT INTO user_gallery (ID_user, ID_gallery) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $gallery_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Gallery image added successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to associate image with user.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to add image to gallery.']);
}

$stmt->close();
$conn->close();
?>