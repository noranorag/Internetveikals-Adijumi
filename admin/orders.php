<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php'); 
    exit();
}

include '../user-database/check_notpaid.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasūtījumi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body id="ordersPage">
    <div id="alertContainer" class="container mt-3"></div>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Pasūtījumi</h1>
        </div>
        <div class="table-container">
            <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="orderSearchInput" placeholder="Meklēt pasūtījumu..." style="margin-right: 10px;">
                    <div class="input-group-append">
                        <button class="btn btn-third" type="button">Meklēt</button>
                    </div>
                </div>
                <div>
                    <select class="form-control" id="statusFilter" style="width: 200px;">
                        <option value="">Visi statusi</option>
                        <option value="Jauns">Jauns</option>
                        <option value="Pieņemts">Pieņemts</option>
                        <option value="Nosūtīts">Nosūtīts</option>
                        <option value="Neapmaksāts">Neapmaksāts</option> 
                    </select>
                </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vārds</th>
                        <th>E-pasts</th>
                        <th>Telefons</th>
                        <th>Summa</th>
                        <th>Piegāde</th>
                        <th>Statuss</th>
                        <th>Datums</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody">
                </tbody>
            </table>
            </div>
        </div>
        <div>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                </ul>
            </nav>
        </div>
    </div>


    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel">Rediģēt Pasūtījumu</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="orderId">Pasūtījuma ID:</label>
                        <input type="text" class="form-control" id="orderId" readonly>
                    </div>
                    <div class="form-group">
                        <label for="customerName">Klienta Vārds:</label>
                        <input type="text" class="form-control" id="customerName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="customerEmail">E-pasts:</label>
                        <input type="text" class="form-control" id="customerEmail" readonly>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Telefons:</label>
                        <input type="text" class="form-control" id="customerPhone" readonly>
                    </div>
                    <div class="form-group">
                        <label for="deliveryMethod">Piegādes Veids:</label>
                        <input type="text" class="form-control" id="deliveryMethod" readonly>
                    </div>
                    <div class="form-group">
                        <label for="totalAmount">Kopējā Summa:</label>
                        <input type="text" class="form-control" id="totalAmount" readonly>
                    </div>
                    <div class="form-group">
                        <label for="shippingDetails">Piegādes Informācija:</label>
                        <textarea class="form-control" id="shippingDetails" rows="5" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label for="orderItems">Pasūtījuma Preces:</label>
                        <table class="table table-striped" id="orderItemsTable">
                            <thead>
                                <tr>
                                    <th>Produkta ID</th>
                                    <th>Nosaukums</th>
                                    <th>Daudzums</th>
                                    <th>Cena</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <label for="orderStatus">Statuss:</label>
                        <select class="form-control" id="orderStatus">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deliveryNumber">Piegādes Numurs:</label>
                        <input type="text" class="form-control" id="deliveryNumber" placeholder="Ievadiet piegādes numuru">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Aizvērt</button>
                    <button type="button" class="btn btn-primary" id="saveOrderChanges">Saglabāt Izmaiņas</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOrderModalLabel">Apstiprināt dzēšanu</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Vai tiešām vēlies dzēst šo pasūtījumu?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteOrder">Dzēst</button>
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