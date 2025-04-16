<?php
include 'db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
        exit();
    }

    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $short_description = $conn->real_escape_string($_POST['short_description'] ?? '');
    $long_description = $conn->real_escape_string($_POST['long_description'] ?? '');
    $material = $conn->real_escape_string($_POST['material'] ?? '');
    $size = $conn->real_escape_string($_POST['size'] ?? '');
    $color = $conn->real_escape_string($_POST['color'] ?? '');
    $care = $conn->real_escape_string($_POST['care'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);

    
    $current_image = $_POST['current_image'] ?? '';
    $imagePath = $current_image;

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $targetDir = '../images/';
        $targetFile = $targetDir . $imageName;

        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'images/' . $imageName; 
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to upload image.']);
            exit();
        }
    }

    
    $sql = "UPDATE product 
            SET name = ?, 
                short_description = ?, 
                long_description = ?, 
                material = ?, 
                size = ?, 
                color = ?, 
                care = ?, 
                price = ?, 
                stock_quantity = ?, 
                ID_category = ?, 
                image = ? 
            WHERE product_ID = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL statement.']);
        exit();
    }

    $stmt->bind_param(
        'ssssssssiiis',
        $name,
        $short_description,
        $long_description,
        $material,
        $size,
        $color,
        $care,
        $price,
        $stock_quantity,
        $category_id,
        $imagePath,
        $id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
?>