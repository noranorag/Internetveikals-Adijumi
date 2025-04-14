<?php
require 'db_connection.php';

$query = "
    SELECT 
        product.product_ID AS id,
        product.name AS name,
        category.big_category AS big_category, -- Include big_category
        category.name AS category_name,
        product.short_description AS short_description,
        product.price AS price,
        product.stock_quantity AS stock_quantity,
        product.image AS product_image -- Include product image
    FROM product
    LEFT JOIN category ON product.ID_category = category.category_ID
    ORDER BY product.product_ID DESC";

$result = mysqli_query($conn, $query);

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'big_category' => htmlspecialchars($row['big_category']), // Include big_category
        'category_name' => htmlspecialchars($row['category_name']),
        'short_description' => htmlspecialchars($row['short_description']),
        'price' => htmlspecialchars($row['price']),
        'stock_quantity' => htmlspecialchars($row['stock_quantity']),
        'product_image' => htmlspecialchars($row['product_image'] ?? 'images/default.png') // Include product image
    );
}

echo json_encode($json);
?>