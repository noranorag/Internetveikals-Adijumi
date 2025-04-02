<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkta detaļas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="announcement" id="announcement"></div>
    <?php include 'files/navbar.php'; ?>

    <div class="container" style="margin-top: 100px;"> 
        <div class="row">
            <div class="col-md-6">
                <img src="images/berniem.png" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
            <h1>Produkts 1</h1>
            <p class="text-muted">Īss apraksts par produktu.</p>
            <div class="d-flex justify-content-between align-items-center">
                <p class="h4 price-text">€20.00</p>
                <p class="text-muted stock-text">Atlikušas 3 preces</p>
            </div>
                <hr style="border-top: 1px solid #ccc;">
                <p class="no-margin"><strong>Apraksts:</strong></p>
                <p class="little-margin">Šis ir garāks apraksts par produktu, kurā tiek detalizēti aprakstītas tā īpašības un priekšrocības.</p>
                <ul class="list-unstyled">
                    <p class="no-margin"><strong>Krāsa:</strong></p>
                    <p class="little-margin">zila</p>
                    <p class="no-margin"><strong>Izmērs:</strong></p>
                    <p class="little-margin">3-6 mēneši</p>
                </ul>
                
                <div class="d-flex align-items-center">
                    <button class="btn quantity-btn" onclick="decreaseQuantity()">-</button>
                    <input type="text" id="quantity" class="form-control quantity-input text-center" value="1" readonly>
                    <button class="btn quantity-btn" onclick="increaseQuantity()">+</button>
                    <button class="btn btn-success ml-3">Ielikt grozā</button>
                    <button class="btn heart-btn ml-3" onclick="toggleHeart(this)">
                        <i class="far fa-heart"></i> 
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-5">
    <div class="col-md-12 text-center">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('material', 0)">Materiāls</h5>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('care', 1)">Rūpes</h5>
            </div>
            <div class="col-md-4 text-center">
                <h5 class="toggle-heading" onclick="showContent('details', 2)">Detaļas</h5>
            </div>
        </div>
        <div class="line-container mt-2">
            <div class="line"></div>
            <div class="line-highlight"></div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <p id="material" class="toggle-content">Šis produkts ir izgatavots no augstas kvalitātes kokvilnas.</p>
        <p id="care" class="toggle-content" style="display: none;">Mazgāt ar rokām 30°C temperatūrā, nebalināt.</p>
        <p id="details" class="toggle-content" style="display: none;">Produkta izmēri: 3-6 mēneši, krāsa: zila.</p>
    </div>
</div>
</div>

<div class="container mt-5">
    <h3 class="mb-4">Produkti, kas der komplektā ar šo</h3>
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
                    <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
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
                    <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
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
                    <button class="btn btn-primary" onclick="window.location.href='product-details.php'">Apskatīt</button>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>
    <script>
    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    }
</script>

<script>
    function toggleHeart(button) {
        const icon = button.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far'); 
            icon.classList.add('fas'); 
            button.classList.add('active');
        } else {
            icon.classList.remove('fas'); 
            icon.classList.add('far');
            button.classList.remove('active'); 
        }
    }
</script>

<script>
    function showContent(sectionId, index) {
        document.querySelectorAll('.toggle-content').forEach(content => {
            content.style.display = 'none';
        });

        document.getElementById(sectionId).style.display = 'block';

        const highlight = document.querySelector('.line-highlight');
        highlight.style.left = `${index * 33.33}%`;
    }
</script>


</body>
</html>

