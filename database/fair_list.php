<?php
require 'db_connection.php';

// Query to fetch all fairs with active = "active"
$query = "
    SELECT 
        f.fair_ID AS id,
        f.name AS name,
        f.description AS description,
        f.image AS image,
        f.link AS link,
        af.ID_user AS admin_user_id
    FROM 
        fair f
    LEFT JOIN 
        admin_fair af ON f.fair_ID = af.ID_fair
    WHERE 
        f.active = 'active'
    ORDER BY 
        f.fair_ID DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'description' => htmlspecialchars($row['description']),
        'image' => htmlspecialchars($row['image']),
        'link' => htmlspecialchars($row['link']),
        'admin_user_id' => htmlspecialchars($row['admin_user_id']),
    );
}

echo json_encode($json);

mysqli_close($conn);
?>