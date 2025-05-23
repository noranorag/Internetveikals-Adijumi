<?php
session_start();
include 'database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_ID'], $_POST['action'])) {
    $cartID = intval($_POST['cart_ID']);
    $action = $_POST['action'];

    // Fetch the current quantity
    $query = "SELECT quantity FROM cart WHERE cart_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $cartID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentQuantity = $row['quantity'];

        // Update quantity based on action
        if ($action === 'increase') {
            $newQuantity = $currentQuantity + 1;
        } elseif ($action === 'decrease' && $currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
        } else {
            $newQuantity = $currentQuantity; // Prevent quantity from going below 1
        }

        // Update the quantity in the database
        $updateQuery = "UPDATE cart SET quantity = ? WHERE cart_ID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('ii', $newQuantity, $cartID);
        $updateStmt->execute();
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
?>