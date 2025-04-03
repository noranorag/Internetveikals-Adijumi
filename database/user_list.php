<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "
    SELECT 
        user_ID, 
        ID_address, 
        name, 
        surname, 
        phone, 
        email, 
        role 
    FROM 
        user 
    WHERE 
        active = 'active' AND (
            name LIKE '%$search%' OR
            surname LIKE '%$search%' OR
            email LIKE '%$search%' OR
            phone LIKE '%$search%' OR
            role LIKE '%$search%'
        )
    ORDER BY 
        user_ID DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['user_ID']),
        'address_id' => htmlspecialchars($row['ID_address']),
        'name' => htmlspecialchars($row['name']),
        'surname' => htmlspecialchars($row['surname']),
        'phone' => htmlspecialchars($row['phone']),
        'email' => htmlspecialchars($row['email']),
        'role' => htmlspecialchars($row['role']),
    );
}

echo json_encode($json);

mysqli_close($conn);
?>