
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preču Katalogs</title>
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

    <section class="product-catalog">
        <div class="filter-search-container">
            <button class="filter-button" onclick="toggleFilterPanel()">Filtrēšana</button>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Meklēt produktu">
                <button class="search-button">Meklēt</button>
            </div>
        </div>
        <div class="product-grid">
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu kombinzoni">
                <h3>Bērnu kombinzoni</h3>
                <p>€50.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/viriesiem.png" alt="Vīriešu Cepure">
                <h3>Vīriešu Cepure</h3>
                <p>€25.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/sievietem.png" alt="Sieviešu Šalle">
                <h3>Sieviešu Šalle</h3>
                <p>€35.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu Džemperis">
                <h3>Bērnu Džemperis</h3>
                <p>€30.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <!-- Add more product items as needed -->
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu kombinzoni">
                <h3>Bērnu kombinzoni</h3>
                <p>€50.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/viriesiem.png" alt="Vīriešu Cepure">
                <h3>Vīriešu Cepure</h3>
                <p>€25.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/sievietem.png" alt="Sieviešu Šalle">
                <h3>Sieviešu Šalle</h3>
                <p>€35.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu Džemperis">
                <h3>Bērnu Džemperis</h3>
                <p>€30.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu kombinzoni">
                <h3>Bērnu kombinzoni</h3>
                <p>€50.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/viriesiem.png" alt="Vīriešu Cepure">
                <h3>Vīriešu Cepure</h3>
                <p>€25.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/sievietem.png" alt="Sieviešu Šalle">
                <h3>Sieviešu Šalle</h3>
                <p>€35.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
            <div class="product-item">
                <img src="images/berniem.png" alt="Bērnu Džemperis">
                <h3>Bērnu Džemperis</h3>
                <p>€30.00</p>
                <a href="#" class="product-button">Pirkt</a>
            </div>
        </div>
    </section>

    <div class="filter-panel" id="filterPanel">
        <h3>Filtrēšana</h3>
        <div class="filter-option">
            <label for="dzimums">Dzimums</label>
            <select id="dzimums">
                <option value="visi">Visi</option>
                <option value="viriesi">Vīrieši</option>
                <option value="sievietes">Sievietes</option>
                <option value="berni">Bērni</option>
            </select>
        </div>
        <div class="filter-option">
            <label for="krasa">Krāsa</label>
            <select id="krasa">
                <option value="visi">Visas</option>
                <option value="sarkana">Sarkana</option>
                <option value="zila">Zila</option>
                <option value="zalā">Zaļā</option>
                <option value="melna">Melna</option>
            </select>
        </div>
        <div class="filter-option">
            <label for="kategorija">Kategorija</label>
            <select id="kategorija">
                <option value="visi">Visas</option>
                <option value="apgerbi">Apģērbi</option>
                <option value="aksesuari">Aksesuāri</option>
                <option value="apavi">Apavi</option>
            </select>
        </div>
        <div class="filter-option">
            <label for="izmers">Izmērs</label>
            <select id="izmers">
                <option value="visi">Visi</option>
                <option value="s">S</option>
                <option value="m">M</option>
                <option value="l">L</option>
                <option value="xl">XL</option>
            </select>
        </div>
        <div class="filter-option">
            <label for="sezona">Sezona</label>
            <select id="sezona">
                <option value="visi">Visas</option>
                <option value="ziema">Ziema</option>
                <option value="pavasaris">Pavasaris</option>
                <option value="vasara">Vasara</option>
                <option value="rudens">Rudens</option>
            </select>
        </div>
    </div>

    <div id="overlay"></div>

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


