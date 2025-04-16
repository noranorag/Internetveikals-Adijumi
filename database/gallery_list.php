<?php
require 'db_connection.php';


$status = $_GET['status'] ?? null;


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
";


if (!empty($status) && in_array($status, ['approved', 'onhold'])) {
    $query .= " WHERE gi.approved = ?";
}


$query .= " ORDER BY FIELD(gi.approved, 'onhold', 'approved', 'declined'), gi.uploaded_at DESC";

$stmt = $conn->prepare($query);


if (!empty($status) && in_array($status, ['approved', 'onhold'])) {
    $stmt->bind_param("s", $status);
}

$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();
?>