<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <section class="hero-section">
        <img src="images/kombinzoni.jpeg" alt="Cover Image" class="hero-image">
        <div class="hero-text">
            <h1>Pašdarināti adījumi</h1>
            <p>Dažādu veidu adījumi visai ģimenei</p>
            <a href="eshop.php" class="hero-button">Iepērcies tagad</a>
        </div>
        <div class="hero-categories">
            <div class="category">
                <img src="images/berniem.png" alt="Bērniem" class="category-image" loading="lazy">
                <h2>Bērniem</h2>
                <a href="eshop.php?big_category=Bērniem" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/sievietem.png" alt="Sievietēm" class="category-image" loading="lazy">
                <h2>Sievietēm</h2>
                <a href="eshop.php?big_category=Sievietēm" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/viriesiem.png" alt="Vīriešiem" class="category-image" loading="lazy">
                <h2>Vīriešiem</h2>
                <a href="eshop.php?big_category=Vīriešiem" class="category-button">Apskatīt kolekciju</a>
            </div>
        </div>
    </section>

    <section class="about-us-section">
        <section class="carousel-container">
            <div class="carousel">
                <div><h3>Bērnu kombinzoni</h3></div>
                <div><h3>Dažādas zeķes</h3></div>
                <div><h3>Cepures</h3></div>
                <div><h3>Mauči</h3></div>
                <div><h3>Bērnu zeķītes</h3></div>
                <div><h3>Bērnu komplekti</h3></div>
                <div><h3>Un citi adījumi</h3></div>
                <!-- ----------------------------------- -->
                <div><h3>Bērnu kombinzoni</h3></div>
                <div><h3>Dažādas zeķes</h3></div>
                <div><h3>Cepures</h3></div>
                <div><h3>Mauči</h3></div>
                <div><h3>Bērnu zeķītes</h3></div>
            </div>
        </section>
    </section>

    <section class="popular-products-section mt-5">
    <div class="container">
        <h3 class="mb-4 text-center">Populārākie produkti</h3>
        <div class="row">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 1</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€20.00</strong></p>
                        <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 2</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€25.00</strong></p>
                        <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 3</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€30.00</strong></p>
                        <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-products-section mt-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="section-title-dark">Par produktiem</h3>
                <p class="section-text-dark">
                    Mūsu adījumi ir rūpīgi veidoti, izmantojot augstākās kvalitātes materiālus, lai nodrošinātu komfortu un izturību. 
                    Katrs produkts ir darināts ar mīlestību un uzmanību pret detaļām, lai tas būtu ne tikai praktisks, bet arī estētiski pievilcīgs. 
                    Mēs lepojamies ar mūsu plašo adījumu klāstu, kas piemērots visai ģimenei – no bērniem līdz pieaugušajiem. 
                    Izvēloties mūsu produktus, jūs iegūstat ne tikai siltumu un komfortu, bet arī unikālu dizainu, kas izceļ jūsu stilu.
                </p>
            </div>
            <div class="col-md-6 position-relative">
                <img src="images/sievietem.png" alt="Adījumi" class="about-products-image">
            </div>
        </div>
    </div>
</section>

<section class="image-text-section mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="images/berniem.png" alt="Bērniem" class="image-text-image">
            </div>
            <div class="col-md-6">
                <h3 class="section-title-dark">Par bērnu adījumiem</h3>
                <p class="section-text-dark">
                    Mūsu bērnu adījumi ir veidoti ar īpašu rūpību, lai nodrošinātu maksimālu komfortu un siltumu. 
                    Katrs adījums ir darināts no augstas kvalitātes materiāliem, kas ir maigi pret bērna ādu. 
                    Mēs piedāvājam plašu klāstu ar dažādiem dizainiem un krāsām, kas piemēroti visiem vecumiem un gaumēm.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'files/footer.php'; ?>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="scripts.js"></script>
</body>
</html>