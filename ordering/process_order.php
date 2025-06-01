<?php
session_start();
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $surname = $_POST['surname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $country = $_POST['country'] ?? null;
    $city = $_POST['city'] ?? null;
    $street = $_POST['street'] ?? null;
    $house = $_POST['house'] ?? null;
    $apartment = $_POST['apartment'] ?? null;
    $postal_code = $_POST['postal_code'] ?? null;
    $total_amount = $_POST['total_amount'] ?? 0;
    $delivery = $_POST['delivery'] ?? null;
    $pickup_address = $_POST['pickup_address'] ?? null;

    if (!$name || !$surname || !$email || !$phone || !$total_amount || !$delivery) {
        die('Error: Missing required fields.');
    }

    $stmt = $conn->prepare("INSERT INTO orders (name, surname, email, phone, country, city, street, house, apartment, postal_code, total_amount, delivery, pickup_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param(
        'ssssssssssdss',
        $name,
        $surname,
        $email,
        $phone,
        $country,
        $city,
        $street,
        $house,
        $apartment,
        $postal_code,
        $total_amount,
        $delivery,
        $pickup_address
    );

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        header("Location: ../order_sucess.php?order_id=$order_id");
        exit;
    } else {
        die('Error: ' . $stmt->error);
    }
} else {
    die('Invalid request method.');
}
?>