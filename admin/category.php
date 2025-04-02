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
            <h1>Kategorijas</h1>
        </div>
        <div class="table-container">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div></div>
                <button class="btn btn-third" id="addCategoryButton">Pievienot kategoriju</button>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lielā kategorija</th>
                        <th>Kategorija</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody id="category"></tbody>
                
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

            <div class="modal" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel"></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId" name="id"> <!-- Hidden input for category ID -->
                    <div class="form-group">
                        <label for="categoryName">Kategorijas nosaukums</label>
                        <input type="text" class="form-control" id="categoryName" name="name" placeholder="Ievadiet kategorijas nosaukumu" required>
                    </div>
                    <div class="form-group">
                        <label for="bigCategory">Lielā kategorija</label>
                        <select class="form-control" id="bigCategory" name="big_category" required>
                            <option value="" disabled selected>Izvēlieties lielo kategoriju</option>
                            <option value="Bērniem">Bērniem</option>
                            <option value="Sievietēm">Sievietēm</option>
                            <option value="Vīriešiem">Vīriešiem</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Saglabāt</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Apstiprināt dzēšanu</h5>
                        <!-- Add the "X" button -->
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Vai tiešām vēlies dzēst šo kategoriju?
                    </div>
                    <div class="modal-footer">
                        <!-- "Atcelt" button to close the modal -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atcelt</button>
                        <!-- "Dzēst" button to confirm deletion -->
                        <button type="button" class="btn btn-danger" id="confirmDelete">Dzēst</button>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script-functions.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>