<?php
require 'db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    error_log("Fair ID received in fair_edit.php: $id");

    if ($id <= 0) {
        error_log("Invalid ID received: $id");
        echo json_encode(['success' => false, 'error' => 'Invalid fair ID.']);
        exit();
    }

    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $link = $conn->real_escape_string($_POST['link']);
    $date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : null;
    $currentImage = $conn->real_escape_string($_POST['current_image'] ?? '');
    $image = $currentImage; // Default to the current image

    // Debugging: Log received data
    error_log("Received data: ID=$id, Name=$name, Description=$description, Link=$link, Date=$date, CurrentImage=$currentImage");

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $targetDir = "../images/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = "images/" . $imageName; // Update the image path
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu!']);
            exit();
        }
    }

    // Debugging: Log the final image path
    error_log("Final image path: $image");

    // Update the database
    $sql = "UPDATE fair 
            SET name = ?, description = ?, link = ?, image = ?, date = ? 
            WHERE fair_ID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("SQL prepare error: " . $conn->error); // Debugging: Log SQL errors
        echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL statement.']);
        exit();
    }

    $stmt->bind_param('sssssi', $name, $description, $link, $image, $date, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("SQL execution error: " . $stmt->error); // Debugging: Log SQL execution errors
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>