
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header-banner">
        <span class="banner-text active">Pirkumiem virs 70 eiro bezmaksas piegāde</span>
        <span class="banner-text">Nopērc kvalitatīvus adījumus jau šodien</span>
    </div>
    <nav class="navigation">
        <div class="nav-logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">SĀKUMS</a></li>
            <li><a href="internetveikals.php">INTERNETVEIKALS</a></li>
            <li><a href="par-mani.php">PAR MANI</a></li>
            <li><a href="galerija.php">GALERIJA</a></li>
            <li><a href="tirdzini.php">TIRDZIŅI</a></li>
        </ul>
        <div class="nav-icons">
            <a href="#" data-hover-text="Mans profils"><i class="fas fa-user"></i></a>
            <a href="#" data-hover-text="Iepirkumu grozs"><i class="fas fa-shopping-cart"></i></a>
            <a href="#" data-hover-text="Favorīti"><i class="fas fa-heart"></i></a>
        </div>
    </nav>

    <section class="gallery">
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="images/berniem.png" alt="Image 1" onclick="openModal(this)">
                <div class="overlay">Bērnu kombinzoni</div>
            </div>
            <div class="gallery-item">
                <img src="images/sievietem.png" alt="Image 2" onclick="openModal(this)">
                <div class="overlay">Sieviešu Šalle</div>
            </div>
            <div class="gallery-item">
                <img src="images/viriesiem.png" alt="Image 3" onclick="openModal(this)">
                <div class="overlay">Vīriešu Cepure</div>
            </div>
            <div class="gallery-item">
                <img src="images/berniem.png" alt="Image 4" onclick="openModal(this)">
                <div class="overlay">Bērnu kombinzoni</div>
            </div>
            <div class="gallery-item">
                <img src="images/sievietem.png" alt="Image 5" onclick="openModal(this)">
                <div class="overlay">Sieviešu Šalle</div>
            </div>
            <div class="gallery-item">
                <img src="images/viriesiem.png" alt="Image 6" onclick="openModal(this)">
                <div class="overlay">Vīriešu Cepure</div>
            </div>
            <div class="gallery-item">
                <img src="images/berniem.png" alt="Image 7" onclick="openModal(this)">
                <div class="overlay">Bērnu kombinzoni</div>
            </div>
            <div class="gallery-item">
                <img src="images/sievietem.png" alt="Image 8" onclick="openModal(this)">
                <div class="overlay">Sieviešu Šalle</div>
            </div>
            <div class="gallery-item">
                <img src="images/viriesiem.png" alt="Image 9" onclick="openModal(this)">
                <div class="overlay">Vīriešu Cepure</div>
            </div>
            <div class="gallery-item">
                <img src="images/berniem.png" alt="Image 10" onclick="openModal(this)">
                <div class="overlay">Bērnu kombinzoni</div>
            </div>
        </div>
    </section>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <img src="images/logo.png" alt="Logo">
            </div>
            <nav class="footer-nav">
                <ul>
                    <li><a href="#">Sākums</a></li>
                    <li><a href="#">Par Mums</a></li>
                    <li><a href="#">Produkti</a></li>
                    <li><a href="#">Kontakti</a></li>
                </ul>
            </nav>
            <div class="footer-links">
                <a href="#">Privātuma Politika</a>
                <a href="#">Sīkdatņu Politika</a>
            </div>
        </div>
    </footer>
</body>
</html>