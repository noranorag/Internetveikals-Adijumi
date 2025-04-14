<?php
 include 'db_connection.php';
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $id = intval($_POST['id']);
 
     $sql = "DELETE FROM product WHERE product_ID = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param('i', $id);
 
     if ($stmt->execute()) {
         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'error' => $stmt->error]);
     }
 
     $stmt->close();
 }
 ?>