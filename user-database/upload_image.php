<?php
session_start();
require '../database/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Check if an image file is uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No image file uploaded.");
}

$user_id = $_SESSION['user_id'];
$target_dir = "../images/"; // Corrected path
$image_name = basename($_FILES['image']['name']);
$image_path = $target_dir . $image_name;
$uploaded_at = date('Y-m-d H:i:s');

// Ensure the target directory exists and is writable
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
}

if (!is_writable($target_dir)) {
    die("Error: Target directory is not writable.");
}

// Move the uploaded file to the target directory
if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
    die("Error: Failed to upload the image.");
}

// Insert the image into the `gallery_images` table
$query = "INSERT INTO gallery_images (image, uploaded_at) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $image_path, $uploaded_at);

if ($stmt->execute()) {
    $gallery_id = $stmt->insert_id;

    // Associate the image with the user in the `user_gallery` table
    $query = "INSERT INTO user_gallery (ID_user, ID_gallery) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $gallery_id);

    if ($stmt->execute()) {
        // Set a success message in the session
        $_SESSION['success_message'] = "Bilde ir veiksmīgi nosūtīta apstiprināšanai";
    } else {
        $_SESSION['error_message'] = "Error: Failed to associate the image with the user.";
    }
} else {
    $_SESSION['error_message'] = "Error: Failed to save the image in the gallery.";
}

$stmt->close();
$conn->close();

// Redirect back to gallery.php
header("Location: ../gallery.php");
exit();
?>