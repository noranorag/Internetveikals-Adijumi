<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <section class="hero-section">
        <img src="images/kombinzoni.jpeg" alt="Cover Image" class="hero-image">
        <div class="hero-text">
            <h1>Pašdarināti adījumi</h1>
            <p>Dažādu veidu adījumi visai ģimenei</p>
            <a href="#" class="hero-button">Iepērcies tagad</a>
        </div>
        <div class="hero-categories">
            <div class="category">
                <img src="images/berniem.png" alt="Bērniem" class="category-image" loading="lazy">
                <h2>Bērniem</h2>
                <a href="#" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/sievietem.png" alt="Sievietēm" class="category-image" loading="lazy">
                <h2>Sievietēm</h2>
                <a href="#" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/viriesiem.png" alt="Vīriešiem" class="category-image" loading="lazy">
                <h2>Vīriešiem</h2>
                <a href="#" class="category-button">Apskatīt kolekciju</a>
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
</body>
</html>