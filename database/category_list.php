<?php
require 'db_connection.php'; 

$query = "SELECT * FROM category WHERE active = 'active' ORDER BY category_ID DESC"; 
$result = mysqli_query($conn, $query);

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['category_ID']),
        'name' => htmlspecialchars($row['name']),
        'big_category' => htmlspecialchars($row['big_category']),
    );
}

echo json_encode($json);
?>