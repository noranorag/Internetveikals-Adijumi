<?php
require 'db_connection.php';

// Update the status column based on the current date
$currentDate = date('Y-m-d');
$updateStatusQuery = "
    UPDATE fair
    SET status = 'late'
    WHERE status = 'upcoming' AND DATE_ADD(date, INTERVAL 1 DAY) <= '$currentDate'
";
mysqli_query($conn, $updateStatusQuery);

// Get the search query and status from the request
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

// Base query to fetch fairs
$query = "
    SELECT 
        f.fair_ID AS id,
        f.name AS name,
        f.description AS description,
        f.image AS image,
        f.link AS link,
        f.status AS status, -- Include the status column
        af.ID_user AS admin_user_id
    FROM 
        fair f
    LEFT JOIN 
        admin_fair af ON f.fair_ID = af.ID_fair
    WHERE 
        f.active = 'active'
";

// Add search filtering if a search query is provided
if (!empty($search)) {
    $query .= " AND (f.name LIKE '%$search%' OR f.description LIKE '%$search%' OR f.link LIKE '%$search%')";
}

// Add status filtering if a status is provided
if (!empty($status)) {
    $query .= " AND f.status = '$status'";
}

// Add sorting to the query
$query .= " ORDER BY f.fair_ID DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$json = array();

while ($row = $result->fetch_assoc()) {
    $json[] = array(
        'id' => htmlspecialchars($row['id']),
        'name' => htmlspecialchars($row['name']),
        'description' => htmlspecialchars($row['description']),
        'image' => htmlspecialchars($row['image']),
        'link' => htmlspecialchars($row['link']),
        'status' => htmlspecialchars($row['status']), // Include the status in the response
        'admin_user_id' => htmlspecialchars($row['admin_user_id']),
    );
}

echo json_encode($json);

mysqli_close($conn);
?>