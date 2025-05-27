<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['first_name']);
    $surname = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validate input lengths
    if (strlen($name) > 50) {
        header('Location: ../register.php?error=Vārds nedrīkst pārsniegt 50 rakstzīmes!');
        exit();
    }
    if (strlen($surname) > 50) {
        header('Location: ../register.php?error=Uzvārds nedrīkst pārsniegt 50 rakstzīmes!');
        exit();
    }
    if (strlen($email) > 255) {
        header('Location: ../register.php?error=E-pasts nedrīkst pārsniegt 255 rakstzīmes!');
        exit();
    }
    if (strlen($phone) > 12) {
        header('Location: ../register.php?error=Tālrunis nedrīkst pārsniegt 12 rakstzīmes!');
        exit();
    }
    if (strlen($password) > 255) {
        header('Location: ../register.php?error=Parole nedrīkst pārsniegt 255 rakstzīmes!');
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    if ($stmt === false) {
        header('Location: ../register.php?error=Datubāzes kļūda!');
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: ../register.php?error=E-pasts jau tiek izmantots!');
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO user (name, surname, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            header('Location: ../register.php?error=Datubāzes kļūda!');
            exit();
        }
        $stmt->bind_param("sssss", $name, $surname, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_email'] = $email;

            header("Location: ../index.php");
            exit();
        } else {
            header('Location: ../register.php?error=Kļūda reģistrējoties!');
            exit();
        }
    }
}
?>