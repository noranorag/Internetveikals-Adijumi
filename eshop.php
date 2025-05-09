<?php
include 'database/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM product WHERE active = 'active'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

$favorites = [];
if (isset($_SESSION['user_id'])) { 
    $userID = $_SESSION['user_id']; 
    $stmt = $conn->prepare("SELECT product_ID FROM favourites WHERE user_ID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row['product_ID'];
    }
}
?>

<?php
$query = "SELECT * FROM category WHERE active = 1 ORDER BY big_category, name";
$result = $conn->query($query);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['big_category']][] = [
            'name' => $row['name'],
            'category_ID' => $row['category_ID']
        ];
    }
}
?>

<?php
$bindParams = []; 
$types = ''; 

$whereClauses = ["p.active = 'active'"]; 

if (!empty($_GET['search'])) {
    $searchQuery = '%' . htmlspecialchars($_GET['search']) . '%'; 
    $whereClauses[] = "(p.name LIKE ? OR p.short_description LIKE ?)"; 
    $bindParams[] = $searchQuery;
    $bindParams[] = $searchQuery;
    $types .= 'ss'; 
}

if (!empty($_GET['big_category'])) {
    $selectedBigCategory = htmlspecialchars($_GET['big_category']);
    $whereClauses[] = "c.big_category = ?";
    $bindParams[] = $selectedBigCategory;
    $types .= 's'; 
} elseif (!empty($_GET['subcategory'])) {
    $selectedSubcategories = array_map('intval', $_GET['subcategory']); 
    $subcategoryPlaceholders = implode(',', array_fill(0, count($selectedSubcategories), '?'));
    $whereClauses[] = "c.category_ID IN ($subcategoryPlaceholders)";
    $bindParams = array_merge($bindParams, $selectedSubcategories);
    $types .= str_repeat('i', count($selectedSubcategories)); 
}

if (!empty($_GET['price_min']) && !empty($_GET['price_max'])) {
    $priceMin = (float)$_GET['price_min'];
    $priceMax = (float)$_GET['price_max'];
    $whereClauses[] = "p.price BETWEEN ? AND ?";
    $bindParams[] = $priceMin;
    $bindParams[] = $priceMax;
    $types .= 'dd'; 
}

$whereSQL = implode(' AND ', $whereClauses);
$query = "
    SELECT p.* 
    FROM product p
    INNER JOIN category c ON p.ID_category = c.category_ID
    WHERE $whereSQL
";

error_log("SQL Query: $query");
error_log("Bind Params: " . print_r($bindParams, true));
error_log("Types: $types");

$stmt = $conn->prepare($query);
if (!empty($bindParams)) {
    $stmt->bind_param($types, ...$bindParams);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}


