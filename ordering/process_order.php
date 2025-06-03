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

    // Input length validation
    if (strlen($name) > 50 || strlen($surname) > 50 || strlen($email) > 255 || strlen($phone) > 12 ||
        strlen($country) > 50 || strlen($city) > 50 || strlen($street) > 50 || strlen($house) > 30 ||
        strlen($apartment) > 30 || strlen($postal_code) > 7) {
        header("Location: ../checkout.php?error=Ievadītie dati pārsniedz atļauto garumu.");
        exit;
    }

    if (!$name || !$surname || !$email || !$phone || !$total_amount || !$delivery) {
        header("Location: ../checkout.php?error=Trūkst obligāto lauku.");
        exit;
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (name, surname, email, phone, country, city, street, house, apartment, postal_code, total_amount, delivery, pickup_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
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

        if (!$stmt->execute()) {
            throw new Exception('Error: ' . $stmt->error);
        }

        $order_id = $stmt->insert_id;

        // Fetch cart items
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartQuery = "SELECT c.ID_product, c.quantity, p.price 
                          FROM cart c
                          INNER JOIN product p ON c.ID_product = p.product_ID
                          WHERE c.ID_user = ?";
            $cartStmt = $conn->prepare($cartQuery);
            if (!$cartStmt) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            $cartStmt->bind_param('i', $userId);
        } else {
            $sessionId = session_id();
            $cartQuery = "SELECT c.ID_product, c.quantity, p.price 
                          FROM cart c
                          INNER JOIN product p ON c.ID_product = p.product_ID
                          WHERE c.session_ID = ?";
            $cartStmt = $conn->prepare($cartQuery);
            if (!$cartStmt) {
                throw new Exception('Prepare failed: ' . $conn->error);
            }
            $cartStmt->bind_param('s', $sessionId);
        }

        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();

        // Insert cart items into order_items table
        $orderItemsStmt = $conn->prepare("INSERT INTO order_items (ID_order, ID_product, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$orderItemsStmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        while ($cartItem = $cartResult->fetch_assoc()) {
            $orderItemsStmt->bind_param(
                'iiid',
                $order_id,
                $cartItem['ID_product'],
                $cartItem['quantity'],
                $cartItem['price']
            );

            if (!$orderItemsStmt->execute()) {
                throw new Exception('Error: ' . $orderItemsStmt->error);
            }
        }

        // Clear the cart after order is placed
        if (isset($_SESSION['user_id'])) {
            $clearCartQuery = "DELETE FROM cart WHERE ID_user = ?";
            $clearCartStmt = $conn->prepare($clearCartQuery);
            $clearCartStmt->bind_param('i', $userId);
        } else {
            $clearCartQuery = "DELETE FROM cart WHERE session_ID = ?";
            $clearCartStmt = $conn->prepare($clearCartQuery);
            $clearCartStmt->bind_param('s', $sessionId);
        }

        if (!$clearCartStmt->execute()) {
            throw new Exception('Error clearing cart: ' . $clearCartStmt->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to success page
        header("Location: ../order_sucess.php?order_id=$order_id");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        die('Error: ' . $e->getMessage());
    }
} else {
    die('Invalid request method.');
}
?>