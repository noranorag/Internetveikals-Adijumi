<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit();
}

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moder'])) {
    header('Location: ../index.php'); // Redirect to the main page
    exit();
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerija</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body id="galleryPage">
    <div id="alertContainer" class="container mt-3"></div>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Galerija</h1>
        </div>
        <div class="table-container">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div></div>
                    <div class="d-flex align-items-center" style="gap: 15px;">
                    <select class="form-control" id="statusFilter" style="width: 200px;">
                        <option value="">Filtrēt pēc statusa...</option>
                        <option value="approved">Apstiprināts</option>
                        <option value="onhold">Gaida apstiprinājumu</option>
                    </select>
                    <button class="btn btn-third" data-toggle="modal" data-target="#addImageModal">Pievienot bildi</button>
                </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bilde</th>
                            <th>Lietotājs</th>
                            <th>Atsauksme</th>
                            <th>Statuss</th>
                            <th>Darbības</th>
                        </tr>
                    </thead>
                    <tbody id="gallery"></tbody>
                    
                </table>
            </div>
            <nav aria-label="Gallery page navigation">
                <ul class="pagination justify-content-center gallery-pagination">
                </ul>
            </nav>


        

        <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addImageModalLabel">Pievienot bildi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addImageForm" enctype="multipart/form-data" method="POST" action="../user-database/upload_image.php">
                            <div class="form-group">
                                <label for="imageFile">Bilde</label>
                                <input type="file" class="form-control-file" id="imageFile" name="image" required>
                            </div>
                            <div class="form-group">
                                <label for="review">Atsauksme</label>
                                <textarea class="form-control" id="review" name="review" rows="3" placeholder="Ierakstiet atsauksmi par šo bildi..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-main">Pievienot</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imagePreviewModalLabel">Attēla priekšskatījums</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Preview" style="max-width: 400px; height: auto;">
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script-functions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>