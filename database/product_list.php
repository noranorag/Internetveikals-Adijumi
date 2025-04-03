<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
$offset = ($page - 1) * $limit;

$query = "
    SELECT 
        p.product_ID AS id,
        p.name AS product_name,
        p.short_description,
        p.price,
        p.stock_quantity,
        p.color,
        p.size,
        c.name AS category_name
    FROM 
        product p
    LEFT JOIN 
        category c ON p.ID_category = c.category_ID
    WHERE 
        p.name LIKE '%$search%' OR
        p.ID_category LIKE '%$search%' OR
        p.stock_quantity LIKE '%$search%' OR
        p.color LIKE '%$search%' OR
        p.size LIKE '%$search%' OR
        p.price LIKE '%$search%'
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$countQuery = "
    SELECT COUNT(*) AS total
    FROM product p
    WHERE 
        p.name LIKE '%$search%' OR
        p.ID_category LIKE '%$search%' OR
        p.stock_quantity LIKE '%$search%' OR
        p.color LIKE '%$search%' OR
        p.size LIKE '%$search%' OR
        p.price LIKE '%$search%'
";
$countResult = mysqli_query($conn, $countQuery);
$totalCount = mysqli_fetch_assoc($countResult)['total'];

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['product_name']),
        'short_description' => htmlspecialchars($row['short_description']),
        'price' => htmlspecialchars($row['price']),
        'stock_quantity' => htmlspecialchars($row['stock_quantity']),
        'color' => htmlspecialchars($row['color']),
        'size' => htmlspecialchars($row['size']),
        'category_name' => htmlspecialchars($row['category_name']),
    );
}


$response = [
    'products' => $json,
    'total' => $totalCount,
    'page' => $page,
    'limit' => $limit
];



echo json_encode($response);
?>