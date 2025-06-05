function showSection(sectionId, index) {

if (sectionId === 'shipping') {
    const requiredInputs = document.querySelectorAll('#information input[required]');
    let allValid = true;
    const errorContainer = document.getElementById('information-errors');
    errorContainer.style.display = 'none'; 
    errorContainer.innerHTML = ''; 

    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            allValid = false;
            input.classList.add('is-invalid'); 
        } else {
            input.classList.remove('is-invalid'); 
        }
    });

    if (!allValid) {
        errorContainer.style.display = 'block';
        errorContainer.innerHTML = 'Lūdzu aizpildiet visus nepieciešamos laukus!';
        return; 
    }
}


if (sectionId === 'order') {
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    console.log('Selected Delivery:', selectedDelivery ? selectedDelivery.value : 'None');
    const errorContainer = document.getElementById('shipping-errors');
    errorContainer.style.display = 'none'; 
    errorContainer.innerHTML = ''; 

    if (!selectedDelivery) {
        errorContainer.style.display = 'block';
        errorContainer.innerHTML = 'Lūdzu izvēlieties piegādes metodi!';
        return; 
    }

    if (selectedDelivery.value === 'omniva-pakomats') {
        const omnivaDropdown = document.getElementById('omniva-pakomati');
        if (!omnivaDropdown.value) {
            errorContainer.style.display = 'block';
            errorContainer.innerHTML = 'Lūdzu izvēlieties Omniva pakomātu!';
            return; 
        }
    } else if (selectedDelivery.value === 'dpd') {
        const dpdDropdown = document.getElementById('dpd-pakomati');
        if (!dpdDropdown.value) {
            errorContainer.style.display = 'block';
            errorContainer.innerHTML = 'Lūdzu izvēlieties DPD pakomātu!';
            return; 
        }
    } else if (selectedDelivery.value === 'omniva-kurjers' || selectedDelivery.value === 'pasts') {
        const requiredAddressInputs = document.querySelectorAll('#address-container input[required]');
        let allAddressValid = true;

        requiredAddressInputs.forEach(input => {
            if (!input.value.trim()) {
                allAddressValid = false;
                input.classList.add('is-invalid'); 
            } else {
                input.classList.remove('is-invalid'); 
            }
        });

        if (!allAddressValid) {
            errorContainer.style.display = 'block';
            errorContainer.innerHTML = 'Lūdzu aizpildiet visus adreses laukus!';
            return; 
        }
    }
}


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

document.querySelectorAll('.step').forEach((step, index) => {
step.addEventListener('click', () => {
    if (index === 1) {
        showSection('shipping', 1); 
    } else if (index === 2) {
        showSection('order', 2); 
    }
});
});
}



function fetchOmnivaPakomati() {
    fetch('json-files/omniva_locations.json') 
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const dropdown = document.getElementById('omniva-pakomati');
            dropdown.innerHTML = '<option value="">Izvēlieties pakomātu</option>'; 
            data.forEach(pakomats => {
                
                if (pakomats.A0_NAME === "LV") {
                    const option = document.createElement('option');
                    option.value = pakomats.ZIP; 
                    option.textContent = `${pakomats.NAME}, ${pakomats.A5_NAME} ${pakomats.A7_NAME}`; 
                    dropdown.appendChild(option);
                }
            });
        })
        .catch(error => console.error('Error loading Omniva pakomāti:', error));
}

