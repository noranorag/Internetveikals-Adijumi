<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { 
    die("User ID not found in session.");
}

$userId = $_SESSION['user_id']; 

$sql = "SELECT ID_address FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: address-edit.php?error=Lietotājs nav atrasts!');
    exit;
}

$addressId = $user['ID_address'];

if (!$addressId) {
    header('Location: address-edit.php?error=Adrese nav atrasta!');
    exit;
}

$conn->begin_transaction();

try {
    $sql = "DELETE FROM address WHERE address_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $addressId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to delete address.");
    }

    $sql = "UPDATE user SET ID_address = NULL WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update user's ID_address field.");
    }

    $conn->commit();

    header('Location: address-edit.php?success=Adrese veiksmīgi dzēsta!');
    exit;
} catch (Exception $e) {
    $conn->rollback();
    error_log("Error deleting address: " . $e->getMessage());
    header('Location: address-edit.php?error=Kļūda dzēšot adresi!');
    exit;
}
?>