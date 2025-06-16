<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_ID, name, surname, email, password, role FROM user WHERE email = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Datubāzes kļūda.']);
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['user_ID'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['surname'] = $user['surname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // Transfer cart items from session_ID to user_ID
            $sessionID = session_id();
            $stmt = $conn->prepare("
                UPDATE cart 
                SET ID_user = ?, session_ID = NULL 
                WHERE session_ID = ?
            ");
            $stmt->bind_param("is", $user['user_ID'], $sessionID);
            $stmt->execute();

            // Redirect based on user role
            if ($user['role'] === 'user') {
                $redirect = 'index.php';
            } elseif ($user['role'] === 'admin' || $user['role'] === 'moder') {
                $redirect = '/admin/index.php';
            } else {
                $redirect = 'index.php'; // Default redirect for unknown roles
            }

            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Nepareiza parole.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Lietotājs ar šādu e-pastu nav atrasts.']);
        exit();
    }
}
?>