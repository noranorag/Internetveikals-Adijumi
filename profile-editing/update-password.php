<?php
session_start();
require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("User ID not found in session.");
    }

    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($currentPassword) > 255 || strlen($newPassword) > 255 || strlen($confirmPassword) > 255) {
        header('Location: password-change.php?error=Parole nedrīkst pārsniegt 255 rakstzīmes!');
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        header('Location: password-change.php?error=Jaunā parole un apstiprinājums nesakrīt!');
        exit;
    }

    $sql = "SELECT password FROM user WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        header('Location: password-change.php?error=Lietotājs nav atrasts!');
        exit;
    }

    if (!password_verify($currentPassword, $user['password'])) {
        header('Location: password-change.php?error=Esošā parole ir nepareiza!');
        exit;
    }

    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE user SET password = ? WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $newPasswordHash, $userId);

    if ($stmt->execute()) {
        header('Location: password-change.php?success=Parole veiksmīgi nomainīta!');
        exit;
    } else {
        header('Location: password-change.php?error=Kļūda mainot paroli!');
        exit;
    }
}
?>