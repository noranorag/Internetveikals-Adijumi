<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { // Check if the user is logged in
    die("User ID not found in session.");
}

$userId = $_SESSION['user_id']; // Get the user ID from the session

// Fetch the user's address ID
$sql = "SELECT ID_address FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) { // Check if the user exists
    header('Location: address-edit.php?error=Lietotājs nav atrasts!');
    exit;
}

$addressId = $user['ID_address'];

if (!$addressId) { // Check if the user has an address
    header('Location: address-edit.php?error=Adrese nav atrasta!');
    exit;
}

// Start a transaction to ensure atomicity
$conn->begin_transaction();

try {
    // Delete the address from the database
    $sql = "DELETE FROM address WHERE address_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $addressId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to delete address.");
    }

    // Set the user's ID_address field to NULL
    $sql = "UPDATE user SET ID_address = NULL WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);

    if (!$stmt->execute()) {
        throw new Exception("Failed to update user's ID_address field.");
    }

    // Commit the transaction
    $conn->commit();

    header('Location: address-edit.php?success=Adrese veiksmīgi dzēsta!');
    exit;
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    error_log("Error deleting address: " . $e->getMessage());
    header('Location: address-edit.php?error=Kļūda dzēšot adresi!');
    exit;
}
?>