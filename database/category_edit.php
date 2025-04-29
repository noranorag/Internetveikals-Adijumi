<?php
include 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $big_category = $conn->real_escape_string($_POST['big_category']);

    if ($id <= 0 || empty($name) || empty($big_category)) {
        echo json_encode(['success' => false, 'error' => 'Nepareizi ievadīti dati!']);
        exit();
    }

    
    $sql = "UPDATE category 
            SET name = ?, 
                big_category = ?, 
                edited = NOW() 
            WHERE category_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $name, $big_category, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
?>