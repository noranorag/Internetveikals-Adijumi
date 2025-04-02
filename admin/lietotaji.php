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
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="heading-container">
            <h1>Lietotāji</h1>
        </div>
        <div class="table-container">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="input-group" style="width: 300px;"> 
                    <input type="text" class="form-control" placeholder="Meklēt lietotāju..." style="margin-right: 10px;"> 
                    <div class="input-group-append">
                        <button class="btn btn-third" type="button">Meklēt</button>
                    </div>
                </div>
                <button class="btn btn-third" data-toggle="modal" data-target="#addUserModal">Pievienot lietotāju</button>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>Tālrunis</th>
                        <th>E-pasts</th>
                        <th>Loma</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Jānis</td>
                        <td>Bērziņš</td>
                        <td>+371 12345678</td>
                        <td>janis.berzins@example.com</td>
                        <td>Administrators</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> 
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Anna</td>
                        <td>Kalniņa</td>
                        <td>+371 87654321</td>
                        <td>anna.kalnina@example.com</td>
                        <td>Lietotājs</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Pēteris</td>
                        <td>Ozols</td>
                        <td>+371 23456789</td>
                        <td>peteris.ozols@example.com</td>
                        <td>Moderators</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Iepriekšējā</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Nākamā</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
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
                    <form>
                        <div class="form-group">
                            <label for="userFirstName">Vārds</label>
                            <input type="text" class="form-control" id="userFirstName" placeholder="Ievadiet vārdu">
                        </div>
                        <div class="form-group">
                            <label for="userLastName">Uzvārds</label>
                            <input type="text" class="form-control" id="userLastName" placeholder="Ievadiet uzvārdu">
                        </div>
                        <div class="form-group">
                            <label for="userPhone">Tālrunis</label>
                            <input type="text" class="form-control" id="userPhone" placeholder="Ievadiet tālruņa numuru">
                        </div>
                        <div class="form-group">
                            <label for="userEmail">E-pasts</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="Ievadiet e-pastu">
                        </div>
                        <div class="form-group">
                            <label for="userPassword">Parole</label>
                            <input type="password" class="form-control" id="userPassword" placeholder="Ievadiet paroli">
                        </div>
                        <div class="form-group">
                            <label for="userRole">Loma</label>
                            <select class="form-control" id="userRole">
                                <option value="administrators">Administrators</option>
                                <option value="moderators">Moderators</option>
                                <option value="lietotajs">Lietotājs</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-main">Saglabāt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>