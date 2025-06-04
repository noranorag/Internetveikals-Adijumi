<?php
session_start(); 
include '../database/db_connection.php';

error_log("Session data: " . print_r($_SESSION, true));

if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id is not set.");
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit();
}

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'moder'])) {
    header('Location: ../index.php'); // Redirect to the main page
    exit();
}

$userName = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'; 

// Fetch counts from the database
$orderCount = 0;
$galleryCount = 0;
$fairCount = 0;

// Fetch data for charts
$salesData = [];
$revenueData = [];

try {
    // Count orders with status "Jauns"
    $orderQuery = $conn->prepare("SELECT COUNT(order_ID) AS count FROM orders WHERE status = 'Jauns'");
    $orderQuery->execute();
    $orderQuery->bind_result($orderCount);
    $orderQuery->fetch();
    $orderQuery->close();

    // Count gallery images with approved = "onhold"
    $galleryQuery = $conn->prepare("SELECT COUNT(gallery_ID) AS count FROM gallery_images WHERE approved = 'onhold'");
    $galleryQuery->execute();
    $galleryQuery->bind_result($galleryCount);
    $galleryQuery->fetch();
    $galleryQuery->close();

    // Count fairs with status = "upcoming" and active = "active"
    $fairQuery = $conn->prepare("SELECT COUNT(fair_ID) AS count FROM fair WHERE status = 'upcoming' AND active = 'active'");
    $fairQuery->execute();
    $fairQuery->bind_result($fairCount);
    $fairQuery->fetch();
    $fairQuery->close();

    // Fetch sales data for the last 6 months
    $salesQuery = $conn->prepare("
        SELECT MONTH(created_at) AS month, COUNT(order_ID) AS order_count 
        FROM orders 
        WHERE status = 'Nosūtīts' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY MONTH(created_at)
    ");
    $salesQuery->execute();
    $result = $salesQuery->get_result();
    while ($row = $result->fetch_assoc()) {
        $salesData[$row['month']] = $row['order_count'];
    }
    $salesQuery->close();

    // Fetch revenue data for the last 6 months
    $revenueQuery = $conn->prepare("
        SELECT MONTH(created_at) AS month, SUM(total_amount) AS revenue 
        FROM orders 
        WHERE status = 'Nosūtīts' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY MONTH(created_at)
    ");
    $revenueQuery->execute();
    $result = $revenueQuery->get_result();
    while ($row = $result->fetch_assoc()) {
        $revenueData[$row['month']] = $row['revenue'];
    }
    $revenueQuery->close();

    $recentOrdersQuery = $conn->prepare("
        SELECT order_ID, name, surname, created_at, status, total_amount 
        FROM orders 
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $recentOrdersQuery->execute();
    $result = $recentOrdersQuery->get_result();
    while ($row = $result->fetch_assoc()) {
        $recentOrders[] = [
            'order_ID' => htmlspecialchars($row['order_ID']),
            'name' => htmlspecialchars($row['name']),
            'surname' => htmlspecialchars($row['surname']),
            'created_at' => date('d/m/Y', strtotime($row['created_at'])),
            'status' => htmlspecialchars($row['status']),
            'total_amount' => htmlspecialchars($row['total_amount']),
        ];
    }
    $recentOrdersQuery->close();

} catch (mysqli_sql_exception $e) {
    error_log("Database query error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrācijas Panelis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="d-flex">
    <?php include 'navbar.php'; ?>
        <div class="content flex-grow-1">
            <div class="header">
                <div class="header-box">
                    <img src="../images/logo.png" alt="Logo" style="max-width: 100px; border-radius: 10px;">
                </div>
                <div class="header-box">
                    <h2>Sveicināti admin panelī, <?php echo htmlspecialchars($userName); ?></h2>
                </div>
                <div class="order-box">
                    <h2><?php echo $orderCount; ?></h2>
                    <p>Nepieņemtie pasūtījumi</p>
                </div>
                <div class="order-box">
                    <h2><?php echo $galleryCount; ?></h2>
                    <p>Neapskatītas galerijas bildes</p>
                </div>
                <div class="order-box">
                    <h2><?php echo $fairCount; ?></h2>
                    <p>Gaidāmie tirdziņi</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <div class="chart-box">
                    <canvas id="chart1"></canvas>
                </div>
                <div class="chart-box">
                    <canvas id="chart2"></canvas>
                </div>
            </div>
            <div class="table-container-index mt-4">
                <h3>Pēdējie Pasūtījumi</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pasūtījuma ID</th>
                            <th>Klients</th>
                            <th>Datums</th>
                            <th>Statuss</th>
                            <th>Kopā</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><?php echo $order['order_ID']; ?></td>
                                <td><?php echo $order['name'] . ' ' . $order['surname']; ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                                <td><?php echo $order['status']; ?></td>
                                <td><?php echo $order['total_amount']; ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    const salesData = <?php echo json_encode($salesData); ?>;
    const revenueData = <?php echo json_encode($revenueData); ?>;

    console.log("Sales Data:", salesData);
    console.log("Revenue Data:", revenueData);

    const ctx1 = document.getElementById('chart1').getContext('2d');
    const chart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs'],
            datasets: [{
                label: 'Pārdotie produkti',
                data: Object.values(salesData),
                backgroundColor: 'rgba(91, 103, 81, 0.2)', 
                borderColor: 'rgba(91, 103, 81, 1)', 
                borderWidth: 1,
                borderRadius: 10
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('chart2').getContext('2d');
    const chart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs'],
            datasets: [{
                label: 'Ieņēmumi',
                data: Object.values(revenueData),
                backgroundColor: 'rgba(91, 103, 81, 0.2)',
                borderColor: 'rgba(91, 103, 81, 1)', 
                borderWidth: 1,
                borderRadius: 10
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>