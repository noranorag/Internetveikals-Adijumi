<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT user_ID, name, surname, email, password, role FROM user WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        error_log("Fetched user data: " . print_r($user, true));
        
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); 
            
            $_SESSION['user_id'] = $user['user_ID'];
            $_SESSION['name'] = $user['name']; 
            $_SESSION['surname'] = $user['surname']; 
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            error_log("Session data after login: " . print_r($_SESSION, true));

            if ($user['role'] === 'admin' || $user['role'] === 'moder') {
                header("Location: ../admin/index.php");
            } else if ($user['role'] === 'user') {
                header("Location: ../index.php");
            } else {
                echo "Invalid user role.";
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>