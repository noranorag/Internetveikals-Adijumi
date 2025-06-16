<?php
// Start the session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include 'database/db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include 'user-database/check_reserved.php';

// Fetch active products
$query = "SELECT * FROM product WHERE active = 'active'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

// Fetch user favorites if logged in
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

// Fetch categories
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

// Handle filters
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

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$query = "
    SELECT p.* 
    FROM product p
    INNER JOIN category c ON p.ID_category = c.category_ID
    WHERE $whereSQL
    LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($query);

if (!empty($bindParams)) {
    $types .= 'ii';
    $bindParams[] = $limit;
    $bindParams[] = $offset;
    $stmt->bind_param($types, ...$bindParams);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$countQuery = "
    SELECT COUNT(*) AS total
    FROM product p
    INNER JOIN category c ON p.ID_category = c.category_ID
    WHERE $whereSQL
";
$countStmt = $conn->prepare($countQuery);
if (!empty($bindParams)) {
    $countParams = array_slice($bindParams, 0, -2);
    $countTypes = substr($types, 0, -2);
    $countStmt->bind_param($countTypes, ...$countParams);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalProducts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $limit);
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
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card text-center h-100 position-relative">
                        <?php if ($product['reserved'] == 1): ?>
                            <div class="reserved-label position-absolute text-white bg-primary px-2 py-1" style="top: 10px; left: 10px; z-index: 1; border-radius: 5px;">
                                Rezervēts
                            </div>
                        <?php elseif ($product['stock_quantity'] == 0): ?>
                            <div class="sold-out-label position-absolute text-white bg-danger px-2 py-1" style="top: 10px; left: 10px; z-index: 1; border-radius: 5px;">
                                Izpārdots
                            </div>
                        <?php endif; ?>
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

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Product pagination">
            <ul class="pagination justify-content-center mt-4">
                <?php
                    $queryParams = $_GET;
                    $queryParams['page'] = max(1, $page - 1);
                ?>
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query($queryParams) ?>" tabindex="-1">&laquo;</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                        $queryParams['page'] = $i;
                    ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query($queryParams) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php
                    $queryParams['page'] = min($totalPages, $page + 1);
                ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query($queryParams) ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

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

<?php include 'files/messages.php'; ?>

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