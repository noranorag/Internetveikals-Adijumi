<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reģistrācija</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="outer-container">
        <div class="container login-container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Reģistrācija</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_GET['error'])): ?>
                                <p id="errorMessage" class="text-danger text-center">
                                    <?php echo htmlspecialchars($_GET['error']); ?>
                                </p>
                            <?php endif; ?>

                            <form action="database/register_process.php" method="POST">
                                <div class="form-group">
                                    <label for="first_name">Vārds</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" maxlength="50" placeholder="Vārds" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Uzvārds</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" maxlength="50" placeholder="Uzvārds" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-pasta adrese</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="255" placeholder="E-pasta adrese" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Tālrunis</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" maxlength="12" placeholder="Tālrunis" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Parole</label>
                                    <input type="password" class="form-control" id="password" name="password" maxlength="255" placeholder="Parole" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Reģistrēties</button>
                                <a href="login.php" class="btn btn-outline-main btn-block mt-2">Ielogoties</a>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <a href="index.php">Atgriezties uz sākumlapu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>