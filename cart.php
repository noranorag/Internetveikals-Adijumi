<?php
session_start();
include 'database/db_connection.php';

if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

include 'user-database/check_reserved.php';

$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$sessionID = session_id();

if ($userID) {
    $query = "
        SELECT c.*, p.name, p.price, p.image, p.reserved, p.stock_quantity 
        FROM cart c
        INNER JOIN product p ON c.ID_product = p.product_ID
        WHERE c.ID_user = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userID);
} else {
    $query = "
        SELECT c.*, p.name, p.price, p.image, p.reserved, p.stock_quantity 
        FROM cart c
        INNER JOIN product p ON c.ID_product = p.product_ID
        WHERE c.session_ID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $sessionID);
}

$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
if ($result->num_rows > 0) {
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);
}

$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

$freeShippingThreshold = 55;
$remainingAmount = max(0, $freeShippingThreshold - $totalPrice); 
$progressPercentage = min(100, ($totalPrice / $freeShippingThreshold) * 100); 

$shippingPrice = $remainingAmount <= 0 ? 0 : 5;

$hasError = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    foreach ($cartItems as $item) {
        if ($item['reserved'] == 1 || $item['stock_quantity'] == 0) {
            $hasError = true;
            $errorMessage = 'Daži produkti tavā grozā ir rezervēti vai nav pieejami noliktavā.';
            break;
        }

        if ($item['quantity'] > $item['stock_quantity']) {
            $hasError = true;
            $errorMessage = 'Daži produkti tavā grozā pārsniedz pieejamo daudzumu noliktavā.';
            break;
        }
    }

    if (!$hasError) {
        $freeShipping = $remainingAmount <= 0 ? 'true' : 'false';
        header("Location: checkout.php?freeShipping=$freeShipping");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tavs grozs</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
  <script src="scripts.js" defer></script>
</head>
<body>

<?php if ($hasError): ?>
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-0 w-100 text-center" role="alert" style="z-index: 1050;">
        <strong><?= htmlspecialchars($errorMessage) ?></strong>
    </div>
<?php endif; ?>

  <div class="announcement" id="announcement"></div>

  <?php include 'files/navbar.php'; ?>

  <div class="container mt-5 pt-5">
    <h2>Tavs grozs</h2>
    <p><?= count($cartItems) ?> preces</p>

    <div class="free-shipping-box d-flex justify-content-between align-items-center">
        <div style="flex: 1">
            <?php if ($remainingAmount > 0): ?>
                <p class="mb-2">Tev vēl ir nepieciešami <?= number_format($remainingAmount, 2) ?>€ līdz bezmaksas piegādei</p>
            <?php else: ?>
                <p class="mb-2">Bezmaksas piegāde!</p>
            <?php endif; ?>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-secondary" style="width: <?= $progressPercentage ?>%;"></div>
            </div>
        </div>
        <div class="ml-4">
            <a href="eshop.php" class="btn btn-outline-secondary">Turpināt iepirkties</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="product-box d-flex mb-3 position-relative">
                        <div class="col-3">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid">
                        </div>
                        <div class="col-9 d-flex flex-column justify-content-between pl-3">
                            <div>
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <form action="user-database/update_cart_quantity.php" method="POST" class="d-inline">
                                    <input type="hidden" name="cart_ID" value="<?= $item['cart_ID'] ?>">
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">-</button>
                                </form>

                                <input type="text" class="form-control form-control-sm mx-2 text-center" value="<?= $item['quantity'] ?>" style="width: 50px;" readonly>

                                <form action="user-database/update_cart_quantity.php" method="POST" class="d-inline">
                                    <input type="hidden" name="cart_ID" value="<?= $item['cart_ID'] ?>">
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">+</button>
                                </form>

                                <div class="ml-auto font-weight-bold">€<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                            </div>
                        </div>
                    
                        <form action="user-database/remove_from_cart.php" method="POST" class="position-absolute" style="top: 10px; right: 10px;">
                            <input type="hidden" name="cart_ID" value="<?= $item['cart_ID'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tavs grozs ir tukšs.</p>
            <?php endif; ?>
        </div>

            <div class="col-md-4">
                <div class="summary-box">
                    <h5 class="mb-4">Kopsavilkums</h5>
                    <div class="border-top pt-3">
                        <?php if (!empty($cartItems)): ?>
                            <?php foreach ($cartItems as $item): ?>
                                <div class="d-flex justify-content-between">
                                    <span><?= htmlspecialchars($item['name']) ?></span>
                                    <span>€<?= number_format($item['price'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3 mt-3">
                        <strong>Kopā</strong>
                        <strong>€<?= number_format($totalPrice, 2) ?></strong>
                    </div>
                    <?php if ($remainingAmount > 0): ?>
                        <div class="d-flex justify-content-between mt-2">
                            <span></span> 
                            <span class="text-muted">+ Piegādes cena</span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $freeShipping = $remainingAmount <= 0;
                    ?>
                    <form method="POST">
                        <input type="hidden" name="checkout" value="1">
                        <button class="btn btn-dark w-100 mt-4">Apmaksāt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>

  <?php include 'files/messages.php'; ?>

  <?php include 'files/footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>