?>

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
    <script src="scripts.js" defer></script>
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
                <form method="GET" action="eshop.php" class="d-flex">
                    <input type="text" class="form-control mr-2" name="search" placeholder="Meklēt produktus" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                    <?php if (!empty($_GET['big_category'])): ?>
                        <input type="hidden" name="big_category" value="<?= htmlspecialchars($_GET['big_category']) ?>">
                    <?php endif; ?>
                    <?php if (!empty($_GET['subcategory'])): ?>
                        <?php foreach ($_GET['subcategory'] as $subcategory): ?>
                            <input type="hidden" name="subcategory[]" value="<?= htmlspecialchars($subcategory) ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($_GET['price_min'])): ?>
                        <input type="hidden" name="price_min" value="<?= htmlspecialchars($_GET['price_min']) ?>">
                    <?php endif; ?>
                    <?php if (!empty($_GET['price_max'])): ?>
                        <input type="hidden" name="price_max" value="<?= htmlspecialchars($_GET['price_max']) ?>">
                    <?php endif; ?>

                    <button class="btn btn-third" type="submit">Meklēt</button>
                </form>
            </div>
        </div>

        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3">
                    <div class="card mb-4 text-center">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['short_description']) ?></p>
                            <p class="card-text"><strong>€<?= htmlspecialchars($product['price']) ?></strong></p>
                            <button class="btn btn-primary" onclick="window.location.href='product-details.php?product_ID=<?= $product['product_ID'] ?>'">Apskatīt</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="filter-modal" id="filterModal">
    <form method="GET" action="eshop.php">
        <div class="filter-modal-header d-flex justify-content-between align-items-center">
            <h5>Filtrēšana</h5>
            <button type="button" class="close" onclick="toggleFilterModal()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="filter-modal-body">
        <div class="filter">
            <h5>Produkta kategorija</h5>
            <?php foreach ($categories as $bigCategory => $subcategories): ?>
                <div class="form-group">
                    <div class="category-heading" onclick="toggleSubcategories('<?= strtolower($bigCategory) ?>Subcategories')">
                        <?= htmlspecialchars($bigCategory) ?> <span class="arrow">&#11166;</span>
                    </div>
                    <div id="<?= strtolower($bigCategory) ?>Subcategories" class="subcategories" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-0"><strong>Visi</strong></label>
                            <input type="checkbox" class="ml-2" onclick="selectBigCategory('<?= htmlspecialchars($bigCategory) ?>', this)"
                                <?= (!empty($_GET['big_category']) && $_GET['big_category'] === $bigCategory) ? 'checked' : '' ?>>
                        </div>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="mb-0"><?= htmlspecialchars($subcategory['name']) ?></label>
                                <input type="checkbox" name="subcategory[]" value="<?= htmlspecialchars($subcategory['category_ID']) ?>" class="ml-2 <?= strtolower($bigCategory) ?>Subcategories"
                                    <?= (!empty($_GET['subcategory']) && in_array($subcategory['category_ID'], $_GET['subcategory'])) ? 'checked' : '' ?>>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="big_category" id="bigCategoryInput" value="">
        </div>
        <div class="form-group">
            <h5>Produkta cena</h5>
            <label for="priceRange">Cena</label>
            <div id="priceRange" style="margin-top: 40px;"></div>
            <div class="d-flex justify-content-between mt-2">
                <span id="priceValueMin">8€</span>
                <span id="priceValueMax">70€</span>
            </div>
            <input type="hidden" name="price_min" id="priceMinInput" value="8">
            <input type="hidden" name="price_max" id="priceMaxInput" value="70">
        </div>
        <button class="btn btn-third mt-3" type="submit">Filtrēt</button>
        <button class="btn btn-secondary mt-3" type="button" onclick="resetFilters()">Atiestatīt filtrus</button>
    </div>
    </form>
</div>

<?php include 'files/footer.php'; ?>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const priceRange = document.getElementById('priceRange');
        const priceMinInput = document.getElementById('priceMinInput');
        const priceMaxInput = document.getElementById('priceMaxInput');
        const priceValueMin = document.getElementById('priceValueMin');
        const priceValueMax = document.getElementById('priceValueMax');

        const urlParams = new URLSearchParams(window.location.search);
        const priceMin = urlParams.get('price_min') || 8; 
        const priceMax = urlParams.get('price_max') || 70; 

        noUiSlider.create(priceRange, {
            start: [priceMin, priceMax], 
            connect: true, 
            range: {
                min: 8, 
                max: 70 
            },
            step: 1, 
            tooltips: [true, true], 
            format: {
                to: value => Math.round(value), 
                from: value => Number(value)
            }
        });

        priceRange.noUiSlider.on('update', (values) => {
            priceMinInput.value = values[0];
            priceMaxInput.value = values[1];
            priceValueMin.textContent = `${values[0]}€`;
            priceValueMax.textContent = `${values[1]}€`;
        });

        priceMinInput.value = priceMin;
        priceMaxInput.value = priceMax;
        priceValueMin.textContent = `${priceMin}€`;
        priceValueMax.textContent = `${priceMax}€`;
    });

    function selectBigCategory(bigCategory, checkbox) {
        const bigCategoryInput = document.getElementById('bigCategoryInput');
        const subcategoryCheckboxes = document.querySelectorAll(`.${bigCategory.toLowerCase()}Subcategories`);

        if (checkbox.checked) {
            bigCategoryInput.value = bigCategory; 
            subcategoryCheckboxes.forEach(subCheckbox => {
                subCheckbox.checked = false; 
            });
        } else {
            bigCategoryInput.value = '';  
        }
    }

    function resetFilters() {
        window.location.href = 'eshop.php';
    }
</script>
</body>
</html>