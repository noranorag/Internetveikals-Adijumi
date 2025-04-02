<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internetveikals</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-4">Internetveikals</h1>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-third" onclick="toggleFilterModal()">Filtrēt</button>
            <div class="d-flex">
                <input type="text" class="form-control mr-2" placeholder="Meklēt produktus">
                <button class="btn btn-third">Meklēt</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 1</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€20.00</strong></p>
                        <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 2</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€25.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 3</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€30.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 text-center">
                    <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title">Produkts 4</h5>
                        <p class="card-text">Īss apraksts par produktu.</p>
                        <p class="card-text"><strong>€35.00</strong></p>
                        <button class="btn btn-primary">Apskatīt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-modal" id="filterModal">
    <div class="filter-modal-header d-flex justify-content-between align-items-center">
        <h5>Filtrēšana</h5>
        <button type="button" class="close" onclick="toggleFilterModal()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="filter-modal-body">
        <div class="filter">
            <h5>Produkta kategorija</h5>
                <div class="form-group">
                    <div class="category-heading" onclick="toggleSubcategories('berniemSubcategories')">
                        Bērniem <span class="arrow">&#11166;</span> 
                    </div>
                    <div id="berniemSubcategories" class="subcategories">
                        <label>Kombinzoni</label>
                        <label>Zeķītes</label>
                        <label>Kurpītes</label>
                        <label>Bodiji</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="category-heading" onclick="toggleSubcategories('sievietemSubcategories')">
                        Sievietēm <span class="arrow">&#11166;</span> 
                    </div>
                    <div id="sievietemSubcategories" class="subcategories">
                        <label>Cepures</label>
                        <label>Zeķes</label>
                        <label>Mauči</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="category-heading" onclick="toggleSubcategories('viriesiemSubcategories')">
                        Vīriešiem <span class="arrow">&#11166;</span>
                    </div>
                    <div id="viriesiemSubcategories" class="subcategories">
                        <label>Cepures</label>
                        <label>Zeķes</label>
                        <label>Mauči</label>
                    </div>
                </div>
                <form>
            </div>
            <div class="filter">
            <h5>Produkta krāsa</h5>
                <label for="filterColor">Krāsa</label>
                <select class="form-control" id="filterColor">
                    <option>-- izvēlies krāsu --</option>
                    <option>Sarkans</option>
                    <option>Zils</option>
                    <option>Zaļš</option>
                    <option>Dzeltens</option>
                </select>
            </div>
            <div class="filter">
            <h5>Produkta izmērs</h5>
                <label for="filterSize">Izmērs</label>
                <select class="form-control" id="filterSize">
                    <option>-- izvēlies izmēru --</option>
                    <option>S</option>
                    <option>M</option>
                    <option>L</option>
                    <option>XL</option>
                </select>
            </div>
            <div class="filter">
            <h5>Produkta materiāls</h5>
                <label for="material">Materiāls</label>
                <select class="form-control" id="material">
                    <option>-- izvēlies materiālu --</option>
                    <option>50% polijesters 50% vilna</option>
                    <option>50% polijesters 50% vilna</option>
                    <option>50% polijesters 50% vilna</option>
                    <option>50% polijesters 50% vilna</option>
                </select>
            </div>
            <div class="form-group">
            <h5>Produkta cena</h5>
                    <label for="filterPrice">Cena</label>
                    <div id="priceRange"></div>
                    <span id="priceValueMin">8€</span> - <span id="priceValueMax">70€</span>
                </div>
        </form>
    </div>
</div>

<?php include 'files/footer.php'; ?>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>

    <script src="scripts.js"></script>
</body>
</html>