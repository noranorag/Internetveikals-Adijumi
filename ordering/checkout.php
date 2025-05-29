<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Piegādes lapa</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <!-- Paziņojums -->
    <div class="announcement" id="announcement"></div>

    <!-- Navigācijas josla -->
    <?php include '../files/navbar.php'; ?>

    <!-- Galvenais saturs -->
    <div class="container mt-5 pt-1">
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
                <div class="step dimmed" onclick="showSection('order', 2)">
                    <div class="step-icon">03</div>
                    <div class="step-title">Jūsu pasūtījums</div>
                </div>
            </div>
            <div class="line-container mt-2">
                <div class="line-highlight"></div>
            </div>

            <!-- Informācija Section -->
            <div id="information" class="form-section">
                <div class="login-prompt text-center mb-4">
                    <p>Ir konts? <a href="#" class="btn btn-outline-main">Ielogojies</a> lai aizpildītu informāciju</p>
                </div>

                <div class="form-group">
                    <label>Vārds *</label>
                    <input type="text" class="form-control" placeholder="Ievadiet savu vārdu" required>
                </div>
                <div class="form-group">
                    <label>Uzvārds *</label>
                    <input type="text" class="form-control" placeholder="Ievadiet savu uzvārdu" required>
                </div>
                <div class="form-group">
                    <label>E-pasts *</label>
                    <input type="email" class="form-control" placeholder="Ievadiet savu e-pastu" required>
                </div>
                <div class="form-group">
                    <label>Tālrunis *</label>
                    <input type="tel" class="form-control" placeholder="Ievadiet savu tālruņa numuru" required>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-main mt-3" style="width: 200px;" onclick="showSection('shipping', 1)">Tālāk</button>
                </div>
            </div>

            <!-- Piegāde Section -->
            <div id="shipping" class="form-section" style="display: none;">
                <div class="form-group">
                    <div class="delivery-option">
                        <label>
                            <img src="../images/omniva.png" alt="Omniva pakomāts" style="width: 80px; margin-right: 10px;">
                            <input type="radio" name="delivery" value="omniva-pakomats" required onclick="handleDeliveryOption('omniva-pakomats')">
                            Piegāde ar Omniva pakomātu
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="../images/omniva.png" alt="Omniva kurjers" style="width: 80px; margin-right: 10px;">
                            <input type="radio" name="delivery" value="omniva-kurjers" required onclick="handleDeliveryOption('omniva-kurjers')">
                            Piegāde ar Omniva kurjeru
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="../images/dpd.png" alt="DPD" style="width: 80px; margin-right: 10px;">
                            <input type="radio" name="delivery" value="dpd" required onclick="handleDeliveryOption('dpd')">
                            Piegāde ar DPD
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <img src="../images/latvijaspasts.png" alt="Latvijas pasts" style="width: 80px; margin-right: 10px;">
                            <input type="radio" name="delivery" value="pasts" required onclick="handleDeliveryOption('pasts')">
                            Piegāde pa pastu
                        </label>
                    </div>
                </div>

                <!-- Dropdown for Omniva Pakomāti -->
                <div id="omniva-dropdown" class="form-group" style="display: none;">
                    <label>Izvēlieties Omniva pakomātu *</label>
                    <select class="form-control" id="omniva-pakomati"></select>
                </div>

                <!-- Dropdown for DPD Pakomāti -->
                <div id="dpd-dropdown" class="form-group" style="display: none;">
                    <label>Izvēlieties DPD pakomātu *</label>
                    <select class="form-control" id="dpd-pakomati"></select>
                </div>

                <!-- Address Inputs -->
                <div id="address-container" class="address-container" style="display: none;">
                    <div class="form-group">
                        <label>Valsts *</label>
                        <input type="text" class="form-control" placeholder="Ievadiet valsti" required>
                    </div>
                    <div class="form-group">
                        <label>Pilsēta *</label>
                        <input type="text" class="form-control" placeholder="Ievadiet pilsētu" required>
                    </div>
                    <div class="form-group">
                        <label>Iela *</label>
                        <input type="text" class="form-control" placeholder="Ievadiet ielu" required>
                    </div>
                    <div class="form-group">
                        <label>Mājas numurs *</label>
                        <input type="text" class="form-control" placeholder="Ievadiet mājas numuru" required>
                    </div>
                    <div class="form-group">
                        <label>Dzīvokļa numurs</label>
                        <input type="text" class="form-control" placeholder="Ievadiet dzīvokļa numuru">
                    </div>
                    <div class="form-group">
                        <label>Pasta indekss *</label>
                        <input type="text" class="form-control" placeholder="Ievadiet pasta indeksu" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-main mt-3" style="width: 200px;" onclick="showSection('order', 2)">Tālāk</button>
                </div>
            </div>

            <!-- Jūsu pasūtījums Section -->
            <div id="order" class="form-section" style="display: none;">
                <p>Šeit būs jūsu pasūtījuma detaļas.</p>
                <div class="text-center">
                    <button type="button" class="btn btn-main mt-3" style="width: 200px;" onclick="alert('Pasūtījums pabeigts!')">Pasūtīt</button>
                </div>
            </div>
        </div>
    </div>

