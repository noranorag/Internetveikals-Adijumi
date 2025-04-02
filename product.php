<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkta Informācija</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'files/navbar.php'; ?>

    <?php
    $productName = $_GET['name'] ?? 'Produkta Nosaukums';
    $shortDescription = $_GET['short'] ?? 'Īss apraksts par produktu.';
    $longDescription = $_GET['long'] ?? 'Garāks apraksts par produktu.';
    $color = $_GET['color'] ?? 'Krāsa nav norādīta';
    $size = $_GET['size'] ?? 'Izmērs nav norādīts';
    $material = $_GET['material'] ?? 'Materiāls nav norādīts';
    $care = $_GET['care'] ?? 'Kopšanas instrukcijas nav norādītas';
    $price = $_GET['price'] ?? 'Cena nav norādīta';
    $image = $_GET['image'] ?? 'images/default.png';
    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($image); ?>" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($productName); ?></h1>
                <p><strong>Īss apraksts:</strong> <?php echo htmlspecialchars($shortDescription); ?></p>
                <p><strong>Garš apraksts:</strong> <?php echo htmlspecialchars($longDescription); ?></p>
                <p><strong>Krāsa:</strong> <?php echo htmlspecialchars($color); ?></p>
                <p><strong>Izmērs:</strong> <?php echo htmlspecialchars($size); ?></p>
                <p><strong>Materiāls:</strong> <?php echo htmlspecialchars($material); ?></p>
                <p><strong>Kopšana:</strong> <?php echo htmlspecialchars($care); ?></p>
                <p><strong>Cena:</strong> €<?php echo htmlspecialchars($price); ?></p>
                <div class="d-flex align-items-center">
                    <button class="btn btn-success mr-3">Ielikt grozā</button>
                    <button class="btn btn-outline-danger">
                        <i class="fas fa-heart"></i> Pievienot favorītiem
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>