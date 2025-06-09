<?php
session_start();
require '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) { 
    die("Lietotāja ID nav atrasts sesijā.");
}

$userId = $_SESSION['user_id'];

$sqlGallery = "
    SELECT gi.gallery_ID, gi.image, gi.uploaded_at, gi.approved, gi.review
    FROM gallery_images gi
    INNER JOIN user_gallery ug ON gi.gallery_ID = ug.ID_gallery
    WHERE ug.ID_user = ?
    ORDER BY gi.uploaded_at DESC
";
$stmtGallery = $conn->prepare($sqlGallery);
$stmtGallery->bind_param('i', $userId);
$stmtGallery->execute();
$resultGallery = $stmtGallery->get_result();

$galleryImages = [];
while ($image = $resultGallery->fetch_assoc()) {
    $galleryImages[] = $image;
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jūsu pasūtījumi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles.css">
    <script src="../scripts.js" defer></script>
</head>
<body>
    <div class="announcement" id="announcement"></div>
    <?php include '../files/navbar.php'; ?>

    <div class="container" id="orders-container">
        <h3 class="text-center mb-4">Ievietotās galerijas bildes</h3>
        <?php if (empty($galleryImages)): ?>
            <p class="text-center text-muted">Tev nav ievietotas galerijas bildes, tās var ievietot galerijas sadaļā.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($galleryImages as $image): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars('../' . $image['image']) ?>" class="card-img-top" alt="Galerijas bilde">
                        <div class="card-body text-center">
                            <p class="card-text"><strong>Augšupielādēts:</strong> <?= htmlspecialchars((new DateTime($image['uploaded_at']))->format('d/m/Y')) ?></p>
                            <p class="card-text">
                                <strong>Statuss:</strong>
                                <?php 
                                if ($image['approved'] === '1') {
                                    echo 'Apstiprināts';
                                } elseif ($image['approved'] === '0') {
                                    echo 'Noraidīts';
                                } else {
                                    echo 'Gaida apstiprinājumu';
                                }
                                ?>
                            </p>
                            <p class="card-text">
                                <strong>Komentārs:</strong>
                                <?= htmlspecialchars($image['review']) ?>
                            </p>
                            <button class="btn btn-danger btn-sm mt-2 custom-delete-btn" data-toggle="modal" data-target="#deleteImageModal" data-id="<?= htmlspecialchars($image['gallery_ID']) ?>">Dzēst</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>


    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">Vai tiešām vēlies dzēst šo bildi?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Šī darbība ir neatgriezeniska.</p>
                    <input type="hidden" id="deleteImageId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteImage">Dzēst</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>