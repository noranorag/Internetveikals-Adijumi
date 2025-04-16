<?php
require 'db_connection.php';

try {
    
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $role = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';

    
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
            active = 'active'
    ";

    
    if (!empty($search)) {
        $query .= " AND (
            name LIKE '%$search%' OR
            surname LIKE '%$search%' OR
            email LIKE '%$search%' OR
            phone LIKE '%$search%' OR
            role LIKE '%$search%'
        )";
    }

    
    if (!empty($role)) {
        $query .= " AND role = '$role'";
    }

    $query .= " ORDER BY user_ID DESC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        throw new Exception('Error executing query: ' . mysqli_error($conn));
    }

    
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
        'users' => $users
    ];

    
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Internal Server Error']);
} finally {
    mysqli_close($conn);
}
?>