<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ielogošanās</title>
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
                            <h3>Ielogošanās</h3>
                        </div>
                        <div class="card-body">
                            <!-- Error message placeholder -->
                            <p id="errorMessage" class="text-danger text-center" style="display: none;"></p>

                            <form id="loginForm" action="database/login_process.php" method="POST">
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '') ?>">
                                <div class="form-group">
                                    <label for="email">E-pasta adrese</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Parole</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Ielogoties</button>
                                <a href="register.php" class="btn btn-outline-main btn-block mt-2">Reģistrēties</a>
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

    <script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('errorMessage');
            if (data.success) {
                // Redirect on success
                window.location.href = data.redirect;
            } else {
                // Show error message
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>