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

    // Check if the new password matches the confirmation
    if ($newPassword !== $confirmPassword) {
        header('Location: password-change.php?error=Jaunā parole un apstiprinājums nesakrīt!');
        exit;
    }

    // Fetch the current password hash from the database
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

    // Verify the current password
    if (!password_verify($currentPassword, $user['password'])) {
        header('Location: password-change.php?error=Esošā parole ir nepareiza!');
        exit;
    }

    // Hash the new password
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
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