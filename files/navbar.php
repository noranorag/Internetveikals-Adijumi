<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (headers_sent($file, $line)) {
    error_log("Headers already sent in $file on line $line");
}

// Dynamically calculate the base path to always point to the root of the project
$basePath = '/'; // Set this to the root path of your project
$absoluteBasePath = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $basePath;

// Ensure the logo and links work correctly in subfolders
$isLoggedIn = isset($_SESSION['user_id']);
$userEmail = $isLoggedIn ? htmlspecialchars($_SESSION['user_email']) : null;

if ($isLoggedIn) {
    error_log("User is logged in. Email: " . $userEmail);
} else {
    error_log("User is not logged in.");
}

$cartCount = 0;
$cartItems = [];
if ($isLoggedIn || session_id()) {
    include_once $_SERVER['DOCUMENT_ROOT'] . '/database/db_connection.php';

    $userID = $isLoggedIn ? $_SESSION['user_id'] : 0;
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

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<script src="<?= $absoluteBasePath ?>scripts.js" defer></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?= $absoluteBasePath ?>index.php">
            <img src="<?= $absoluteBasePath ?>images/logo.png" alt="Logo" style="height: 40px;">
        </a>

        <!-- Navbar toggler (three lines icon) -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav nav-links">
                <li class="nav-item mx-3 <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= $absoluteBasePath ?>index.php">Sākums</a>
                </li>
                <li class="nav-item mx-3 <?= $currentPage === 'eshop.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= $absoluteBasePath ?>eshop.php">Internetveikals</a>
                </li>
                <li class="nav-item mx-3 <?= $currentPage === 'gallery.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= $absoluteBasePath ?>gallery.php">Galerija</a>
                </li>
                <li class="nav-item mx-3 <?= $currentPage === 'fair.php' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= $absoluteBasePath ?>fair.php">Tirdziņi</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= $absoluteBasePath ?>favourites.php" class="nav-link" onclick="checkLoginForHeart(event)">
                        <i class="fas fa-heart"></i>
                    </a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link" href="<?= $absoluteBasePath ?>cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="badge badge-danger position-absolute cart-badge">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <?php if ($isLoggedIn): ?>
                            <span class="navbar-text"><?= $userEmail ?></span>
                        <?php else: ?>
                            <span class="navbar-text">Viesis</span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <?php if ($isLoggedIn): ?>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>profile-editing/profile-edit.php">Mans profils</a>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>profile-editing/user-gallery.php">Manas bildes</a>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>profile-editing/user-orders.php">Mani pasūtījumi</a>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>database/logout.php">Izlogoties</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>login.php?redirect=index.php">Ielogoties</a>
                            <a class="dropdown-item" href="<?= $absoluteBasePath ?>register.php">Reģistrēties</a>
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
                <a href="<?= $absoluteBasePath ?>login.php" class="btn btn-primary">Ielogoties</a>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Aizvērt</button>
            </div>
        </div>
    </div>
</div>

<?php
if (headers_sent($file, $line)) {
    error_log("Headers already sent in $file on line $line");
}
?>