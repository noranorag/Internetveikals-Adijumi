<?php session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Logo" style="height: 40px;">
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
                    <a class="nav-link" href="internetveikals.php">Internetveikals</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="galerija.php">Galerija</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="tirdzini.php">Tirdziņi</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-heart"></i></a>
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
                            <a class="dropdown-item" href="profile.php">Mans profils</a>
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