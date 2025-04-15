<?php
require 'db_connection.php';

$category = isset($_GET['category']) ? intval($_GET['category']) : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Debugging: Log the received parameters
error_log("Category: " . $category);
error_log("Sort: " . $sort);
error_log("Search: " . $search);

$query = "
    SELECT 
        product.product_ID AS id,
        product.name AS name,
        category.big_category AS big_category,
        category.name AS category_name,
        product.short_description AS short_description,
        product.price AS price,
        product.stock_quantity AS stock_quantity,
        product.image AS product_image,
        product.created_at AS created_at,
        product.color AS color,
        product.material AS material
    FROM product
    LEFT JOIN category ON product.ID_category = category.category_ID
    WHERE 1=1
";

if (!empty($search)) {
    $query .= " AND (
        product.name LIKE '%$search%' OR
        category.name LIKE '%$search%' OR
        product.short_description LIKE '%$search%' OR
        product.color LIKE '%$search%' OR
        product.material LIKE '%$search%'
    )";
}

if ($category) {
    $query .= " AND product.ID_category = $category";
}

// Apply sorting based on the `sort` parameter
switch ($sort) {
    case 'quantity_asc':
        $query .= " ORDER BY product.stock_quantity ASC";
        break;
    case 'quantity_desc':
        $query .= " ORDER BY product.stock_quantity DESC";
        break;
    case 'date_asc':
        $query .= " ORDER BY product.created_at ASC";
        break;
    case 'date_desc':
        $query .= " ORDER BY product.created_at DESC";
        break;
    case 'price_asc':
        $query .= " ORDER BY product.price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY product.price DESC";
        break;
    default:
        $query .= " ORDER BY product.product_ID DESC"; // Default sorting
        break;
}

// Debugging: Log the generated SQL query
error_log("SQL Query: " . $query);

$result = mysqli_query($conn, $query);

if (!$result) {
    // Handle SQL errors
    error_log("SQL Error: " . mysqli_error($conn));
    echo json_encode(['error' => 'Database query failed.']);
    exit();
}

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'big_category' => htmlspecialchars($row['big_category']),
        'category_name' => htmlspecialchars($row['category_name']),
        'short_description' => htmlspecialchars($row['short_description']),
        'price' => htmlspecialchars($row['price']),
        'stock_quantity' => htmlspecialchars($row['stock_quantity']),
        'product_image' => htmlspecialchars($row['product_image'] ?? 'images/default.png'),
        'created_at' => htmlspecialchars($row['created_at']),
        'color' => htmlspecialchars($row['color']),
        'material' => htmlspecialchars($row['material'])
    );
}

// Debugging: Log the JSON response
error_log("JSON Response: " . json_encode($json));

echo json_encode($json);
?>