<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internetveikals</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Announcement Bar -->
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="heading-container">
            <h1 class="mb-4">Internetveikals</h1>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-secondary" onclick="toggleFilterModal()">Filtrēt</button>
            <div class="d-flex">
                <input type="text" class="form-control mr-2" placeholder="Meklēt produktus">
                <button class="btn btn-secondary">Meklēt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 1', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Sarkans', 'M', 'Kokvilna', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 2', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Zils', 'L', 'Vilna', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 3', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Zaļš', 'S', 'Lins', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 4', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Dzeltens', 'XL', 'Poliesters', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 4', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Dzeltens', 'XL', 'Poliesters', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 4', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Dzeltens', 'XL', 'Poliesters', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 4', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Dzeltens', 'XL', 'Poliesters', 'Mazgāt ar rokām.')">Apskatīt</button>
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
                        <button class="btn btn-primary" data-toggle="modal" data-target="#productModal" onclick="showProductDetails('Produkts 4', 'Īss apraksts par produktu.', 'Garāks apraksts par produktu.', 'Dzeltens', 'XL', 'Poliesters', 'Mazgāt ar rokām.')">Apskatīt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="images/berniem.png" class="img-fluid" alt="Product Image">
                        </div>
                        <div class="col-md-6">
                            <h5 id="productName">Product Name</h5>
                            <p id="productShortDescription">Short description</p>
                            <p id="productLongDescription">Long description</p>
                            <p><strong>Krāsa:</strong> <span id="productColor">Color</span></p>
                            <p><strong>Izmērs:</strong> <span id="productSize">Size</span></p>
                            <p><strong>Materiāls:</strong> <span id="productMaterial">Material</span></p>
                            <p><strong>Kopšana:</strong> <span id="productCare">Care instructions</span></p>
                        </div>
                    </div>
                    <h5 class="mt-4">Līdzīgi produkti</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card mb-4 text-center">
                                <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Produkts 5</h5>
                                    <p class="card-text">Īss apraksts par produktu.</p>
                                    <p class="card-text"><strong>€40.00</strong></p>
                                    <a href="#" class="btn btn-primary">Apskatīt</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4 text-center">
                                <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Produkts 6</h5>
                                    <p class="card-text">Īss apraksts par produktu.</p>
                                    <p class="card-text"><strong>€45.00</strong></p>
                                    <a href="#" class="btn btn-primary">Apskatīt</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4 text-center">
                                <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Produkts 7</h5>
                                    <p class="card-text">Īss apraksts par produktu.</p>
                                    <p class="card-text"><strong>€50.00</strong></p>
                                    <a href="#" class="btn btn-primary">Apskatīt</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mb-4 text-center">
                                <img src="images/berniem.png" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">Produkts 8</h5>
                                    <p class="card-text">Īss apraksts par produktu.</p>
                                    <p class="card-text"><strong>€55.00</strong></p>
                                    <a href="#" class="btn btn-primary">Apskatīt</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Aizvērt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="filter-modal" id="filterModal">
        <div class="filter-modal-header d-flex justify-content-between align-items-center">
            <h5>Filtrēt</h5>
            <button type="button" class="close" onclick="toggleFilterModal()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="filter-modal-body">
            <form>
                <div class="form-group">
                    <label for="filterColor">Krāsa</label>
                    <select class="form-control" id="filterColor">
                        <option>Sarkans</option>
                        <option>Zils</option>
                        <option>Zaļš</option>
                        <option>Dzeltens</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filterSize">Izmērs</label>
                    <select class="form-control" id="filterSize">
                        <option>S</option>
                        <option>M</option>
                        <option>L</option>
                        <option>XL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filterPrice">Cena</label>
                    <input type="range" class="form-control-range" id="filterPrice" min="8" max="70" oninput="updatePriceValue(this.value)">
                    <span id="priceValue">39</span>
                </div>
                <button type="submit" class="btn btn-primary">Piemērot</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
    <script>
        function showProductDetails(name, shortDescription, longDescription, color, size, material, care) {
            document.getElementById('productModalLabel').innerText = name;
            document.getElementById('productName').innerText = name;
            document.getElementById('productShortDescription').innerText = shortDescription;
            document.getElementById('productLongDescription').innerText = longDescription;
            document.getElementById('productColor').innerText = color;
            document.getElementById('productSize').innerText = size;
            document.getElementById('productMaterial').innerText = material;
            document.getElementById('productCare').innerText = care;
        }
        
        function updatePriceValue(value) {
            document.getElementById('priceValue').innerText = value + '€';
        }

        
    </script>
</body>
</html>