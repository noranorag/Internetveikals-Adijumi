<?php
require 'db_connection.php';

// Get the search query from the request
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Base query to fetch fairs
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
";

// Add search filtering if a search query is provided
if (!empty($search)) {
    $query .= " AND (f.name LIKE '%$search%' OR f.description LIKE '%$search%' OR f.link LIKE '%$search%')";
}

// Add sorting to the query
$query .= " ORDER BY f.fair_ID DESC";

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