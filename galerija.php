<?php
require 'database/db_connection.php'; // Include your database connection

$query = "
    SELECT 
        gi.gallery_ID AS gallery_id,
        gi.image AS image_path,
        u.email AS posted_by,
        u.name AS user_name,
        u.surname AS user_surname
    FROM 
        user_gallery ug
    INNER JOIN 
        gallery_images gi ON ug.ID_gallery = gi.gallery_ID
    INNER JOIN 
        user u ON ug.ID_user = u.user_ID
    WHERE 
        gi.approved = 'approved'
";

$result = mysqli_query($conn, $query);

$galleryItems = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $galleryItems[] = [
            'gallery_id' => htmlspecialchars($row['gallery_id']),
            'image_path' => htmlspecialchars($row['image_path']), // Use the full path directly
            'posted_by' => htmlspecialchars($row['posted_by']),
            'user_name' => htmlspecialchars($row['user_name']),
            'user_surname' => htmlspecialchars($row['user_surname']),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-2">Galerija</h1>
        </div>
        <div class="heading-with-lines">
            <div class="line"></div>
            <p class="page-heading">Pircēju uzņemtās bildes ar produktiem</p>
            <div class="line"></div>
        </div>
        <div class="gallery">
            <?php foreach ($galleryItems as $item): ?>
                <div class="gallery-item" data-toggle="modal" data-target="#imageModal" data-name="<?= $item['user_name'] . ' ' . $item['user_surname'] ?>" data-product="<?= $item['user_name'] . ' ' . $item['user_surname'] ?>">
                    <img src="<?= $item['image_path'] ?>" alt="Gallery Image">
                    <div class="overlay"><?= $item['user_name'] . ' ' . $item['user_surname'] ?><br></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Gallery Image">
                    <p id="modalProduct"></p>
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
            var name = button.data('name');
            var product = button.data('product');
            var imageSrc = button.find('img').attr('src');

            var modal = $(this);
            modal.find('.modal-title').text(name);
            modal.find('#modalImage').attr('src', imageSrc);
            modal.find('#modalProduct').text(product);
        });
    </script>
</body>
</html>