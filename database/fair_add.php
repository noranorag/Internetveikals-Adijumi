<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $link = $conn->real_escape_string($_POST['link']);
    $date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : null; 
    $image = '';

    
    if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) { 
        echo json_encode(['success' => false, 'error' => 'Invalid date provided.']);
        exit();
    }

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../images/"; 
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = "images/" . $imageName; 
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu!']);
            exit();
        }
    }

    
    $sql = "INSERT INTO fair (name, description, link, image, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param('sssss', $name, $description, $link, $image, $date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>