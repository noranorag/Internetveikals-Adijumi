<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect form data
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $country = $_POST['country'] ?? '';
        $city = $_POST['city'] ?? '';
        $street = $_POST['street'] ?? '';
        $house = $_POST['house'] ?? '';
        $apartment = $_POST['apartment'] ?? '';
        $postalCode = $_POST['postal_code'] ?? '';
        $totalAmount = $_POST['total_amount'] ?? 0.00;
        $delivery = $_POST['delivery'] ?? '';
        $pickupAddress = $_POST['pickup_address'] ?? '';
        $shippingPrice = $_POST['shipping_price'] ?? 0.00; // Default to 0.00 if not provided
        $status = 'Pending'; // Default status
        $createdAt = date('Y-m-d H:i:s'); // Current timestamp

        // Insert into `orders` table
        $sqlOrder = "INSERT INTO orders (name, surname, email, phone, country, city, street, house, apartment, postal_code, total_amount, created_at, delivery, pickup_address, shipping_price) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        if (!$stmtOrder) {
            throw new Exception("Failed to prepare statement for orders: " . $conn->error);
        }
        $stmtOrder->bind_param(
            'ssssssssssdssss',
            $name,
            $surname,
            $email,
            $phone,
            $country,
            $city,
            $street,
            $house,
            $apartment,
            $postalCode,
            $totalAmount,
            $createdAt,
            $delivery,
            $pickupAddress,
            $shippingPrice
        );

        if (!$stmtOrder->execute()) {
            throw new Exception("Failed to execute statement for orders: " . $stmtOrder->error);
        }

        // Get the last inserted order ID
        $orderId = $stmtOrder->insert_id;

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
                $orderId, // Use $orderId instead of $order_id
                $cartItem['ID_product'],
                $cartItem['quantity'],
                $cartItem['price']
            );

            if (!$orderItemsStmt->execute()) {
                throw new Exception('Error: ' . $orderItemsStmt->error);
            }

            // Update the reserved field for the product
            $updateReservedQuery = "UPDATE product SET reserved = 1 WHERE product_ID = ?";
            $updateReservedStmt = $conn->prepare($updateReservedQuery);
            if (!$updateReservedStmt) {
                throw new Exception('Prepare failed for reserved update: ' . $conn->error);
            }
            $updateReservedStmt->bind_param('i', $cartItem['ID_product']);
            if (!$updateReservedStmt->execute()) {
                throw new Exception('Error updating reserved field: ' . $updateReservedStmt->error);
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
        header("Location: ../order_sucess.php?order_id=$orderId");
        exit;
    } catch (Exception $e) {
        error_log("Error in process_order.php: " . $e->getMessage());
        header("Location: ../checkout.php?error=" . urlencode("Failed to process order."));
        exit();
    }
} else {
    header("Location: ../checkout.php?error=" . urlencode("Invalid request method."));
    exit();
}
?>