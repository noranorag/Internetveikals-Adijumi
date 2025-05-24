<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'database/db_connection.php';

if (isset($_GET['product_ID'])) {
    $product_ID = intval($_GET['product_ID']); 
    $stmt = $conn->prepare("SELECT * FROM product WHERE product_ID = ?");
    $stmt->bind_param("i", $product_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

$favorites = [];
if (isset($_SESSION['user_id'])) { 
    $userID = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT product_ID FROM favourites WHERE user_ID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row['product_ID'];
    }
}

$isFavorite = false;
if (isset($_SESSION['user_id'])) { 
    $stmt = $conn->prepare("SELECT * FROM favourites WHERE user_ID = ? AND product_ID = ?");
    $stmt->bind_param("ii", $userID, $product_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $isFavorite = $result->num_rows > 0;

    error_log("Checking favorites for user_id: $userID, product_ID: $product_ID");
    error_log("Query result: " . ($isFavorite ? "Favorite" : "Not Favorite"));
}

// Check if the product is already in the cart
$isInCart = false;
if (isset($_SESSION['user_id']) || session_id()) {
    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sessionID = session_id();

    $stmt = $conn->prepare("
        SELECT * FROM cart 
        WHERE ((ID_user = ? AND ID_user != 0) OR (session_ID = ? AND ID_user = 0)) 
        AND ID_product = ?
    ");
    $stmt->bind_param("isi", $userID, $sessionID, $product_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $isInCart = $result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkta detaļas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js" defer></script>
</head>
<body>
<div class="announcement" id="announcement"></div>
    <?php include 'files/navbar.php'; ?>

    <div id="cartNotification" class="cart-notification" style="display: none;">
    <div class="notification-content">
        <img id="notificationImage" src="" alt="Product Image" class="notification-image">
        <div class="notification-text">
            <p id="notificationName" class="notification-name"></p>
            <p id="notificationPrice" class="notification-price"></p>
            <p class="notification-message">Produkts ievietots grozā</p>
            <button class="btn btn-primary" onclick="window.location.href='cart.php'">Apskatīt grozu</button>
        </div>
    </div>
</div>

    <div class="container" style="margin-top: 75px;"> 
    <div class="container upper mt-3">
        <button class="btn" onclick="history.back()" style="background: none; border: none; font-size: 1.5rem; color: inherit; padding: 0; margin-bottom: 10px;">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="col-md-6">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-muted"><?= htmlspecialchars($product['short_description']) ?></p>
            <div class="d-flex justify-content-between align-items-center">
                <p class="h4 price-text">€<?= htmlspecialchars($product['price']) ?></p>
                <p class="text-muted stock-text">Atlikušas <?= htmlspecialchars($product['stock_quantity']) ?> preces</p>
            </div>
            <hr style="border-top: 1px solid #ccc;">
            <p class="no-margin"><strong>Apraksts:</strong></p>
            <p class="little-margin"><?= htmlspecialchars($product['long_description']) ?></p>
            <ul class="list-unstyled">
                <p class="no-margin"><strong>Krāsa:</strong></p>
                <p class="little-margin"><?= htmlspecialchars($product['color']) ?></p>
                <p class="no-margin"><strong>Izmērs:</strong></p>
                <p class="little-margin"><?= htmlspecialchars($product['size']) ?></p>
            </ul>
            <div class="d-flex align-items-center">
                <button class="btn quantity-btn" onclick="decreaseQuantity()">-</button>
                <input type="text" id="quantity" class="form-control quantity-input text-center" value="1" readonly>
                <button class="btn quantity-btn" onclick="increaseQuantity()">+</button>
                <button 
                    class="btn btn-success ml-3" 
                    id="addToCartButton" 
                    onclick="addToCart(<?= $product_ID ?>)" 
                    <?= $isInCart ? 'disabled' : '' ?>>
                    <?= $isInCart ? '<i class="fas fa-check"></i> Jau grozā' : 'Ielikt grozā' ?>
                </button>
                <button class="btn heart-btn ml-3" data-product-id="<?= $product['product_ID'] ?>" onclick="toggleFavourite(<?= $product['product_ID'] ?>, this)">
                    <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-5">
    <div class="col-md-12 text-center">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('material', 0)">Materiāls</h5>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('care', 1)">Rūpes</h5>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('details', 2)">Detaļas</h5>
            </div>
        </div>
        <div class="line-container mt-2">
            <div class="line"></div>
            <div class="line-highlight"></div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <p id="material" class="toggle-content">
            <?= htmlspecialchars($product['material']) ?>
        </p>
        <p id="care" class="toggle-content" style="display: none;">
            <?= htmlspecialchars($product['care']) ?>
        </p>
        <p id="details" class="toggle-content" style="display: none;">
            <?= htmlspecialchars("Produkts: {$product['name']}. {$product['short_description']} {$product['long_description']} Izmērs: {$product['size']}, krāsa: {$product['color']}. Cena: €{$product['price']}. Pieejams daudzums: {$product['stock_quantity']}.") ?>
        </p>
    </div>
</div>

<div class="container upper mt-5">
    <h3 class="mb-4">Produkti, kas der komplektā ar šo</h3>
    <div class="row">
        <?php
        $stmt = $conn->prepare("
            SELECT DISTINCT p.product_ID, p.name, p.short_description, p.price, p.image 
            FROM product p
            INNER JOIN product_sets ps ON p.product_ID = ps.ID_product
            INNER JOIN sets s ON ps.ID_set = s.set_ID
            WHERE ps.ID_set IN (
                SELECT ps2.ID_set 
                FROM product_sets ps2 
                WHERE ps2.ID_product = ?
            ) 
            AND p.product_ID != ? 
            AND p.active = 1 
            AND ps.active = 1 
            AND s.active = 1
        ");
        $stmt->bind_param("ii", $product_ID, $product_ID);
        $stmt->execute();
        $relatedProducts = $stmt->get_result();

        if ($relatedProducts->num_rows > 0) {
            while ($relatedProduct = $relatedProducts->fetch_assoc()) {
                ?>
                <div class="col-md-3">
                    <div class="card mb-4 text-center">
                        <img src="<?= htmlspecialchars($relatedProduct['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($relatedProduct['short_description']) ?></p>
                            <p class="card-text"><strong>€<?= htmlspecialchars($relatedProduct['price']) ?></strong></p>
                            <button class="btn btn-primary" onclick="window.location.href='product-details.php?product_ID=<?= $relatedProduct['product_ID'] ?>'">Apskatīt</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='textin'>Nav pieejamu produktu komplektā ar šo.</p>";
        }
        ?>
    </div>
</div>
</div>

<div id="loginModal" class="modal login-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tu neesi ielogojies</h5>
                <button type="button" class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Vai ielogoties?</p>
            </div>
            <div class="modal-footer">
                <a href="login.php" class="btn btn-primary">Ielogoties</a>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Aizvērt</button>
            </div>
        </div>
    </div>
</div>

 <?php include 'files/messages.php'; ?>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'files/footer.php'; ?>


</body>
</html>

