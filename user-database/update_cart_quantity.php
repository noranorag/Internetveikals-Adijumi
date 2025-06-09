<?php
session_start();
include '../database/db_connection.php';

if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_ID = $_POST['cart_ID'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($cart_ID && $action) {
        $query = "SELECT quantity, ID_product FROM cart WHERE cart_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $cart_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cartItem = $result->fetch_assoc();
            $currentQuantity = $cartItem['quantity'];
            $productID = $cartItem['ID_product'];

            $productQuery = "SELECT stock_quantity FROM product WHERE product_ID = ?";
            $productStmt = $conn->prepare($productQuery);
            $productStmt->bind_param('i', $productID);
            $productStmt->execute();
            $productResult = $productStmt->get_result();

            if ($productResult->num_rows > 0) {
                $product = $productResult->fetch_assoc();
                $stockQuantity = $product['stock_quantity'];

                if ($action === 'increase' && $currentQuantity < $stockQuantity) {
                    $newQuantity = $currentQuantity + 1;
                } elseif ($action === 'decrease' && $currentQuantity > 1) {
                    $newQuantity = $currentQuantity - 1;
                } else {
                    header("Location: ../cart.php?error=Nepareiza darbība vai nepietiekams daudzums.");
                    exit;
                }

                $updateQuery = "UPDATE cart SET quantity = ? WHERE cart_ID = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('ii', $newQuantity, $cart_ID);

                if ($updateStmt->execute()) {
                    header("Location: ../cart.php");
                    exit;
                } else {
                    die("Kļūda atjauninot groza daudzumu: " . $conn->error);
                }
            } else {
                die("Produkts netika atrasts.");
            }
        } else {
            die("Groza prece netika atrasta.");
        }
    } else {
        die("Trūkst parametru.");
    }
} else {
    die("Nederīgs pieprasījuma veids.");
}
?>