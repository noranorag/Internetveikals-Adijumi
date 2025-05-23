<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

$query = "SELECT * FROM category WHERE active = 'active'";

if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}

if (!empty($filter)) {
    $query .= " AND big_category = '$filter'";
}

$query .= " ORDER BY category_ID DESC";

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