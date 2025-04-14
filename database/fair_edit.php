<?php
 require 'db_connection.php';
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $id = intval($_POST['id']);
     $name = $conn->real_escape_string($_POST['name']);
     $description = $conn->real_escape_string($_POST['description']);
     $link = $conn->real_escape_string($_POST['link']);
     $image = '';
 
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
 
     if (!empty($image)) {
         $sql = "UPDATE fair SET name = ?, description = ?, link = ?, image = ? WHERE fair_ID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param('ssssi', $name, $description, $link, $image, $id);
     } else {
         $sql = "UPDATE fair SET name = ?, description = ?, link = ? WHERE fair_ID = ?";
         $stmt = $conn->prepare($sql);
         $stmt->bind_param('sssi', $name, $description, $link, $id);
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