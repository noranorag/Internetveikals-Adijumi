<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { 
    die("Lietotāja ID nav atrasts sesijā.");
}

include '../user-database/check_notpaid.php';

$userId = $_SESSION['user_id']; 

// Iegūt pasūtījumus pieslēgtajam lietotājam
$sqlOrders = "
    SELECT o.order_ID, o.created_at, o.status, o.delivery, o.pickup_address, o.total_amount, o.delivery_number, o.invoice_path
    FROM orders o
    INNER JOIN user_order uo ON o.order_ID = uo.ID_order
    WHERE uo.ID_user = ?
    ORDER BY o.created_at DESC
";
$stmtOrders = $conn->prepare($sqlOrders);
$stmtOrders->bind_param('i', $userId);
$stmtOrders->execute();
$resultOrders = $stmtOrders->get_result();

$orders = [];
while ($order = $resultOrders->fetch_assoc()) {
    // Iegūt produktus katram pasūtījumam
    $sqlProducts = "
        SELECT p.name, p.image, oi.quantity, oi.price
        FROM order_items oi
        INNER JOIN product p ON oi.ID_product = p.product_ID
        WHERE oi.ID_order = ?
    ";
    $stmtProducts = $conn->prepare($sqlProducts);
    $stmtProducts->bind_param('i', $order['order_ID']);
    $stmtProducts->execute();
    $resultProducts = $stmtProducts->get_result();

    $products = [];
    while ($product = $resultProducts->fetch_assoc()) {
        $products[] = $product;
    }

    $order['products'] = $products;
    $orders[] = $order;
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jūsu pasūtījumi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js" defer></script>
</head>
<body>
    <div class="announcement" id="announcement"></div>
    <?php include '../files/navbar.php'; ?>

    <div class="container" id="orders-container">
        <h3 class="text-center mb-4">Visi apstrādātie un nosūtītie pasūtījumi</h3>
        <?php if (empty($orders)): ?>
            <p class="text-center text-muted">Tev nav pasūtījumi</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <div class="order-card bg-white p-4 rounded shadow-sm mb-4 <?= $order['status'] === 'Neapmaksāts' ? 'dimmed' : '' ?>">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        Pasūtījums #<?= htmlspecialchars($order['order_ID']) ?>
                        <?php if ($order['status'] === 'Nosūtīts' && !empty($order['delivery_number']) && $order['delivery_number'] !== '0'): ?>
                            <span class="text-muted"> | Piegādes numurs: <?= htmlspecialchars($order['delivery_number']) ?></span>
                        <?php endif; ?>
                    </h4>
                    <span class="text-muted">Pasūtījuma datums: <?= htmlspecialchars((new DateTime($order['created_at']))->format('d/m/Y')) ?></span>
                </div>

                <div class="order-products mb-3">
                    <?php foreach ($order['products'] as $product): ?>
                    <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                        <img src="<?= htmlspecialchars('../' . $product['image']) ?>" alt="Produkta attēls" class="img-thumbnail mr-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                            <p class="mb-0 text-muted">Daudzums: <?= $product['quantity'] ?> | Cena: €<?= number_format($product['price'], 2) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3">
                    <span>
                        <strong>Statuss:</strong> <?= htmlspecialchars($order['status']) ?>
                        <?php if (!empty($order['invoice_path'])): ?>
                            <a href="<?= htmlspecialchars('../' . $order['invoice_path']) ?>" target="_blank" class="btn btn-sm btn-invoice ml-3">Atvērt rēķinu</a>
                        <?php endif; ?>
                    </span>
                    <div>
                        <strong>Kopā: €<?= number_format($order['total_amount'], 2) ?></strong>
                        <?php if ($order['status'] === 'Nosūtīts'): ?>
                            <?php 
                            // Determine the tracking URL based on the delivery method
                            $trackingUrl = '';
                            if (strpos($order['delivery'], 'omniva') !== false) {
                                $trackingUrl = 'https://www.omniva.lv/sutijumu-izsekosana/';
                            } elseif (strpos($order['delivery'], 'dpd') !== false) {
                                $trackingUrl = 'https://www.dpd.com/lv/lv/sanemsana/sutijumu-izsekosana/';
                            } elseif (strpos($order['delivery'], 'latvijas pasts') !== false) {
                                $trackingUrl = 'https://pasts.lv/lv/kategorija/sutijumu_sekosana/?locale=lv-LV';
                            }
                            ?>
                            <?php if (!empty($trackingUrl)): ?>
                                <a href="<?= htmlspecialchars($trackingUrl) ?>" target="_blank" class="btn btn-track-order ml-3">Izsekot pasūtījumu</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>