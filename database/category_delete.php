<?php
     require 'db_connection.php';
     if(isset($_POST['id'])) {
         $id = intval($_POST['id']);
         $query = $conn->prepare("UPDATE category SET active = 'deleted' WHERE category_ID = ?");
         $query->bind_param("i", $id);
 
         if($query->execute()) {
           
         } else {
            
         }
 
         $query->close();
         $conn->close();
     }
 ?>