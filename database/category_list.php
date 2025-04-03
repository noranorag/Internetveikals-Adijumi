<?php

require 'db_connection.php';


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
$offset = ($page - 1) * $limit;


$query = "
    SELECT * 
    FROM category 
    WHERE active = 'active' 
    ORDER BY category_ID DESC 
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);


$countQuery = "SELECT COUNT(*) AS total FROM category WHERE active = 'active'";
$countResult = mysqli_query($conn, $countQuery);
$totalCount = mysqli_fetch_assoc($countResult)['total'];

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = [
        'id' => htmlspecialchars($row['category_ID']),
        'name' => htmlspecialchars($row['name']),
        'big_category' => htmlspecialchars($row['big_category']),
    ];
}


$response = [
    'categories' => $categories,
    'total' => $totalCount,
    'page' => $page,
    'limit' => $limit
];

header('Content-Type: application/json');
echo json_encode($response);
?>