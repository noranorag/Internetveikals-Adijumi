<?php
session_start();
require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) { 
        die("User ID not found in session.");
    }
    $userId = $_SESSION['user_id']; 

    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    if (strlen($name) > 50) {
        header('Location: profile-edit.php?error=Vārds nedrīkst pārsniegt 50 rakstzīmes!');
        exit;
    }
    if (strlen($surname) > 50) {
        header('Location: profile-edit.php?error=Uzvārds nedrīkst pārsniegt 50 rakstzīmes!');
        exit;
    }
    if (strlen($phone) > 12) {
        header('Location: profile-edit.php?error=Telefons nedrīkst pārsniegt 12 rakstzīmes!');
        exit;
    }
    if (strlen($email) > 255) {
        header('Location: profile-edit.php?error=E-pasts nedrīkst pārsniegt 255 rakstzīmes!');
        exit;
    }

    if (empty($name) || empty($surname) || empty($phone) || empty($email)) {
        header('Location: profile-edit.php?error=Visi lauki ir obligāti!');
        exit;
    }

    $sql = "SELECT user_ID FROM user WHERE email = ? AND user_ID != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $email, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: profile-edit.php?error=E-pasts jau tiek izmantots!');
        exit;
    }

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