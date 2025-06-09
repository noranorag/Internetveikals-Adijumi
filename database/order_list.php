<?php
require 'db_connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

$query = "SELECT * FROM orders WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR surname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}

if (!empty($statusFilter)) {
    $query .= " AND status = '$statusFilter'";
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

$json = array();

while ($row = $result->fetch_assoc()) {
    $formattedDate = date('d/m/Y', strtotime($row['created_at']));

    $json[] = array(
        'order_ID' => htmlspecialchars($row['order_ID']),
        'name' => htmlspecialchars($row['name']),
        'surname' => htmlspecialchars($row['surname']),
        'email' => htmlspecialchars($row['email']),
        'phone' => htmlspecialchars($row['phone']),
        'total_amount' => htmlspecialchars($row['total_amount']),
        'delivery' => htmlspecialchars($row['delivery']),
        'status' => htmlspecialchars($row['status']),
        'created_at' => htmlspecialchars($formattedDate), 
    );
}

echo json_encode($json);
?>