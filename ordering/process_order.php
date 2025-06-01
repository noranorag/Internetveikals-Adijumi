<?php
session_start();
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
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

    // Debugging: Log each value
    error_log("Name: " . var_export($name, true));
    error_log("Surname: " . var_export($surname, true));
    error_log("Email: " . var_export($email, true));
    error_log("Phone: " . var_export($phone, true));
    error_log("Country: " . var_export($country, true));
    error_log("City: " . var_export($city, true));
    error_log("Street: " . var_export($street, true));
    error_log("House: " . var_export($house, true));
    error_log("Apartment: " . var_export($apartment, true));
    error_log("Postal Code: " . var_export($postal_code, true));
    error_log("Total Amount: " . var_export($total_amount, true));
    error_log("Delivery: " . var_export($delivery, true));
    error_log("Pickup Address: " . var_export($pickup_address, true));

    // Validate required fields
    if (!$name || !$surname || !$email || !$phone || !$total_amount || !$delivery) {
        die('Error: Missing required fields.');
    }

    // Insert into the database
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
        // Get the inserted order ID
        $order_id = $stmt->insert_id;

        // Redirect to the order success page with the order ID
        header("Location: ../order_sucess.php?order_id=$order_id");
        exit;
    } else {
        die('Error: ' . $stmt->error);
    }
} else {
    die('Invalid request method.');
}
?>