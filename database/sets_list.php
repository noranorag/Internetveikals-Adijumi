<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "
    SELECT 
        s.set_ID AS set_id,
        s.name AS set_name,
        s.description AS set_description,
        p.product_ID AS product_id,
        p.name AS product_name,
        p.image AS product_image
    FROM sets s
    LEFT JOIN product_sets ps ON s.set_ID = ps.ID_set
    LEFT JOIN product p ON ps.ID_product = p.product_ID
";

if (!empty($search)) {
    $query .= " WHERE s.name LIKE '%$search%'";
}

$query .= " ORDER BY s.set_ID DESC";

$result = mysqli_query($conn, $query);

$sets = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $set_id = $row['set_id'];

        if (!isset($sets[$set_id])) {
            $sets[$set_id] = [
                'set_id' => htmlspecialchars($row['set_id']),
                'set_name' => htmlspecialchars($row['set_name']),
                'set_description' => htmlspecialchars($row['set_description']),
                'products' => []
            ];
        }

        if (!empty($row['product_id'])) {
            $sets[$set_id]['products'][] = [
                'product_id' => htmlspecialchars($row['product_id']),
                'product_name' => htmlspecialchars($row['product_name']),
                'product_image' => htmlspecialchars($row['product_image'] ?? 'default.png')
            ];
        }
    }
} else {
    error_log("Database query failed: " . mysqli_error($conn));
}

echo json_encode(array_values($sets));
?>