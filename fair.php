<?php
require 'database/db_connection.php';

$currentDate = date('Y-m-d');
$updateStatusQuery = "
    UPDATE fair
    SET status = 'late'
    WHERE status = 'upcoming' AND DATE_ADD(date, INTERVAL 1 DAY) <= '$currentDate'
";
mysqli_query($conn, $updateStatusQuery);

$query = "
    SELECT 
        fair_ID AS id,
        name,
        description,
        image,
        link,
        status,
        date
    FROM fair
    WHERE active = 'active'
    ORDER BY fair_ID DESC
";
$result = mysqli_query($conn, $query);

$upcomingFairs = [];
$lateFairs = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fair = [
            'id' => htmlspecialchars($row['id']),
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'image' => htmlspecialchars($row['image']),
            'link' => htmlspecialchars($row['link']),
            'status' => htmlspecialchars($row['status']),
            'date' => htmlspecialchars($row['date']), 
        ];
        if ($fair['status'] === 'upcoming') {
            $upcomingFairs[] = $fair;
        } elseif ($fair['status'] === 'late') {
            $lateFairs[] = $fair;
        }
    }
}

// Number of late fairs per page
$lateFairsPerPage = 8;

// Get the current page for late fairs from URL (default to 1)
$latePage = isset($_GET['late_page']) && is_numeric($_GET['late_page']) ? (int)$_GET['late_page'] : 1;

// Calculate the offset for SQL LIMIT clause
$lateOffset = ($latePage - 1) * $lateFairsPerPage;

// Query to fetch late fairs with pagination
$lateFairsQuery = "
    SELECT 
        fair_ID AS id,
        name,
        description,
        image,
        link,
        status,
        date
    FROM fair
    WHERE active = 'active' AND status = 'late'
    ORDER BY fair_ID DESC
    LIMIT $lateFairsPerPage OFFSET $lateOffset
";

$lateFairsResult = mysqli_query($conn, $lateFairsQuery);

// Fetch late fairs
$lateFairs = [];
if ($lateFairsResult) {
    while ($row = mysqli_fetch_assoc($lateFairsResult)) {
        $lateFairs[] = [
            'id' => htmlspecialchars($row['id']),
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'image' => htmlspecialchars($row['image']),
            'link' => htmlspecialchars($row['link']),
            'status' => htmlspecialchars($row['status']),
            'date' => htmlspecialchars($row['date']), 
        ];
    }
}

// Get the total number of late fairs for pagination
$totalLateFairsQuery = "
    SELECT COUNT(*) AS total
    FROM fair
    WHERE active = 'active' AND status = 'late'
";
$totalLateFairsResult = mysqli_query($conn, $totalLateFairsQuery);
$totalLateFairs = 0;
if ($totalLateFairsResult) {
    $row = mysqli_fetch_assoc($totalLateFairsResult);
    $totalLateFairs = (int)$row['total'];
}

// Calculate total pages for late fairs
$totalLatePages = ceil($totalLateFairs / $lateFairsPerPage);
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tirdziņi</title>
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
            <h1 class="mb-2">Tirdziņi</h1>
        </div>

        <!-- Upcoming Fairs -->
        <?php if (!empty($upcomingFairs)): ?>
            <div class="row">
                <?php foreach ($upcomingFairs as $fair): ?>
                    <div class="col-md-6 mb-2">
                        <div class="market-item" data-toggle="modal" data-target="#imageModal" data-src="<?= $fair['image'] ?>">
                            <img src="<?= $fair['image'] ?>" alt="<?= $fair['name'] ?>" class="market-image">
                            <div>
                                <h3><?= $fair['name'] ?></h3>
                                <p class="market-details">
                                    <span class="market-date">Norisinās: <?= htmlspecialchars($fair['date']) ?></span>
                                    <?= $fair['description'] ?>
                                </p>
                                <p class="market-link">
                                    Apskati: <a href="<?= $fair['link'] ?>" target="_blank"><?= $fair['name'] ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Šobrīd nav tuvāko tirdziņu.</p>
        <?php endif; ?>

        <!-- Late Fairs -->
        <?php if (!empty($lateFairs)): ?>
            <div class="mt-5">
                <p class="page-heading text-left">Bijušie tirdziņi</p>
            </div>
            <div class="row">
                <?php foreach ($lateFairs as $fair): ?>
                    <div class="col-md-6 mb-2">
                        <div class="market-item dimmed" data-toggle="modal" data-target="#imageModal" data-src="<?= $fair['image'] ?>">
                            <img src="<?= $fair['image'] ?>" alt="<?= $fair['name'] ?>" class="market-image">
                            <div>
                                <h3><?= $fair['name'] ?></h3>
                                <p class="market-details">
                                    <span class="market-date">Norisinājās: <?= htmlspecialchars($fair['date']) ?></span>
                                    <?= $fair['description'] ?>
                                </p>
                                <p class="market-link">
                                    Apskati: <a href="<?= $fair['link'] ?>" target="_blank"><?= $fair['name'] ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Šobrīd nav bijušo tirdziņu.</p>
        <?php endif; ?>

            <!-- Pagination for late fairs -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-4">
                    <!-- Previous Page Link -->
                    <li class="page-item <?= ($latePage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?late_page=<?= $latePage - 1 ?>" tabindex="-1">&laquo;</a>
                    </li>

                    <!-- Page Number Links -->
                    <?php for ($i = 1; $i <= $totalLatePages; $i++): ?>
                        <li class="page-item <?= ($latePage == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?late_page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page Link -->
                    <li class="page-item <?= ($latePage >= $totalLatePages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?late_page=<?= $latePage + 1 ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Tirgus plakāts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Aizvērt">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="Market Image">
                </div>
            </div>
        </div>
    </div>
    <?php include 'files/messages.php'; ?>
    <?php include 'files/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var imageSrc = button.data('src');
            $('#modalImage').attr('src', imageSrc);
        });
    </script>
</body>
</html>