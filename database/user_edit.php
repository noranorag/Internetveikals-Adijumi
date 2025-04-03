<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $surname = $conn->real_escape_string($_POST['surname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $address_id = null; 
    $password = isset($_POST['password']) && !empty($_POST['password'])
        ? password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT)
        : null;

    $emailCheck = $conn->prepare("SELECT email FROM user WHERE email = ? AND user_ID != ?");
    $emailCheck->bind_param('si', $email, $id);
    $emailCheck->execute();
    $emailResult = $emailCheck->get_result();

    if ($emailResult->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'E-pasts jau eksistē!']);
        exit;
    }

    if ($password) {
        $sql = "UPDATE user SET ID_address = ?, name = ?, surname = ?, phone = ?, email = ?, role = ?, password = ? WHERE user_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issssssi', $address_id, $name, $surname, $phone, $email, $role, $password, $id);
    } else {
        $sql = "UPDATE user SET ID_address = ?, name = ?, surname = ?, phone = ?, email = ?, role = ? WHERE user_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isssssi', $address_id, $name, $surname, $phone, $email, $role, $id);
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No rows affected.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

mysqli_close($conn);
?>