<?php
session_start();
require '../database/db_connection.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Query to fetch user and address information
    $sql = "SELECT 
                u.name, 
                u.surname, 
                u.email, 
                u.phone, 
                a.country, 
                a.city, 
                a.street, 
                a.house, 
                a.apartment, 
                a.postal_code 
            FROM user u
            LEFT JOIN address a ON u.ID_address = a.address_ID
            WHERE u.user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user and address data
        $user = $result->fetch_assoc();
        echo json_encode($user); // Return the user and address data as JSON
    } else {
        // User not found
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
    }
} else {
    // User not logged in
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
}
?>