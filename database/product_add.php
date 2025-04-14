<?php
 include 'db_connection.php';
 session_start(); // Ensure this is called only once
 
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
     $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
 
     // Handle image upload
     $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = '../images/';
        $targetFile = $targetDir . $imageName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'images/' . $imageName; // Save the relative path without a leading slash
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās augšupielādēt attēlu.']);
            exit();
        }
    }
 
     // Insert query
     $sql = "INSERT INTO product (ID_category, ID_user, name, short_description, long_description, material, size, color, care, price, stock_quantity, image) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param(
         'iissssssddis',
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
         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'error' => $stmt->error]);
     }
 
     $stmt->close();
 }
 ?>