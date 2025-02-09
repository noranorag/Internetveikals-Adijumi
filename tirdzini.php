
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tirdziņi</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="script.js" defer></script>
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

    <div id="overlay"></div>

    <section class="fairs">
        <h2>Tirdziņi, kuros mani satikt</h2>
        <div class="filter-container">
            <button class="filter-button" onclick="toggleFilterPanel()">Filtrēšana</button>
        </div>
        <div class="filter-panel" id="filterPanel">
            <h3>Filtrēt pēc datuma</h3>
            <label for="start-date">Sākuma datums:</label>
            <input type="date" id="start-date">
            <label for="end-date">Beigu datums:</label>
            <input type="date" id="end-date">
            <button onclick="applyFilter()">Piemērot</button>
        </div>
        <div class="fairs-header">Nākamie tirdziņi</div>
        <div class="fairs-grid">
            <div class="fair">
                <img src="images/tirgus.jpg" alt="Fair 1">
                <div class="fair-details">
                    <h3>Emīla tirgus</h3>
                    <p>16. jūnijā no pulksten 10:00, Ēdoles pilī</p>
                    <a href="https://example.com/fair1" target="_blank">Apmeklēt tirgus lapu</a>
                </div>
            </div>
            <div class="fair">
                <img src="images/tirgus2.jpg" alt="Fair 2">
                <div class="fair-details">
                    <h3>Pavasara tirgus</h3>
                    <p>7. aprīlī no pulksten 9:00, Smiltenē, Baznīcas laukumā</p>
                    <a href="https://example.com/fair2" target="_blank">Apmeklēt tirgus lapu</a>
                </div>
            </div>
        </div>
        <div class="fairs-header">Bijušie tirdziņi</div>
        <div class="fairs-grid past-fairs">
            <div class="fair past">
                <img src="images/tirgus2.jpg" alt="Fair 3">
                <div class="fair-details">
                    <h3>Rudens tirgus</h3>
                    <p>15. oktobrī no pulksten 11:00, Rīgas centrāltirgū</p>
                    <a href="https://example.com/fair3" target="_blank">Apmeklēt tirgus lapu</a>
                </div>
            </div>
            <div class="fair past">
                <img src="images/tirgus.jpg" alt="Fair 4">
                <div class="fair-details">
                    <h3>Ziemassvētku tirgus</h3>
                    <p>24. decembrī no pulksten 12:00, Doma laukumā</p>
                    <a href="https://example.com/fair4" target="_blank">Apmeklēt tirgus lapu</a>
                </div>
            </div>
        </div>
    </section>  

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