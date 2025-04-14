<?php
 require 'db_connection.php';
 
 if (isset($_POST['id'])) {
     $id = intval($_POST['id']);
 
     // Update the active column to 'deleted'
     $query = $conn->prepare("UPDATE fair SET active = 'deleted' WHERE fair_ID = ?");
     $query->bind_param("i", $id);
 
     if ($query->execute()) {
         echo json_encode(['success' => true]);
     } else {
         echo json_encode(['success' => false, 'error' => 'Neizdevās dzēst tirdziņu!']);
     }
 
     $query->close();
 }
 
 $conn->close();
 ?>