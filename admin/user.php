<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id is not set.");
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php'); // Redirect to the main page
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
</head>
<body id="userPage">
    <div id="alertContainer" class="container mt-3"></div>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Lietotāji</h1>
        </div>
        <div class="table-container">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="input-group" style="width: 300px;"> 
                <input type="text" class="form-control" id="userSearchInput" placeholder="Meklēt lietotāju..." style="margin-right: 10px;"> 
                <div class="input-group-append">
                    <button class="btn btn-third" type="button">Meklēt</button>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <select class="form-control mr-2" id="roleFilter" style="width: 200px;">
                    <option value="">Filtrēt pēc lomas...</option>
                    <option value="user">Lietotāji</option>
                    <option value="moder">Moderatori</option>
                    <option value="admin">Administrātori</option>
                </select>
                <button class="btn btn-third" data-toggle="modal" data-target="#addUserModal">Pievienot lietotāju</button>
            </div>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Adrese</th>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>Tālrunis</th>
                        <th>E-pasts</th>
                        <th>Loma</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                </tbody>
            </table>
            </div>
            <div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                    
                    </ul>
                </nav>
            </div>

    <div class="modal" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Pievienot Lietotāju</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">
                    <div class="form-group d-flex justify-content-between">
                        <small class="text-muted" id="userCreatedAtText" style="display: none;">Izveidots: </small>
                        <small class="text-muted" id="userEditedAtText" style="display: none;">Pēdējo reizi rediģēts: </small>
                    </div>
                    <div class="form-group">
                        <label for="userFirstName">Vārds</label>
                        <input type="text" class="form-control" id="userFirstName" name="name" placeholder="Ievadiet vārdu" required>
                    </div>
                    <div class="form-group">
                        <label for="userLastName">Uzvārds</label>
                        <input type="text" class="form-control" id="userLastName" name="surname" placeholder="Ievadiet uzvārdu" required>
                    </div>
                    <div class="form-group">
                        <label for="userPhone">Tālrunis</label>
                        <input type="text" class="form-control" id="userPhone" name="phone" placeholder="Ievadiet tālruņa numuru" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">E-pasts</label>
                        <input type="email" class="form-control" id="userEmail" name="email" placeholder="Ievadiet e-pastu" required>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Parole</label>
                        <input type="password" class="form-control" id="userPassword" name="password" placeholder="Ievadiet paroli">
                    </div>
                    <div class="form-group">
                        <label for="userRole">Loma</label>
                        <select class="form-control" id="userRole" name="role" required>
                            <option value="admin">Administrators</option>
                            <option value="moder">Moderators</option>
                            <option value="user">Lietotājs</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-main">Saglabāt</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Adrese</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="addressDetails">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aizvērt</button>
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
                Vai tiešām vēlaties dzēst šo lietotāju?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Dzēst</button>
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