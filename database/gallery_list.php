<?php
require 'db_connection.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3; 
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        u.email AS posted_by,
        gi.gallery_ID AS gallery_id,
        gi.image AS image_path,
        gi.uploaded_at,
        gi.approved AS status
    FROM 
        user_gallery ug
    INNER JOIN 
        gallery_images gi ON ug.ID_gallery = gi.gallery_ID
    INNER JOIN 
        user u ON ug.ID_user = u.user_ID
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$countQuery = "
    SELECT COUNT(*) AS total 
    FROM gallery_images gi
    WHERE gi.approved IN ('onhold', 'approved', 'declined')
";
$countResult = mysqli_query($conn, $countQuery);
$totalCount = mysqli_fetch_assoc($countResult)['total'];

$gallery = [];
while ($row = $result->fetch_assoc()) {
    $gallery[] = [
        'id' => htmlspecialchars($row['gallery_id']),
        'image_path' => htmlspecialchars($row['image_path']),
        'uploaded_at' => htmlspecialchars($row['uploaded_at']),
        'status' => htmlspecialchars($row['status']),
        'posted_by' => htmlspecialchars($row['posted_by']),
    ];
}

$response = [
    'gallery' => $gallery,
    'total' => $totalCount,
    'page' => $page,
    'limit' => $limit
];



header('Content-Type: application/json');
echo json_encode($response);
?>