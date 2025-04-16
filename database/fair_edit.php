<?php
require 'db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $link = $conn->real_escape_string($_POST['link']);
    $date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : null; // Ensure the date is received
    $image = '';

    // Debugging: Log the received date
    error_log("Received Date in POST: " . $date);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../uploads/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $imageName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu!']);
            exit();
        }
    }

    // Update the fair in the database
    if (!empty($image)) {
        $sql = "UPDATE fair SET name = ?, description = ?, link = ?, image = ?, date = ? WHERE fair_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $name, $description, $link, $image, $date, $id);
    } else {
        $sql = "UPDATE fair SET name = ?, description = ?, link = ?, date = ? WHERE fair_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $name, $description, $link, $date, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>