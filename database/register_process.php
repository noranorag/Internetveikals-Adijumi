<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['first_name'];
    $surname = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already exists.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO user (name, surname, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sssss", $name, $surname, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Store user information in the session
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_email'] = $email;

            header("Location: ../index.php");
            exit();
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }
    }
}
?>