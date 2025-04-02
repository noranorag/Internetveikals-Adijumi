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
                    <h2>Sveicināti admin panelī, vārds</h2>
                </div>
                <div class="order-box">
                    <h2>5</h2>
                    <p>Nepieņemtie pasūtījumi</p>
                </div>
                <div class="order-box">
                    <h2>7</h2>
                    <p>Neapskatītas galerijas bildes</p>
                </div>
                <div class="order-box">
                    <h2>2</h2>
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
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>2025-03-24</td>
                            <td>Pabeigts</td>
                            <td>$100.00</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>John Doe</td>
                            <td>2025-03-24</td>
                            <td>Pabeigts</td>
                            <td>$100.00</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>John Doe</td>
                            <td>2025-03-24</td>
                            <td>Pabeigts</td>
                            <td>$100.00</td>
                        </tr>
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
    const ctx1 = document.getElementById('chart1').getContext('2d');
    const chart1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs'],
            datasets: [{
                label: 'Pārdošana',
                data: [12, 19, 3, 5, 2, 3],
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
                data: [15, 10, 5, 2, 20, 30],
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