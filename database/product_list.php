<?php
require 'db_connection.php'; 

$query = "SELECT * FROM product ORDER BY product_ID DESC"; 
$result = mysqli_query($conn, $query);

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['product_ID']),
        'category_id' => htmlspecialchars($row['ID_category']),
        'user_id' => htmlspecialchars($row['ID_user']),
        'name' => htmlspecialchars($row['name']),
        'short_description' => htmlspecialchars($row['short_description']),
        'long_description' => htmlspecialchars($row['long_description']),
        'material' => htmlspecialchars($row['material']),
        'size' => htmlspecialchars($row['size']),
        'color' => htmlspecialchars($row['color']),
        'care' => htmlspecialchars($row['care']),
        'price' => htmlspecialchars($row['price']),
        'image' => htmlspecialchars($row['image']),
        'stock_quantity' => htmlspecialchars($row['stock_quantity']),
    );
}

echo json_encode($json);
?>