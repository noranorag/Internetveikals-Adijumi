<?php
session_start(); 
include '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id is not set.");
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="../scripts.js" defer></script>
</head>
<body id="productPage">
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Preces</h1>
        </div>
        <div class="btn-group btn-group-lg d-flex" role="group" aria-label="Navigation Buttons">
            <button type="button" class="btn flex-fill" id="productsButton" onclick="window.location.href='product.php'">Produkti</button>
            <button type="button" class="btn flex-fill" id="categoriesButton" onclick="window.location.href='category.php'">Kategorijas</button>
            <button type="button" class="btn flex-fill" id="setsButton" onclick="window.location.href='sets.php'">Komplekti</button>
        </div>
        <div class="table-container">
        <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Search Bar on the Left -->
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Meklēt preci...">
                <div class="input-group-append">
                    <button class="btn btn-third" type="button">Meklēt</button>
                </div>
            </div>

            <!-- Filters and Add Button on the Right -->
            <div class="d-flex align-items-center" style="gap: 15px;">
                <select class="form-control" id="filterCategory" style="width: 200px;">
                    <option value="">Visas kategorijas</option>
                    <!-- Populate categories dynamically -->
                </select>
                <select class="form-control" id="sortOptions" style="width: 200px;">
                    <option value="">Sakārtot pēc...</option>
                    <option value="quantity_asc">Daudzums (↑)</option>
                    <option value="quantity_desc">Daudzums (↓)</option>
                    <option value="date_asc">Datums (↑)</option>
                    <option value="date_desc">Datums (↓)</option>
                    <option value="price_asc">Cena (↑)</option>
                    <option value="price_desc">Cena (↓)</option>
                </select>
                <button class="btn btn-third" data-toggle="modal" data-target="#addProductModal">Pievienot preci</button>
            </div>
        </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Preces ID</th>
                            <th>Nosaukums</th>
                            <th>Kategorija</th>
                            <th>Apraksts</th>
                            <th>Cena</th>
                            <th>Daudzums</th>
                            <th>Darbības</th>
                        </tr>
                    </thead>
                    <tbody id="product"></tbody>
                </table>
            </div>
            <div>
            <nav aria-label="Product page navigation">
                <ul class="pagination justify-content-center product-pagination">
                </ul>
            </nav>

    <div class="modal" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Pievienot preci</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="productForm">
                    <input type="hidden" id="productId" name="id">
                    <div class="form-group">
                        <label for="productName">Nosaukums</label>
                        <input type="text" class="form-control" id="productName" name="name" placeholder="Ievadiet nosaukumu">
                    </div>
                    <div class="form-group">
                        <label for="shortDescription">Īss Apraksts</label>
                        <textarea class="form-control" id="shortDescription" name="short_description" rows="2" placeholder="Ievadiet īsu aprakstu"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="longDescription">Garš Apraksts</label>
                        <textarea class="form-control" id="longDescription" name="long_description" rows="4" placeholder="Ievadiet garu aprakstu"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="material">Materiāls</label>
                        <input type="text" class="form-control" id="material" name="material" placeholder="Ievadiet materiālu">
                    </div>
                    <div class="form-group">
                        <label for="size">Izmērs</label>
                        <input type="text" class="form-control" id="size" name="size" placeholder="Ievadiet izmēru">
                    </div>
                    <div class="form-group">
                        <label for="color">Krāsa</label>
                        <input type="text" class="form-control" id="color" name="color" placeholder="Ievadiet krāsu">
                    </div>
                    <div class="form-group">
                        <label for="care">Rūpēšanās</label>
                        <textarea class="form-control" id="care" name="care" rows="2" placeholder="Ievadiet rūpēšanās instrukcijas"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Cena</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Ievadiet cenu" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Preces Daudzums</label>
                        <input type="number" class="form-control" id="quantity" name="stock_quantity" placeholder="Ievadiet daudzumu">
                    </div>
                    <div class="form-group">
                    <label for="category">Kategorija</label>
                    <select class="form-control" id="category" name="category_id">
                        <!-- Options will be dynamically populated here -->
                    </select>
                </div>
                    <div class="form-group">
                        <label for="image">Attēls</label>
                        <div id="imagePreviewContainer">
                            <img id="imagePreview" src="" alt="Pašreizējais attēls" style="max-width: 100%; height: auto; display: none;">
                        </div>
                        <input type="file" style="margin-top: 5px;" class="form-control" id="image" name="image" accept="image/*">
                        <input type="hidden" id="imagePath" name="current_image">
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
                Vai tiešām vēlies dzēst šo preci?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Dzēst</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrēt Preces</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add filter options here -->
                <form id="filterForm">
                    <div class="form-group">
                        <label for="filterCategory">Kategorija</label>
                        <select class="form-control" id="filterCategory">
                            <option value="">Visas kategorijas</option>
                            <!-- Populate categories dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterPrice">Cena (līdz)</label>
                        <input type="number" class="form-control" id="filterPrice" placeholder="Ievadiet maksimālo cenu">
                    </div>
                    <button type="submit" class="btn btn-main">Filtrēt</button>
                </form>
            </div>
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