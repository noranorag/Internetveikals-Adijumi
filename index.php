<?php
// Start the session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include 'database/db_connection.php';
include 'user-database/check_reserved.php';

$popularProducts = [];

try {
    // Fetch the most popular products based on quantity in orders
    $popularProductsQuery = $conn->prepare("
        SELECT 
            p.product_ID, 
            p.name, 
            p.short_description AS description, 
            p.image, 
            p.price, 
            p.reserved, 
            p.stock_quantity, 
            SUM(oi.quantity) AS total_quantity
        FROM order_items oi
        INNER JOIN product p ON oi.ID_product = p.product_ID
        GROUP BY oi.ID_product
        ORDER BY total_quantity DESC
        LIMIT 4
    ");
    $popularProductsQuery->execute();
    $result = $popularProductsQuery->get_result();
    while ($row = $result->fetch_assoc()) {
        $popularProducts[] = [
            'product_ID' => htmlspecialchars($row['product_ID']),
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'image' => htmlspecialchars($row['image']),
            'price' => htmlspecialchars($row['price']),
            'reserved' => htmlspecialchars($row['reserved']),
            'stock_quantity' => htmlspecialchars($row['stock_quantity']),
            'total_quantity' => htmlspecialchars($row['total_quantity']),
        ];
    }
    $popularProductsQuery->close();
} catch (mysqli_sql_exception $e) {
    error_log("Database query error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internetveikals</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <section class="hero-section">
        <img src="images/aizmugure1.jpg" alt="Cover Image" class="hero-image">
        <div class="hero-text">
            <h1>Pašdarināti adījumi</h1>
            <p>Dažādu veidu adījumi visai ģimenei</p>
            <a href="eshop.php" class="hero-button">Iepērcies tagad</a>
        </div>
        <div class="hero-categories">
            <div class="category">
                <img src="images/bernuKombenzons.jpg" alt="Bērniem" class="category-image" loading="lazy">
                <h2>Bērniem</h2>
                <a href="eshop.php?big_category=Bērniem" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/sieviesuZekes.jpeg" alt="Sievietēm" class="category-image" loading="lazy">
                <h2>Sievietēm</h2>
                <a href="eshop.php?big_category=Sievietēm" class="category-button">Apskatīt kolekciju</a>
            </div>
            <div class="category">
                <img src="images/viriesuZekes.jpeg" alt="Vīriešiem" class="category-image" loading="lazy">
                <h2>Vīriešiem</h2>
                <a href="eshop.php?big_category=Vīriešiem" class="category-button">Apskatīt kolekciju</a>
            </div>
        </div>
    </section>

    <section class="about-us-section">
        <section class="carousel-container">
            <div class="carousel">
                <div><h3>Bērnu kombinezoni</h3></div>
                <div><h3>Dažādas zeķes</h3></div>
                <div><h3>Cepures</h3></div>
                <div><h3>Mauči</h3></div>
                <div><h3>Bērnu zeķītes</h3></div>
                <div><h3>Bērnu komplekti</h3></div>
                <div><h3>Un citi adījumi</h3></div>
                <!-- ----------------------------------- -->
                <div><h3>Bērnu kombinezoni</h3></div>
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
                <?php foreach ($popularProducts as $product): ?>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card mb-4 text-center">
                            <?php if ($product['reserved'] == 1): ?>
                                <div class="reserved-label position-absolute text-white bg-primary px-2 py-1" style="top: 10px; left: 10px; z-index: 1; border-radius: 5px;">
                                    Rezervēts
                                </div>
                            <?php elseif ($product['stock_quantity'] == 0): ?>
                                <div class="sold-out-label position-absolute text-white bg-danger px-2 py-1" style="top: 10px; left: 10px; z-index: 1; border-radius: 5px;">
                                    Izpārdots
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text"><?php echo $product['description']; ?></p>
                                <p class="card-text"><strong>€<?php echo $product['price']; ?></strong></p>
                                <button class="btn btn-primary" onclick="window.location.href='product-details.php?product_ID=<?php echo $product['product_ID']; ?>'">Apskatīt</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<section class="about-products-section mt-5">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="section-title-dark">Par produktiem</h3>
                <p class="section-text-dark">
                    Katrs produkts ir ar rokām darināts ar rūpību un mīlestību, padarot katru izstrādājumu unikālu un īpašu. Tie nav rūpnieciski ražojumi – tie ir roku darbi, kas iemieso siltumu, pacietību un vēlmi radīt ko skaistu un noderīgu. Adījumi top nelielos daudzumos, ievērojot kvalitāti, nevis kvantitāti. Katra detaļa ir pārdomāta, katrs valdziņš – adīts ar uzmanību, lai rezultāts būtu ne tikai skaists, bet arī praktisks, ērts un ilgtspējīgs.
                </p>
            </div>
            <div class="col-md-6 position-relative">
                <img src="images/musuadijumi.jpeg" alt="Adījumi" class="about-products-image">
            </div>
        </div>
    </div>
</section>

<section class="image-text-section mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="images/bernuadijumi.jpg" alt="Bērniem" class="image-text-image">
            </div>
            <div class="col-md-6">
                <h3 class="section-title-dark">Par bērnu adījumiem</h3>
                <p class="section-text-dark">
                    Bērnu adījumi ir radīti ar īpašu maigumu un uzmanību, lai nodrošinātu siltumu un komfortu pašiem mazākajiem. Tiek izmantotas tikai mīkstas, ādai draudzīgas dzijas, kas ir piemērotas jutīgai bērnu ādai un nerada diskomfortu. Dizaini ir ne tikai vizuāli pievilcīgi, bet arī funkcionāli – viegli uzvelkami, praktiski un pielāgoti bērnu vajadzībām. Katrs adījums ir kā neliels mīlestības apliecinājums – gan bērna ikdienai, gan sirsnīgai dāvanai.
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'files/messages.php'; ?>

<?php include 'files/footer.php'; ?>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="scripts.js"></script>
</body>
</html>