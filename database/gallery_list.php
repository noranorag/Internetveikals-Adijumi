<?php
 require 'db_connection.php';
 
 // Query to join user_gallery, gallery_images, and user tables
 $query = "
     SELECT 
         ug.user_gallery_ID AS id,
         gi.gallery_ID AS gallery_id,
         gi.image AS image_path,
         gi.uploaded_at,
         gi.approved AS status,
         u.email AS posted_by
     FROM 
         user_gallery ug
     INNER JOIN 
         gallery_images gi ON ug.ID_gallery = gi.gallery_ID
     INNER JOIN 
         user u ON ug.ID_user = u.user_ID
     WHERE 
         gi.approved IN ('onhold', 'approved', 'declined') -- Include all relevant statuses
     ORDER BY 
         FIELD(gi.approved, 'onhold', 'approved', 'declined'), -- Prioritize 'onhold' first, then 'approved', then 'declined'
         gi.uploaded_at DESC -- Sort by upload date within each status
 ";
 
 $result = mysqli_query($conn, $query);
 
 $json = array();
 
 while ($row = $result->fetch_assoc()) {
     $json[] = array(
         'id' => htmlspecialchars($row['id']),
         'gallery_id' => htmlspecialchars($row['gallery_id']),
         'image_path' => htmlspecialchars($row['image_path']),
         'uploaded_at' => htmlspecialchars($row['uploaded_at']),
         'status' => htmlspecialchars($row['status']),
         'posted_by' => htmlspecialchars($row['posted_by']),
     );
 }
 
 echo json_encode($json);
 ?>