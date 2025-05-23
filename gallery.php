<?php
require 'database/db_connection.php'; 

// Number of images per page
$imagesPerPage = 21;

// Get the current page from URL (default to 1)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for SQL LIMIT clause
$offset = ($page - 1) * $imagesPerPage;

// First, get total number of approved images to calculate total pages
$countQuery = "
    SELECT COUNT(*) AS total 
    FROM user_gallery ug
    INNER JOIN gallery_images gi ON ug.ID_gallery = gi.gallery_ID
    WHERE gi.approved = 'approved'
";

$countResult = mysqli_query($conn, $countQuery);
$totalImages = 0;
if ($countResult) {
    $row = mysqli_fetch_assoc($countResult);
    $totalImages = (int)$row['total'];
}

// Calculate total pages
$totalPages = ceil($totalImages / $imagesPerPage);

// Now, get the images for the current page only
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
    LIMIT $imagesPerPage OFFSET $offset
";

$result = mysqli_query($conn, $query);

$galleryItems = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $galleryItems[] = [
            'gallery_id' => htmlspecialchars($row['gallery_id']),
            'image_path' => htmlspecialchars($row['image_path']), 
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
    <script src="scripts.js" defer></script>
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-2">Galerija</h1>
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

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Gallery Image">
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <p class="mb-0 text-center">Pievienoja lietotājs: <span id="modalProduct"></span></p>
                </div>
            </div>
        </div>
    </div>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
            <!-- Previous Page Link -->
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>" tabindex="-1">&laquo;</a>
            </li>

            <!-- Page Number Links -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>

            <!-- Next Page Link -->
            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
            </li>
        </ul>
    </nav>

    <div class="container mt-5">
        <div class="add-to-gallery d-flex justify-content-between align-items-center p-4">
            <p class="mb-0">Vēlies galerijā pievienot savu bildi?</p>
            <button id="addToGalleryBtn" class="btn btn-main d-flex align-items-center">
                <i class="fas fa-plus mr-2"></i> Pievienot
            </button>
        </div>
    </div>

   <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Tu neesi ielogojies</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Aizvērt">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p>Vai ielogoties?</p>
      </div>

      <div class="modal-footer">
        <a href="<?= $basePath ?>login.php" class="btn btn-primary">Ielogoties</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aizvērt</button>
      </div>

    </div>
  </div>
</div>

    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Pievienot Bildi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="upload_image.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="imageInput">Izvēlies bildi:</label>
                            <input type="file" class="form-control-file" id="imageInput" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Aizvērt</button>
                        <button type="submit" class="btn btn-main">Pievienot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php include 'files/messages.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php include 'files/footer.php'; ?>

    <script>
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name = button.data('name');
            var imageSrc = button.find('img').attr('src');

            var modal = $(this);
            modal.find('#modalImage').attr('src', imageSrc);
            modal.find('#modalProduct').text(name);
        });

    </script>
</body>
</html>