<?php
 require 'db_connection.php';
 
 if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    error_log('Fair ID received in fair_get.php: ' . $id);

    $sql = "SELECT * FROM fair WHERE fair_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Tirdziņš netika atrasts!']);
    }

    $stmt->close();
} else {
    error_log('No ID received in fair_get.php');
    echo json_encode(['error' => 'ID parameter is missing!']);
}
 
 mysqli_close($conn);
 ?>