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

            $base_url = dirname(dirname($_SERVER['SCRIPT_NAME'])); 

            if ($user['role'] === 'admin' || $user['role'] === 'moder') {
                $redirect = $base_url . '/admin/index.php';
            } else if ($user['role'] === 'user') {
                $redirect = $base_url . '/index.php';
            } else {
                $redirect = $base_url . '/index.php'; 
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