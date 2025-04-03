<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7; // Default limit is 7
$offset = ($page - 1) * $limit;

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
    LIMIT $limit OFFSET $offset
";

$result = mysqli_query($conn, $query);

$countQuery = "
    SELECT COUNT(*) AS total 
    FROM user 
    WHERE 
        active = 'active' AND (
            name LIKE '%$search%' OR
            surname LIKE '%$search%' OR
            email LIKE '%$search%' OR
            phone LIKE '%$search%' OR
            role LIKE '%$search%'
        )
";
$countResult = mysqli_query($conn, $countQuery);
$totalCount = mysqli_fetch_assoc($countResult)['total'];

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        'id' => htmlspecialchars($row['user_ID']),
        'address_id' => htmlspecialchars($row['ID_address']),
        'name' => htmlspecialchars($row['name']),
        'surname' => htmlspecialchars($row['surname']),
        'phone' => htmlspecialchars($row['phone']),
        'email' => htmlspecialchars($row['email']),
        'role' => htmlspecialchars($row['role']),
    ];
}

$response = [
    'users' => $users,
    'total' => $totalCount,
    'page' => $page,
    'limit' => $limit
];

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>