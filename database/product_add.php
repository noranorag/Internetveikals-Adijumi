<?php
include 'db_connection.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $user_id = $_SESSION['user_id'];


    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = '../images/';
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'images/' . $imageName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu.']);
            exit();
        }
    }

    $sql = "INSERT INTO product (ID_category, ID_user, name, short_description, long_description, material, size, color, care, price, stock_quantity, image) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log('SQL prepare error: ' . $conn->error);
        echo json_encode(['success' => false, 'error' => 'Failed to prepare SQL statement.']);
        exit();
    }

    $stmt->bind_param(
        'iisssssssdis',
        $category_id,
        $user_id,
        $name,
        $short_description,
        $long_description,
        $material,
        $size,
        $color,
        $care, 
        $price,
        $stock_quantity,
        $imagePath
    );

    if ($stmt->execute()) {
        error_log('Response: {"success": true}');
        echo json_encode(['success' => true]);
    } else {
        error_log('Response: {"success": false, "error": "' . $stmt->error . '"}');
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    error_log('SQL Query: ' . $sql);
error_log('Parameters: category_id=' . $category_id . ', user_id=' . $user_id . ', name=' . $name . ', short_description=' . $short_description . ', long_description=' . $long_description . ', material=' . $material . ', size=' . $size . ', color=' . $color . ', care=' . $care . ', price=' . $price . ', stock_quantity=' . $stock_quantity . ', imagePath=' . $imagePath);

    $stmt->close();
}