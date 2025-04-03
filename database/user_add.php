<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT); 
    $role = $conn->real_escape_string($_POST['role']);
    $address_id = null; 

    $emailCheck = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $emailCheck->bind_param('s', $email);
    $emailCheck->execute();
    $emailResult = $emailCheck->get_result();

    if ($emailResult->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'E-pasts jau eksistē!']);
        exit;
    }

    $sql = "INSERT INTO user (ID_address, name, surname, phone, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssss', $address_id, $name, $surname, $phone, $email, $password, $role);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>