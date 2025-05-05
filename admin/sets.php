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
    <title>Komplekti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body id="setsPage">
<div id="alertContainer" class="container mt-3"></div>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Komplekti</h1>
        </div>
        <div class="btn-group btn-group-lg d-flex" role="group">
            <button type="button" class="btn flex-fill" id="productsButton" onclick="window.location.href='product.php'">Produkti</button>
            <button type="button" class="btn flex-fill" id="categoriesButton" onclick="window.location.href='category.php'">Kategorijas</button>
            <button type="button" class="btn flex-fill" id="setsButton" onclick="window.location.href='sets.php'">Komplekti</button>
        </div>

        <div class="table-container">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Meklēt komplektu...">
                        <div class="input-group-append">
                            <button class="btn btn-third" onclick="searchSets()">Meklēt</button>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-third" data-toggle="modal" data-target="#addSetModal">Pievienot komplektu</button>
                    </div>
                </div>

                <div class="row" id="setsContainer">
                   
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSetTitle">Komplekta detaļas</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 id="modalSetName" class="bolder"></h3>
                        <h4 id="modalSetTitle" class="bolder"></h4>
                        <p id="modalSetDescription" class="gray"></p>
                    </div>
                </div>

                <div class="row" id="modalProductsContainer" style="margin-bottom: 30px;">
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h5 class="bolder">Pievienot produktus:</h5>
                    </div>
                </div>
                <div class="row" id="categoriesContainer" style="margin-top: 20px; flex-direction: column;">
                </div>
                <div id="${subCategoryId}" class="product-list mt-2" style="display: none;" data-subcategory="${category.name}">
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal" id="addSetModal" tabindex="-1" aria-labelledby="addSetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSetModalLabel">Pievienot komplektu</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="setForm">
                        <input type="hidden" id="setId" name="set_id">
                        <div class="form-group">
                            <label for="setName">Komplekta nosaukums</label>
                            <input type="text" class="form-control" id="setName" name="name" placeholder="Ievadiet komplekta nosaukumu" required>
                        </div>
                        <div class="form-group">
                            <label for="setDescription">Apraksts</label>
                            <textarea class="form-control" id="setDescription" name="description" rows="3" placeholder="Ievadiet komplekta aprakstu" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Saglabāt</button>
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
                    Vai tiešām vēlies dzēst šo komplektu?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Dzēst</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script-functions.js"></script>
</body>
</html>
