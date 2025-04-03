<?php
require 'db_connection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $query = $conn->prepare("UPDATE user SET active = 'deleted' WHERE user_ID = ?");
    $query->bind_param("i", $id);

    if ($query->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Neizdevās dzēst lietotāju!']);
    }

    $query->close();
    $conn->close();
}
?>