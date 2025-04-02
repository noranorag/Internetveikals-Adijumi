<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $short_description = $conn->real_escape_string($_POST['short_description']);
    $long_description = $conn->real_escape_string($_POST['long_description']);
    $material = $conn->real_escape_string($_POST['material']);
    $size = $conn->real_escape_string($_POST['size']);
    $color = $conn->real_escape_string($_POST['color']);
    $care = $conn->real_escape_string($_POST['care']);
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $category_id = intval($_POST['category_id']);

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']); // Generate a unique filename
        $targetDir = '../images/';
        $targetFile = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = '/images/' . $imageName; // Save the relative path
            error_log("Image uploaded successfully: " . $imagePath); // Debugging log
        } else {
            error_log("Failed to upload image."); // Debugging log
        }
    } else {
        error_log("No image uploaded or an error occurred."); // Debugging log
    }

    // Update query
    $sql = "UPDATE product 
            SET name = ?, short_description = ?, long_description = ?, material = ?, size = ?, color = ?, care = ?, price = ?, stock_quantity = ?, ID_category = ?";
    if ($imagePath) {
        $sql .= ", image = ?";
    }
    $sql .= " WHERE product_ID = ?";
    $stmt = $conn->prepare($sql);

    if ($imagePath) {
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
    } else {
        $stmt->bind_param(
            'ssssssssiii',
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
            $id
        );
    }

    // Debugging: Log the SQL query and parameters
    error_log("SQL Query: " . $sql);
    error_log("Image Path: " . ($imagePath ?? 'NULL'));

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
?>