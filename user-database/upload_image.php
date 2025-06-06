<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No image file uploaded.");
}

$user_id = $_SESSION['user_id'];
$target_dir = "images/"; // Updated to remove "../"
$image_name = basename($_FILES['image']['name']);
$image_path = $target_dir . $image_name;
$uploaded_at = date('Y-m-d H:i:s');

if (!is_dir("../" . $target_dir)) { // Ensure the directory exists
    mkdir("../" . $target_dir, 0777, true); 
}

if (!is_writable("../" . $target_dir)) { // Check if the directory is writable
    die("Error: Target directory is not writable.");
}

if (!move_uploaded_file($_FILES['image']['tmp_name'], "../" . $image_path)) { // Adjust path for file upload
    die("Error: Failed to upload the image.");
}

$query = "INSERT INTO gallery_images (image, uploaded_at) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $image_path, $uploaded_at);

if ($stmt->execute()) {
    $gallery_id = $stmt->insert_id;

    $query = "INSERT INTO user_gallery (ID_user, ID_gallery) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $gallery_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Bilde ir veiksmīgi nosūtīta apstiprināšanai";
    } else {
        $_SESSION['error_message'] = "Error: Failed to associate the image with the user.";
    }
} else {
    $_SESSION['error_message'] = "Error: Failed to save the image in the gallery.";
}

$stmt->close();
$conn->close();

header("Location: ../gallery.php");
exit();
?>