function fetchDPDPakomati() {
    fetch('json-files/dpd_locations.json') 
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            const dropdown = document.getElementById('dpd-pakomati');
            dropdown.innerHTML = '<option value="">Izvēlieties pakomātu</option>'; 
            data.forEach(pakomats => {
                const option = document.createElement('option');
                option.value = `${pakomats.city}, ${pakomats.location}`; 
                option.textContent = `${pakomats.city}, ${pakomats.location}`; 
                dropdown.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading DPD pakomāti:', error));
}

function handleDeliveryOption(option) {
    // Hide all sections
    document.getElementById('omniva-dropdown').style.display = 'none';
    document.getElementById('dpd-dropdown').style.display = 'none';
    document.getElementById('address-container').style.display = 'none';

    // Show the appropriate section based on the selected option
    if (option === 'omniva-pakomats') {
        document.getElementById('omniva-dropdown').style.display = 'block';
        fetchOmnivaPakomati(); // Load Omniva locations
    } else if (option === 'dpd') {
        document.getElementById('dpd-dropdown').style.display = 'block';
        fetchDPDPakomati(); // Load DPD locations
    } else if (option === 'omniva-kurjers' || option === 'pasts') {
        document.getElementById('address-container').style.display = 'block';
    }
}

function showOrderSummary() {
    console.log("Starting showOrderSummary...");

    // Collect user information
    const name = document.querySelector('input[name="name"]').value.trim();
    const surname = document.querySelector('input[name="surname"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();

    console.log("User Information:", { name, surname, email, phone });

    // Collect delivery method
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    const deliveryMethod = selectedDelivery ? selectedDelivery.value : '';
    console.log("Selected Delivery Method:", deliveryMethod);

    // Collect parcel location or address
    let deliveryAddress = '';
    let address = {
        country: '',
        city: '',
        street: '',
        house: '',
        apartment: '',
        postalCode: ''
    };

    let deliveryPrice = 0;

    if (deliveryMethod === 'omniva pakomāts') {
        console.log("Omniva Pakomāts selected.");
        const selectedOption = document.getElementById('omniva-pakomati').selectedOptions[0];
        deliveryAddress = selectedOption ? selectedOption.textContent : '';
        console.log("Omniva Pakomāts Address:", deliveryAddress);

        deliveryPrice = freeShipping ? 0 : 3.00; // Free shipping logic
        document.getElementById('parcel-location-summary').style.display = 'block';
        document.getElementById('summary-parcel-location').textContent = deliveryAddress;
        document.getElementById('address-summary').style.display = 'none'; // Hide address summary
    } else if (deliveryMethod === 'dpd') {
        console.log("DPD selected.");
        const selectedOption = document.getElementById('dpd-pakomati').selectedOptions[0];
        deliveryAddress = selectedOption ? selectedOption.textContent : '';
        console.log("DPD Address:", deliveryAddress);

        deliveryPrice = freeShipping ? 0 : 2.50; // Free shipping logic
        document.getElementById('parcel-location-summary').style.display = 'block';
        document.getElementById('summary-parcel-location').textContent = deliveryAddress;
        document.getElementById('address-summary').style.display = 'none'; // Hide address summary
    } else if (deliveryMethod === 'omniva kurjers') {
        console.log("Omniva Kurjers selected.");
        address.country = document.querySelector('input[name="country"]').value.trim();
        address.city = document.querySelector('input[name="city"]').value.trim();
        address.street = document.querySelector('input[name="street"]').value.trim();
        address.house = document.querySelector('input[name="house"]').value.trim();
        address.apartment = document.querySelector('input[name="apartment"]').value.trim();
        address.postalCode = document.querySelector('input[name="postal_code"]').value.trim();

        console.log("Address Fields:", address);

        deliveryPrice = 12.00; // Omniva Kurjers always has a price
        deliveryAddress = `${address.street} ${address.house}, ${address.city}, ${address.country}, ${address.postalCode}`;
        if (address.apartment) {
            deliveryAddress += `, Dzīvoklis: ${address.apartment}`;
        }

        console.log("Formatted Address:", deliveryAddress);

        document.getElementById('address-summary').style.display = 'block';
        document.getElementById('summary-country').textContent = address.country;
        document.getElementById('summary-city').textContent = address.city;
        document.getElementById('summary-street').textContent = address.street;
        document.getElementById('summary-house').textContent = address.house;
        document.getElementById('summary-apartment').textContent = address.apartment || 'Nav norādīts';
        document.getElementById('summary-postal-code').textContent = address.postalCode;
        document.getElementById('parcel-location-summary').style.display = 'none'; // Hide parcel location summary
    } else if (deliveryMethod === 'latvijas pasts') {
        console.log("Latvijas Pasts selected.");
        address.country = document.querySelector('input[name="country"]').value.trim();
        address.city = document.querySelector('input[name="city"]').value.trim();
        address.street = document.querySelector('input[name="street"]').value.trim();
        address.house = document.querySelector('input[name="house"]').value.trim();
        address.apartment = document.querySelector('input[name="apartment"]').value.trim();
        address.postalCode = document.querySelector('input[name="postal_code"]').value.trim();

        console.log("Address Fields:", address);

        deliveryPrice = freeShipping ? 0 : 4.00; // Free shipping logic
        deliveryAddress = `${address.street} ${address.house}, ${address.city}, ${address.country}, ${address.postalCode}`;
        if (address.apartment) {
            deliveryAddress += `, Dzīvoklis: ${address.apartment}`;
        }

        console.log("Formatted Address:", deliveryAddress);

        document.getElementById('address-summary').style.display = 'block';
        document.getElementById('summary-country').textContent = address.country;
        document.getElementById('summary-city').textContent = address.city;
        document.getElementById('summary-street').textContent = address.street;
        document.getElementById('summary-house').textContent = address.house;
        document.getElementById('summary-apartment').textContent = address.apartment || 'Nav norādīts';
        document.getElementById('summary-postal-code').textContent = address.postalCode;
        document.getElementById('parcel-location-summary').style.display = 'none'; // Hide parcel location summary
    } else {
        console.log("No valid delivery method selected.");
        document.getElementById('parcel-location-summary').style.display = 'none';
        document.getElementById('address-summary').style.display = 'none';
    }

    // Update user information in the summary
    document.getElementById('summary-name').textContent = name;
    document.getElementById('summary-surname').textContent = surname;
    document.getElementById('summary-email').textContent = email;
    document.getElementById('summary-phone').textContent = phone;
    document.getElementById('summary-delivery-method').textContent = deliveryMethod;

    console.log("Fetching cart items...");

    // Fetch cart items and update the summary
    fetch('ordering/get_cart_info.php')
        .then(response => response.json())
        .then(cartItems => {
            console.log("Cart Items:", cartItems);

            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = ''; // Clear previous items

            let totalPrice = 0;

            cartItems.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'd-flex justify-content-between';
                itemElement.innerHTML = `
                    <span>${item.product_name} (${item.quantity}x)</span>
                    <span>€${(item.product_price * item.quantity).toFixed(2)}</span>
                `;
                cartItemsContainer.appendChild(itemElement);

                totalPrice += item.product_price * item.quantity;
            });

            // Update total price in the summary
            document.getElementById('summary-product-total').textContent = `€${totalPrice.toFixed(2)}`;
            document.getElementById('summary-shipping-price').textContent = `€${deliveryPrice.toFixed(2)}`; // Update delivery price
            document.getElementById('summary-total-cost').textContent = `€${(totalPrice + deliveryPrice).toFixed(2)}`; // Adjust total cost
        })
        .catch(error => console.error('Error fetching cart items:', error));

    console.log("Summary updated successfully.");
}

function updateDeliveryPrices() {
    console.log("Free Shipping Status in JavaScript:", freeShipping); // Debug the freeShipping variable

    const deliveryOptions = [
        { id: 'omniva pakomāts', label: 'Piegāde ar Omniva pakomātu', price: 3.00 },
        { id: 'dpd', label: 'Piegāde ar DPD', price: 2.50 },
        { id: 'omniva kurjers', label: 'Piegāde ar Omniva kurjeru', price: 12.00 },
        { id: 'latvijas pasts', label: 'Piegāde pa pastu', price: 4.00 }
    ];

    deliveryOptions.forEach(option => {
        const deliveryElement = document.querySelector(`input[value="${option.id}"]`);
        if (!deliveryElement) {
            console.error(`Delivery option not found: ${option.id}`);
            return;
        }

        const priceElement = deliveryElement.parentElement.querySelector('.shipping-price');
        if (!priceElement) {
            console.error(`Price element not found for: ${option.id}`);
            return;
        }

        if (freeShipping === true && option.id !== 'omniva kurjers') {
            priceElement.textContent = '(Bezmaksas piegāde)';
        } else {
            priceElement.textContent = `(${option.price.toFixed(2)}€)`;
        }

        console.log(`Updated price for ${option.label}:`, priceElement.textContent);
    });
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', updateDeliveryPrices);


function collectOrderData(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    console.log("Collecting order data...");

    // Collect delivery method
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    const deliveryMethod = selectedDelivery ? selectedDelivery.value : '';
    const pickupAddress = document.getElementById('summary-parcel-location')?.textContent.trim() || '';

    // Collect user information
    const name = document.querySelector('input[name="name"]').value.trim();
    const surname = document.querySelector('input[name="surname"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();

    // Collect address information
    const country = document.querySelector('input[name="country"]')?.value.trim() || '';
    const city = document.querySelector('input[name="city"]')?.value.trim() || '';
    const street = document.querySelector('input[name="street"]')?.value.trim() || '';
    const house = document.querySelector('input[name="house"]')?.value.trim() || '';
    const apartment = document.querySelector('input[name="apartment"]')?.value.trim() || '';
    const postalCode = document.querySelector('input[name="postal_code"]')?.value.trim() || '';

    // Collect total amount and shipping price
    const totalAmount = document.getElementById('summary-total-cost')?.textContent.replace('€', '').trim() || '0.00';
    const shippingPrice = document.getElementById('summary-shipping-price')?.textContent.replace('€', '').trim() || '0.00';

    console.log("Order Data:", {
        deliveryMethod,
        pickupAddress,
        name,
        surname,
        email,
        phone,
        country,
        city,
        street,
        house,
        apartment,
        postalCode,
        totalAmount,
        shippingPrice
    });

    // Populate hidden form fields
    const formFields = {
        'delivery-method': deliveryMethod,
        'pickup-address': pickupAddress,
        'name': name,
        'surname': surname,
        'email': email,
        'phone': phone,
        'country': country,
        'city': city,
        'street': street,
        'house': house,
        'apartment': apartment,
        'postal-code': postalCode,
        'total-amount': totalAmount,
        'shipping-price': shippingPrice
    };

    Object.keys(formFields).forEach(fieldId => {
        const fieldElement = document.getElementById(fieldId);
        if (fieldElement) {
            fieldElement.value = formFields[fieldId];
        } else {
            console.error(`Field with ID "${fieldId}" not found in the form.`);
        }
    });

    // Submit the form
    document.getElementById('final-checkout-form').submit();
}