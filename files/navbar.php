<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="/Internetveikals-Adijumi2/images/logo.png" alt="Logo" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav nav-links">
                <li class="nav-item mx-3">
                    <a class="nav-link" href="index.php">Sākums</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="eshop.php">Internetveikals</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="gallery.php">Galerija</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="fair.php">Tirdziņi</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                <a href="#" class="nav-link" onclick="checkLoginForHeart(event)">
                    <i class="fas fa-heart"></i>
                </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <span class="navbar-text"><?php echo $_SESSION['user_email']; ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <a class="dropdown-item" href="profile-editing/profile-edit.php">Mans profils</a>
                            <a class="dropdown-item" href="profile_settings.php">Profila iestatījumi</a>
                            <a class="dropdown-item" href="database/logout.php">Izlogoties</a>
                        <?php else: ?>
                            <a class="dropdown-item" href="login.php">Ielogoties</a>
                            <a class="dropdown-item" href="register.php">Reģistrēties</a>
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
                <a href="login.php" class="btn btn-primary">Ielogoties</a>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Aizvērt</button>
            </div>
        </div>
    </div>
</div>