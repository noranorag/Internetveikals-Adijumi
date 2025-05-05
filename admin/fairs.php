<?php
session_start(); 
include '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id is not set.");
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
    <title>Preces</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
    <script src="script-functions.js" defer></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body id="fairPage">
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Tirdziņi</h1>
        </div>
        <div class="table-container">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="input-group" style="width: 300px;"> 
                <input type="text" class="form-control" id="fairSearchInput" placeholder="Meklēt tirdziņu..." style="margin-right: 10px;"> 
                <div class="input-group-append">
                    <button class="btn btn-third" type="button">Meklēt</button>
                </div>
            </div>
            <div class="d-flex align-items-center" style="gap: 15px;">
                <select class="form-control" id="FairStatusFilter" style="width: 200px;">
                            <option value="">Filtrēt pēc statusa...</option>
                            <option value="late">Bijis</option>
                            <option value="upcoming">Gaidāms</option>
                </select>
                <button class="btn btn-third" data-toggle="modal" data-target="#addMarketModal">Pievienot tirdziņu</button>
            </div>   
            </div>
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nosaukums</th>
                    <th>Apraksts</th>
                    <th>Links uz Tirdziņu</th>
                    <th>Statuss</th> <!-- Add this column -->
                    <th>Bilde</th>
                    <th>Darbības</th>
                </tr>
            </thead>
                <tbody id="fairTableBody"></tbody>
            </table>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center fair-pagination"></ul>
            </nav>


    <div class="modal fade" id="addMarketModal" tabindex="-1" aria-labelledby="addMarketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarketModalLabel">Pievienot Tirdziņu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="marketForm">
                    <input type="hidden" id="fairId" name="id" value="">
                    <div class="form-group d-flex justify-content-between">
                        <small class="text-muted" id="fairCreatedAtText" style="display: none;">Izveidots: </small>
                        <small class="text-muted" id="fairEditedAtText" style="display: none;">Pēdējo reizi rediģēts: </small>
                    </div>
                    <div class="form-group">
                        <label for="marketName">Nosaukums</label>
                        <input type="text" class="form-control" id="marketName" name="name" placeholder="Ievadiet tirdziņa nosaukumu" required>
                    </div>
                    <div class="form-group">
                        <label for="marketDescription">Apraksts</label>
                        <textarea class="form-control" id="marketDescription" name="description" rows="3" placeholder="Ievadiet tirdziņa aprakstu" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="marketImage">Bilde</label>
                        <div id="imagePreviewContainer">
                            <img id="imagePreview" src="" alt="Pašreizējais attēls" style="max-width: 100%; height: auto; display: none;">
                        </div>
                        <input type="file" class="form-control-file" id="marketImage" name="image" accept="image/*">
                        <input type="hidden" id="currentImagePath" name="current_image" value="">
                    </div>
                    <div class="form-group">
                        <label for="marketLink">Links uz Tirdziņu</label>
                        <input type="url" class="form-control" id="marketLink" name="link" placeholder="Ievadiet saiti uz tirdziņu" required>
                    </div>
                    <div class="form-group">
                        <label for="marketDate">Datums</label>
                        <input type="text" class="form-control" id="marketDate" name="date" placeholder="DD/MM/YYYY" required>
                    </div>
                    <button type="submit" class="btn btn-main">Saglabāt</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Apstiprināt dzēšanu</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Vai tiešām vēlies dzēst šo tirdziņu?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Dzēst</button>
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