<script>
  function showSection(sectionId, index) {
    document.querySelectorAll('.form-section').forEach(section => {
      section.style.display = 'none';
    });
    document.getElementById(sectionId).style.display = 'block';
    const highlight = document.querySelector('.line-highlight');
    highlight.style.left = `${index * 33.33}%`;
    document.querySelectorAll('.step').forEach((step, stepIndex) => {
      if (stepIndex === index) {
        step.classList.add('active');
        step.classList.remove('dimmed');
      } else {
        step.classList.remove('active');
        step.classList.add('dimmed');
      }
    });
  }

  function handleDeliveryOption(option) {
    document.getElementById('omniva-dropdown').style.display = 'none';
    document.getElementById('dpd-dropdown').style.display = 'none';
    document.getElementById('address-container').style.display = 'none';
    if (option === 'omniva-pakomats') {
      document.getElementById('omniva-dropdown').style.display = 'block';
      fetchOmnivaPakomati();
    } else if (option === 'dpd') {
      document.getElementById('dpd-dropdown').style.display = 'block';
      fetchDPDPakomati();
    } else {
      document.getElementById('address-container').style.display = 'block';
    }
  }

  function fetchOmnivaPakomati() {
    fetch('../json-files/omniva_locations.json') // Path to your saved JSON file
        .then(response => response.json())
        .then(data => {
        const dropdown = document.getElementById('omniva-pakomati');
        dropdown.innerHTML = '<option value="">Izvēlieties pakomātu</option>'; // Default option
        data.forEach(pakomats => {
            // Only include Latvian locations (A0_NAME === "LV")
            if (pakomats.A0_NAME === "LV") {
            const option = document.createElement('option');
            option.value = pakomats.ZIP; // Use ZIP as the unique value
            option.textContent = `${pakomats.NAME}, ${pakomats.A5_NAME} ${pakomats.A7_NAME}`; // Combine name, street, and house number
            dropdown.appendChild(option);
            }
        });
        })
        .catch(error => console.error('Error loading Omniva pakomāti:', error));
    }

  function fetchDPDPakomati() {
    fetch('../json-files/dpd_locations.json') // Path to your saved JSON file
        .then(response => response.json()) // Parse the JSON file
        .then(data => {
        const dropdown = document.getElementById('dpd-pakomati');
        dropdown.innerHTML = '<option value="">Izvēlieties pakomātu</option>'; // Default option

        // Iterate through the JSON data and populate the dropdown
        data.forEach(pakomats => {
            const option = document.createElement('option');
            option.value = `${pakomats.city}, ${pakomats.location}`; // Use city and location as the value
            option.textContent = `${pakomats.city}, ${pakomats.location}`; // Combine city and location for display
            dropdown.appendChild(option);
        });
        })
        .catch(error => console.error('Error loading DPD pakomāti:', error));
    }
</script>

</body>
</html>