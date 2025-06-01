<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define the project root
$projectRoot = '/Internetveikals-Adijumi2';

// Get the current directory relative to the project root
$currentDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Calculate the depth of the current directory
if (strpos($currentDir, $projectRoot) === 0) {
    $relativePath = substr($currentDir, strlen($projectRoot));
    $depth = substr_count($relativePath, '/');
} else {
    $depth = 0;
}

// Generate the base path dynamically
$basePath = $depth > 0 ? str_repeat('../', $depth) : './';

// Debugging: Output the calculated $basePath
error_log("Base Path: " . $basePath);

// Fetch cart count
$cartCount = 0;
if (isset($_SESSION['user_id']) || session_id()) {
    include_once $_SERVER['DOCUMENT_ROOT'] . $projectRoot . '/database/db_connection.php';

    $userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sessionID = session_id();

    $stmt = $conn->prepare("
        SELECT SUM(quantity) AS total_quantity 
        FROM cart 
        WHERE (ID_user = ? AND ? != 0) OR (session_ID = ? AND ? = 0)
    ");
    $stmt->bind_param("isis", $userID, $userID, $sessionID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $cartCount = $row['total_quantity'] ?? 0;
    }
}

// Fetch cart items for dropdown
$cartItems = [];
if (isset($_SESSION['user_id']) || session_id()) {
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.image 
        FROM cart c
        INNER JOIN product p ON c.ID_product = p.product_ID
        WHERE (c.ID_user = ? AND ? != 0) OR (c.session_ID = ? AND ? = 0)
    ");
    $stmt->bind_param("isis", $userID, $userID, $sessionID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<script src="<?= $basePath ?>scripts.js" defer></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?= $basePath ?>index.php">
            <img src="<?= $basePath ?>images/logo.png" alt="Logo" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav nav-links">
                <li class="nav-item mx-3">
                    <a class="nav-link" href="<?= $basePath ?>index.php">Sākums</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="<?= $basePath ?>eshop.php">Internetveikals</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="<?= $basePath ?>gallery.php">Galerija</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="<?= $basePath ?>fair.php">Tirdziņi</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="checkLoginForHeart(event)">
                        <i class="fas fa-heart"></i>
                    </a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="<?= $basePath ?>cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="badge badge-danger position-absolute cart-badge">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <div class="cart-dropdown" id="cartDropdown">
                        <?php if (!empty($cartItems)): ?>
                            <ul class="cart-items">
                                <?php foreach ($cartItems as $item): ?>
                                    <li class="cart-item">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                                        <div class="cart-item-details">
                                            <p class="cart-item-name"><?= htmlspecialchars($item['name']) ?></p>
                                            <p class="cart-item-price">€<?= number_format($item['price'], 2) ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($cartItems) > 3): ?>
                                <div class="cart-scroll-indicator">Scroll for more...</div>
                            <?php endif; ?>
                            <div class="cart-dropdown-footer">
                                <a href="<?= $basePath ?>cart.php" class="btn btn-primary btn-sm w-100">Apskatīt grozu</a>
                            </div>
                        <?php else: ?>
                            <p class="cart-empty">Tavs grozs ir tukšs.</p>
                        <?php endif; ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <span class="navbar-text"><?php echo $_SESSION['user_email']; ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <a class="dropdown-item" href="<?= $basePath ?>profile-editing/profile-edit.php">Mans profils</a>
                            <a class="dropdown-item" href="<?= $basePath ?>database/logout.php">Izlogoties</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?= $basePath ?>login.php">Ielogoties</a>
                            <a class="dropdown-item" href="<?= $basePath ?>register.php">Reģistrēties</a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

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
                <a href="<?= $basePath ?>login.php" class="btn btn-primary">Ielogoties</a>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Aizvērt</button>
            </div>
        </div>
    </div>
</div>


<?php
// Debugging: Output the calculated $basePath
error_log("Base Path: " . $basePath);
?>