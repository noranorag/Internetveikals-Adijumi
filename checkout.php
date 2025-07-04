<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Piegādes lapa</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="styles.css"> 
  <script src="scripts.js" defer></script>
  <script src="checkoutScripts.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="announcement" id="announcement"></div>

    <?php include 'files/navbar.php'; ?> 

    
        <div class="checkout-container">
            <div class="step-header">
                <div class="step active" onclick="showSection('information', 0)">
                    <div class="step-icon">01</div>
                    <div class="step-title">Informācija</div>
                </div>
                <div class="step dimmed" onclick="showSection('shipping', 1)">
                    <div class="step-icon">02</div>
                    <div class="step-title">Piegāde</div>
                </div>
                <div class="step dimmed" onclick="showOrderSummary(); showSection('order', 2)">
                    <div class="step-icon">03</div>
                    <div class="step-title">Jūsu pasūtījums</div>
                </div>
            </div>
            <div class="line-container mt-2">
                <div class="line-highlight"></div>
            </div>
            <div class="container mt-3 pt-1">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

            <div id="information" class="form-section">
                
                <div id="information-errors" class="text-danger mb-3" style="display: none;"></div>
                <div class="form-group">
                    <label>Vārds *</label>
                    <input type="text" name="name" class="form-control" placeholder="Ievadiet savu vārdu" required maxlength="50">
                </div>
                <div class="form-group">
                    <label>Uzvārds *</label>
                    <input type="text" name="surname" class="form-control" placeholder="Ievadiet savu uzvārdu" required maxlength="50">
                </div>
                <div class="form-group">
                    <label>E-pasts *</label>
                    <input type="email" name="email" class="form-control" placeholder="Ievadiet savu e-pastu" required maxlength="255">
                </div>
                <div class="form-group">
                    <label>Tālrunis *</label>
                    <input type="tel" name="phone" class="form-control" placeholder="Ievadiet savu tālruņa numuru" required maxlength="12">
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-main mt-3" style="width: 200px;" onclick="showSection('shipping', 1)">Tālāk</button>
                </div>
            </div>

            <div id="shipping" class="form-section" style="display: none;">
                <div id="shipping-errors" class="text-danger mb-3" style="display: none;"></div>
                <div class="form-group">
                    <div class="delivery-option">
                        <label>
                            <img src="images/omniva.png" alt="Omniva pakomāts" style="width: 80px; margin-right: 10px;"> 
                            <input type="radio" name="delivery" value="omniva pakomāts" required onclick="handleDeliveryOption('omniva-pakomats')">
                            Piegāde ar Omniva pakomātu <span class="shipping-price text-muted">(€3.00)</span>
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="images/omniva.png" alt="Omniva kurjers" style="width: 80px; margin-right: 10px;">
                            <input type="radio" name="delivery" value="omniva kurjers" required onclick="handleDeliveryOption('omniva-kurjers')">
                            Piegāde ar Omniva kurjeru <span class="shipping-price text-muted">(€12.00)</span>
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="images/dpd.png" alt="DPD" style="width: 80px; margin-right: 10px;"> 
                            <input type="radio" name="delivery" value="dpd" required onclick="handleDeliveryOption('dpd')">
                            Piegāde ar DPD <span class="shipping-price text-muted">(€2.50)</span>
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="images/latvijaspasts.png" alt="Latvijas pasts" style="width: 80px; margin-right: 10px;"> 
                            <input type="radio" name="delivery" value="latvijas pasts" required onclick="handleDeliveryOption('pasts')">
                            Piegāde pa pastu <span class="shipping-price text-muted">(€4.00)</span>
                        </label>
                    </div>
                </div>

                <div id="omniva-dropdown" class="form-group" style="display: none;">
                    <label>Izvēlieties Omniva pakomātu *</label>
                    <select class="form-control" id="omniva-pakomati" name="pickup_address"></select>
                </div>

                <div id="dpd-dropdown" class="form-group" style="display: none;">
                    <label>Izvēlieties DPD pakomātu *</label>
                    <select class="form-control" id="dpd-pakomati" name="pickup_address"></select>
                </div>

                <div id="address-container" class="address-container" style="display: none;">
                    <div class="form-group">
                        <label>Valsts *</label>
                        <input type="text" name="country" class="form-control" placeholder="Ievadiet valsti" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Pilsēta *</label>
                        <input type="text" name="city" class="form-control" placeholder="Ievadiet pilsētu" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Iela *</label>
                        <input type="text" name="street" class="form-control" placeholder="Ievadiet ielu" required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label>Mājas numurs *</label>
                        <input type="text" name="house" class="form-control" placeholder="Ievadiet mājas numuru" required maxlength="30">
                    </div>
                    <div class="form-group">
                        <label>Dzīvokļa numurs</label>
                        <input type="text" name="apartment" class="form-control" placeholder="Ievadiet dzīvokļa numuru" maxlength="30">
                    </div>
                    <div class="form-group">
                        <label>Pasta indekss *</label>
                        <input type="text" name="postal_code" class="form-control" placeholder="Ievadiet pasta indeksu" required maxlength="7">
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-main mt-3" style="width: 200px;" onclick="showOrderSummary(); showSection('order', 2)">Tālāk</button>
                </div>
            </div>

            <div id="order" class="form-section" style="display: none;">
                <h3 class="mb-4">Jūsu pasūtījuma detaļas</h3>
                <div id="order-summary" class="row">
                    <div class="col-md-6">
                        <h5>Lietotāja informācija</h5>
                        <p><strong>Vārds:</strong> <span id="summary-name"></span></p>
                        <p><strong>Uzvārds:</strong> <span id="summary-surname"></span></p>
                        <p><strong>E-pasts:</strong> <span id="summary-email"></span></p>
                        <p><strong>Tālrunis:</strong> <span id="summary-phone"></span></p>

                        <h5 class="mt-4">Piegādes metode</h5>
                        <p><strong>Metode:</strong> <span id="summary-delivery-method"></span></p>

                        <div id="parcel-location-summary" style="display: none;">
                            <h5 class="mt-4">Pakomāta lokācija</h5>
                            <p><strong>Lokācija:</strong> <span id="summary-parcel-location"></span></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="address-summary">
                            <h5>Adrese</h5>
                            <p><strong>Valsts:</strong> <span id="summary-country"></span></p>
                            <p><strong>Pilsēta:</strong> <span id="summary-city"></span></p>
                            <p><strong>Iela:</strong> <span id="summary-street"></span></p>
                            <p><strong>Mājas numurs:</strong> <span id="summary-house"></span></p>
                            <p><strong>Dzīvokļa numurs:</strong> <span id="summary-apartment"></span></p>
                            <p><strong>Pasta indekss:</strong> <span id="summary-postal-code"></span></p>
                        </div>

                        <h5 class="mt-4">Preces</h5>
                        <div id="cart-items"></div>

                        <h5 class="mt-4">Kopsavilkums</h5>
                        <div class="d-flex justify-content-between">
                            <span>Preču kopsumma:</span>
                            <span id="summary-product-total">€0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Piegādes cena:</span>
                            <span id="summary-shipping-price">€0.00</span>
                        </div>
                        <div class="d-flex justify-content-between font-weight-bold border-top pt-2">
                            <span>Kopā:</span>
                            <span id="summary-total-cost">€0.00</span>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-main" style="width: 200px;" onclick="collectOrderData(event)">Pasūtīt</button>
                </div>
            </div>
        </div>
    </div>

    <form id="final-checkout-form" action="ordering/process_order.php" method="POST" style="display: none;">
        <input type="hidden" name="delivery" id="delivery-method">
        <input type="hidden" name="pickup_address" id="pickup-address">
        <input type="hidden" name="total_amount" id="total-amount">
        <input type="hidden" name="name" id="name">
        <input type="hidden" name="surname" id="surname">
        <input type="hidden" name="email" id="email">
        <input type="hidden" name="phone" id="phone">
        <input type="hidden" name="country" id="country">
        <input type="hidden" name="city" id="city">
        <input type="hidden" name="street" id="street">
        <input type="hidden" name="house" id="house">
        <input type="hidden" name="apartment" id="apartment">
        <input type="hidden" name="postal_code" id="postal-code">
        <input type="hidden" name="shipping_price" id="shipping-price">
    </form>

    <?php
    $freeShipping = isset($_GET['freeShipping']) && $_GET['freeShipping'] === 'true';
    ?>

    <script>
        const freeShipping = <?= json_encode($freeShipping) ?>;
        const userId = <?= json_encode($userId) ?>;
    </script>

</body>
</html>