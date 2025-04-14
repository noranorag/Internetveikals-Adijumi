<?php
 include 'db_connection.php';
 
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $name = $conn->real_escape_string($_POST['name']);
     $big_category = $conn->real_escape_string($_POST['big_category']);
 
     $sql = "INSERT INTO category (name, big_category) VALUES (?, ?)";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param('ss', $name, $big_category);
 
     if ($stmt->execute()) {
         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'error' => $stmt->error]);
     }
     $stmt->close();
 }
 ?>