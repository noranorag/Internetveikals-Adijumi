
<div class="navbar collapse show" id="sidebar">
    <div class="user-info">
        <i class="fas fa-user-circle"></i>
        <h4>
            <?php
            echo isset($_SESSION['name'], $_SESSION['surname']) 
                ? htmlspecialchars($_SESSION['name'] . ' ' . $_SESSION['surname']) 
                : 'Vārds Uzvārds';
            ?>
        </h4>
        <p>
            <?php
            echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'epasts@example.com';
            ?>
        </p>
    </div>
    <a href="index.php">Sākums</a>
    <a href="orders.php">Pasūtījumi</a>
    <a href="product.php">Preces</a>
    <a href="gallery.php">Galerija</a>
    <a href="fairs.php">Tirdziņi</a>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="user.php">Lietotāji</a>
    <?php endif; ?>
    <a class="logout" href="../database/logout.php"><i class="fas fa-sign-out-alt"></i> Izlogoties</a>
</div>
<button class="btn btn-arrow toggle-btn" type="button" data-toggle="collapse" data-target="#sidebar" aria-expanded="true" aria-controls="sidebar">
    <i class="fas fa-arrow-left"></i>
</button>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content');
        const toggleButton = document.querySelector('[data-toggle="collapse"]');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('full-width');
            if (sidebar.classList.contains('collapsed')) {
                toggleButton.style.left = '0';
                toggleButton.querySelector('i').classList.replace('fa-arrow-left', 'fa-arrow-right');
            } else {
                toggleButton.style.left = '250px';
                toggleButton.querySelector('i').classList.replace('fa-arrow-right', 'fa-arrow-left');
            }
        });
    });
</script>