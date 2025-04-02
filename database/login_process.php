<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Prepare the SQL statement to fetch the user
    $stmt = $conn->prepare("SELECT user_ID, email, password, role FROM user WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Regenerate session ID for security

            // Store user information in the session
            $_SESSION['user_id'] = $user['user_ID']; // Use the correct column name
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];


            // Redirect based on user role
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