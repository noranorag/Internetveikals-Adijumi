<?php

error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 0); // Do not display errors in the response
ini_set('log_errors', 1); // Log errors to the server's error log
require 'db_connection.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3; // Default limit is 3
$offset = ($page - 1) * $limit;

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
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$countQuery = "
    SELECT COUNT(*) AS total
    FROM fair f
    WHERE 
        f.active = 'active' AND (
            f.name LIKE '%$search%' OR
            f.description LIKE '%$search%' OR
            f.link LIKE '%$search%'
        )
";
$countResult = mysqli_query($conn, $countQuery);
$totalCount = mysqli_fetch_assoc($countResult)['total'];

$fairs = [];
while ($row = $result->fetch_assoc()) {
    $fairs[] = [
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'description' => htmlspecialchars($row['description']),
        'image' => htmlspecialchars($row['image']),
        'link' => htmlspecialchars($row['link']),
    ];
}

$response = [
    'fairs' => $fairs,
    'total' => $totalCount,
    'page' => $page,
    'limit' => $limit
];

// Debugging: Log the response to ensure it's valid JSON
error_log(json_encode($response)); // Logs to the PHP error log

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>