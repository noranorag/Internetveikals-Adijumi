<?php
require 'database/db_connection.php'; // Include your database connection

$query = "
    SELECT 
        f.fair_ID AS id,
        f.name AS name,
        f.description AS description,
        f.image AS image,
        f.link AS link
    FROM 
        fair f
    WHERE 
        f.active = 'active'
    ORDER BY 
        f.fair_ID DESC
";

$result = mysqli_query($conn, $query);

$fairs = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fairs[] = [
            'id' => htmlspecialchars($row['id']),
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'image' => htmlspecialchars($row['image']),
            'link' => htmlspecialchars($row['link']),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tirdziņi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-2">Tirdziņi</h1>
        </div>
        <div class="heading-with-lines">
            <div class="line"></div>
            <p class="page-heading">Tirdziņi, kuros mani satikt</p>
            <div class="line"></div>
        </div>
        <div class="row">
            <?php foreach ($fairs as $fair): ?>
                <div class="col-md-6">
                    <div class="market-item">
                        <img src="<?= $fair['image'] ?>" alt="<?= $fair['name'] ?>" class="market-image" data-toggle="modal" data-target="#imageModal" data-src="<?= $fair['image'] ?>">
                        <div>
                            <h3><?= $fair['name'] ?></h3>
                            <p><?= $fair['description'] ?></p>
                            <a href="<?= $fair['link'] ?>" class="market-link" target="_blank">Apskati <?= $fair['name'] ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Tirgus plakāts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Market Image">
                </div>
            </div>
        </div>
    </div>

    <?php include 'files/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="scripts.js"></script>
    <script>
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var imageSrc = button.data('src');
            var modal = $(this);
            modal.find('#modalImage').attr('src', imageSrc);
        });
    </script>
</body>
</html>