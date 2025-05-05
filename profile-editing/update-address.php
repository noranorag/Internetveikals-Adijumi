<?php
session_start();
require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the session
    if (!isset($_SESSION['user_id'])) {
        die("User ID not found in session.");
    }
    $userId = $_SESSION['user_id'];

    // Fetch the user's address ID
    $sql = "SELECT ID_address FROM user WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $addressId = $user['ID_address'] ?? null;

    // Get the form data
    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);
    $house = trim($_POST['house']);
    $apartment = trim($_POST['apartment']);
    $postal_code = trim($_POST['postal_code']);

    // Check if all fields are empty
    if (empty($country) && empty($city) && empty($street) && empty($house) && empty($apartment) && empty($postal_code)) {
        header('Location: address-edit.php?error=Visi lauki ir tukši!');
        exit;
    }

    if ($addressId) {
        // Update the existing address
        $sql = "UPDATE address SET country = ?, city = ?, street = ?, house = ?, apartment = ?, postal_code = ? WHERE address_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $country, $city, $street, $house, $apartment, $postal_code, $addressId);

        if ($stmt->execute()) {
            header('Location: address-edit.php?success=Adrese veiksmīgi atjaunināta!');
            exit;
        } else {
            header('Location: address-edit.php?error=Kļūda atjauninot adresi!');
            exit;
        }
    } else {
        // Insert a new address
        $sql = "INSERT INTO address (country, city, street, house, apartment, postal_code) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $country, $city, $street, $house, $apartment, $postal_code);

        if ($stmt->execute()) {
            // Get the newly inserted address ID
            $newAddressId = $stmt->insert_id;

            // Update the user's ID_address field
            $sql = "UPDATE user SET ID_address = ? WHERE user_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $newAddressId, $userId);

            if ($stmt->execute()) {
                header('Location: address-edit.php?success=Jauna adrese veiksmīgi pievienota!');
                exit;
            } else {
                header('Location: address-edit.php?error=Kļūda pievienojot jauno adresi lietotājam!');
                exit;
            }
        } else {
            header('Location: address-edit.php?error=Kļūda pievienojot jauno adresi!');
            exit;
        }
    }
}
?>