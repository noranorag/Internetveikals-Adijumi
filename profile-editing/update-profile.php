<?php
session_start();
require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the session
    if (!isset($_SESSION['user_id'])) { // Updated to match login_process.php
        die("User ID not found in session.");
    }
    $userId = $_SESSION['user_id']; // Updated to match login_process.php

    // Get the form data
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Validate the input
    if (empty($name) || empty($surname) || empty($phone) || empty($email)) {
        header('Location: profile-edit.php?error=Visi lauki ir obligāti!');
        exit;
    }

    // Check if the email is unique
    $sql = "SELECT user_ID FROM user WHERE email = ? AND user_ID != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: profile-edit.php?error=E-pasts jau tiek izmantots!');
        exit;
    }

    // Update the user in the database
    $sql = "UPDATE user SET name = ?, surname = ?, phone = ?, email = ? WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $name, $surname, $phone, $email, $userId);

    if ($stmt->execute()) {
        header('Location: profile-edit.php?success=Profils veiksmīgi atjaunināts!');
        exit;
    } else {
        header('Location: profile-edit.php?error=Kļūda atjauninot profilu!');
        exit;
    }
}
?>