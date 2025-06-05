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

        // Backend validation for input lengths
        if (strlen($name) > 50 || strlen($surname) > 50 || strlen($email) > 255 || strlen($phone) > 12 ||
            strlen($country) > 50 || strlen($city) > 50 || strlen($street) > 50 || strlen($house) > 30 ||
            strlen($apartment) > 30 || strlen($postalCode) > 7 || strlen($delivery) > 50 || strlen($pickupAddress) > 255) {
            throw new Exception("Ievades garuma validācija neizdevās.");
        }

        $status = 'Pending'; // Default status
        $createdAt = date('Y-m-d H:i:s'); // Current timestamp

        // Insert into `orders` table
        $sqlOrder = "INSERT INTO orders (name, surname, email, phone, country, city, street, house, apartment, postal_code, total_amount, created_at, delivery, pickup_address, shipping_price) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        if (!$stmtOrder) {
            throw new Exception("Neizdevās sagatavot pieprasījumu pasūtījumiem: " . $conn->error);
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
            throw new Exception("Neizdevās izpildīt pieprasījumu pasūtījumiem: " . $stmtOrder->error);
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
                throw new Exception('Neizdevās sagatavot pieprasījumu grozam: ' . $conn->error);
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
                throw new Exception('Neizdevās sagatavot pieprasījumu grozam: ' . $conn->error);
            }
            $cartStmt->bind_param('s', $sessionId);
        }

        $cartStmt->execute();
        $cartResult = $cartStmt->get_result();

        // Insert cart items into order_items table
        $orderItemsStmt = $conn->prepare("INSERT INTO order_items (ID_order, ID_product, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$orderItemsStmt) {
            throw new Exception('Neizdevās sagatavot pieprasījumu pasūtījuma vienībām: ' . $conn->error);
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
                throw new Exception('Kļūda: ' . $orderItemsStmt->error);
            }

            // Update the reserved field and reservation_time for the product
            $updateReservedQuery = "UPDATE product SET reserved = 1, reservation_time = NOW() WHERE product_ID = ?";
            $updateReservedStmt = $conn->prepare($updateReservedQuery);
            if (!$updateReservedStmt) {
                throw new Exception('Neizdevās sagatavot pieprasījumu rezervācijas atjaunināšanai: ' . $conn->error);
            }
            $updateReservedStmt->bind_param('i', $cartItem['ID_product']);
            if (!$updateReservedStmt->execute()) {
                throw new Exception('Kļūda, atjauninot rezervācijas lauku: ' . $updateReservedStmt->error);
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
            throw new Exception('Kļūda, dzēšot grozu: ' . $clearCartStmt->error);
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to success page
        header("Location: ../order_sucess.php?order_id=$orderId");
        exit;
    } catch (Exception $e) {
        error_log("Kļūda process_order.php: " . $e->getMessage());
        header("Location: ../checkout.php?error=" . urlencode("Neizdevās apstrādāt pasūtījumu."));
        exit();
    }
} else {
    header("Location: ../checkout.php?error=" . urlencode("Nederīga pieprasījuma metode."));
    exit();
}
?>