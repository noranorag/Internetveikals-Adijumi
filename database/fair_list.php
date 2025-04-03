<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "
    SELECT 
        f.fair_ID AS id,
        f.name AS name,
        f.description AS description,
        f.image AS image,
        f.link AS link
    FROM 
        fair f
    WHERE 
        f.active = 'active' AND (
            f.name LIKE '%$search%' OR
            f.description LIKE '%$search%' OR
            f.link LIKE '%$search%'
        )
    ORDER BY 
        f.fair_ID DESC
";

$result = mysqli_query($conn, $query);

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'description' => htmlspecialchars($row['description']),
        'image' => htmlspecialchars($row['image']),
        'link' => htmlspecialchars($row['link']),
    );
}


echo json_encode($json);

mysqli_close($conn);
?>