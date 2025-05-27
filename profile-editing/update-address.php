<?php
session_start();
require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("User ID not found in session.");
    }
    $userId = $_SESSION['user_id'];

    $country = trim($_POST['country']);
    $city = trim($_POST['city']);
    $street = trim($_POST['street']);
    $house = trim($_POST['house']);
    $apartment = trim($_POST['apartment']);
    $postal_code = trim($_POST['postal_code']);

    // Validate character limits
    if (strlen($country) > 50) {
        header('Location: address-edit.php?error=Valsts nedrīkst pārsniegt 50 rakstzīmes!');
        exit;
    }
    if (strlen($city) > 50) {
        header('Location: address-edit.php?error=Pilsēta nedrīkst pārsniegt 50 rakstzīmes!');
        exit;
    }
    if (strlen($street) > 50) {
        header('Location: address-edit.php?error=Iela nedrīkst pārsniegt 50 rakstzīmes!');
        exit;
    }
    if (strlen($house) > 30) {
        header('Location: address-edit.php?error=Mājas numurs nedrīkst pārsniegt 30 rakstzīmes!');
        exit;
    }
    if (strlen($apartment) > 30) {
        header('Location: address-edit.php?error=Dzīvokļa numurs nedrīkst pārsniegt 30 rakstzīmes!');
        exit;
    }
    if (strlen($postal_code) > 7) {
        header('Location: address-edit.php?error=Pasta indekss nedrīkst pārsniegt 7 rakstzīmes!');
        exit;
    }

    if (empty($country) || empty($city) || empty($street) || empty($house) || empty($postal_code)) {
        header('Location: address-edit.php?error=Visi obligātie lauki ir jāaizpilda!');
        exit;
    }

    $sql = "SELECT ID_address FROM user WHERE user_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $addressId = $user['ID_address'] ?? null;

    if ($addressId) {
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
        $sql = "INSERT INTO address (country, city, street, house, apartment, postal_code) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $country, $city, $street, $house, $apartment, $postal_code);

        if ($stmt->execute()) {
            $newAddressId = $stmt->insert_id;

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