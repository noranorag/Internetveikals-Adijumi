<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $link = $conn->real_escape_string($_POST['link']);
    $date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : null;
    $currentImage = $conn->real_escape_string($_POST['current_image'] ?? '');
    $image = $currentImage;

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $targetDir = "../images/";
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = "images/" . $imageName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu!']);
            exit();
        }
    }

    
    $sql = "UPDATE fair 
            SET name = ?, description = ?, link = ?, image = ?, date = ?, edited = NOW() 
            WHERE fair_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $name, $description, $link, $image, $date, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>