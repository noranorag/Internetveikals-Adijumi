
<?php
session_start();

include 'database/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) { 
    die("User not logged in.");
}

$userID = $_SESSION['user_id']; 

$query = "
    SELECT p.* 
    FROM product p
    INNER JOIN favourites f ON p.product_ID = f.product_ID
    WHERE f.user_ID = ? AND p.active = 'active'
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

error_log("Executing query for user_id: $userID");
error_log("Query: SELECT p.* FROM product p INNER JOIN favourites f ON p.product_ID = f.product_ID WHERE f.user_ID = $userID");

if ($result->num_rows > 0) {
    $favouriteProducts = $result->fetch_all(MYSQLI_ASSOC);
    error_log("Number of favourite products fetched: " . count($favouriteProducts));
} else {
    $favouriteProducts = [];
    error_log("No favourite products found for user_id: $userID");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavi favorīti</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container" style="margin-top: 90px;">
        <div style="margin-bottom: 20px;">
            <h2 class="mb-2">Tavi favorīti</h2>
            <h5 class="text-muted"><?= count($favouriteProducts) ?> produkti</h5>
        </div>

        <div class="row">
            <?php if (!empty($favouriteProducts)): ?>
                <?php foreach ($favouriteProducts as $product): ?>
                    <div class="col-md-3">
                        <div class="card mb-4 text-center">
                            <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['short_description']) ?></p>
                                <p class="card-text"><strong>€<?= htmlspecialchars($product['price']) ?></strong></p>
                                <button class="btn btn-primary" onclick="window.location.href='product-details.php?product_ID=<?= $product['product_ID'] ?>'">Apskatīt</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center text-muted">Tev vēl nav favorītu produktu.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'files/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>