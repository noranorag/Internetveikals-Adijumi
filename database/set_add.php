<?php
require 'db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    if (!$name || !$description) {
        echo json_encode(['success' => false, 'error' => 'Nosaukums un apraksts ir obligāti.']);
        exit();
    }

    $query = "INSERT INTO sets (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ss', $name, $description);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Komplekts veiksmīgi pievienots.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Neizdevās pievienot komplektu.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Neizdevās sagatavot datubāzes pieprasījumu.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nederīgs pieprasījuma veids.']);
}
?>