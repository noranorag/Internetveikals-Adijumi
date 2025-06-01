<?php
session_start();
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_ID'])) {
    $cartID = intval($_POST['cart_ID']);

    $query = "DELETE FROM cart WHERE cart_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $cartID);

    if ($stmt->execute()) {
        header('Location: ../cart.php');
        exit;
    } else {
        echo "Kļūda dzēšot preci no groza.";
    }
} else {
    echo "Nederīgs pieprasījums.";
}
?>