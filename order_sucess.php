<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pasūtījums veiksmīgs</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="styles.css"> 
  <script src="scripts.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?>

    <div class="container mt-5 pt-1">
        <div class="checkout-container">
            <div class="step-header">
                <div class="step active">
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="step-title">Pasūtījums veiksmīgs</div>
                </div>
            </div>
            <div class="line-container mt-2">
                <div class="line-highlight"></div>
            </div>

            <div id="order-success" class="form-section text-center">
                <h3 class="mb-4">Paldies par jūsu pasūtījumu!</h3>
                <p>Jūsu pasūtījums ir veiksmīgi apstiprināts un tiks apstrādāts tik līdz tiks pārskaitīta nauda uz norādīto kontu.</p>
                <p>Visa sūtījuma informācija redzama rēķinā.</p>
                <div class="mt-4">
                    <a href="generate_invoice.php?order_id=<?php echo $orderId; ?>" class="btn btn-main" style="width: 200px;">Apskatīt rēķinu</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>