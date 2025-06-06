<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("User ID not found in session.");
}

$userId = $_SESSION['user_id'];

// Retrieve the address ID associated with the user
$sql = "SELECT ID_address FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !$user['ID_address']) {
    header("Location: address-edit.php?error=Adrese netika atrasta.");
    exit;
}

$addressId = $user['ID_address'];

// Update the user table to set ID_address to NULL
$sql = "UPDATE user SET ID_address = NULL WHERE user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    // Delete the address from the address table
    $sql = "DELETE FROM address WHERE address_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $addressId);

    if ($stmt->execute()) {
        // Redirect with a success message
        header("Location: address-edit.php?success=Adrese veiksmīgi dzēsta.");
    } else {
        // Redirect with an error message
        header("Location: address-edit.php?error=Adresi neizdevās dzēst no datubāzes.");
    }
} else {
    // Redirect with an error message
    header("Location: address-edit.php?error=Adresi neizdevās dzēst.");
}

$stmt->close();
$conn->close();
?>