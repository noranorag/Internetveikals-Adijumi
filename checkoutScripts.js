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

function handleDeliveryOption(deliveryMethod) {
    
    document.getElementById('omniva-dropdown').style.display = 'none';
    document.getElementById('dpd-dropdown').style.display = 'none';
    document.getElementById('address-container').style.display = 'none';

    
    if (deliveryMethod === 'omniva-pakomats') {
        document.getElementById('omniva-dropdown').style.display = 'block';
    } else if (deliveryMethod === 'dpd') {
        document.getElementById('dpd-dropdown').style.display = 'block';
    } else if (deliveryMethod === 'omniva-kurjers' || deliveryMethod === 'pasts') {
        document.getElementById('address-container').style.display = 'block';
    }
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



document.addEventListener('DOMContentLoaded', () => {

fetch('check_login.php') 
    .then(response => response.json())
    .then(data => {
    if (data.loggedIn) {
        
        document.querySelector('.login-prompt').style.display = 'none';

        
        fetchUserInfo();
    } else {
        
        document.querySelector('.login-prompt').style.display = 'block';
    }
    })
    .catch(error => console.error('Error checking login status:', error));

    if (freeShipping) {
    
    document.querySelectorAll('.shipping-price').forEach(price => {
        price.style.display = 'none';
    });
}
});

function fetchUserInfo() {
fetch('./get_user_info.php')
    .then(response => {
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
    })
    .then(user => {
    if (user.error) {
        console.error('Error:', user.error);
        return;
    }

    
    document.querySelector('input[placeholder="Ievadiet savu vārdu"]').value = user.name || '';
    document.querySelector('input[placeholder="Ievadiet savu uzvārdu"]').value = user.surname || '';
    document.querySelector('input[placeholder="Ievadiet savu e-pastu"]').value = user.email || '';
    document.querySelector('input[placeholder="Ievadiet savu tālruņa numuru"]').value = user.phone || '';

    
    document.querySelector('input[placeholder="Ievadiet valsti"]').value = user.country || '';
    document.querySelector('input[placeholder="Ievadiet pilsētu"]').value = user.city || '';
    document.querySelector('input[placeholder="Ievadiet ielu"]').value = user.street || '';
    document.querySelector('input[placeholder="Ievadiet mājas numuru"]').value = user.house || '';
    document.querySelector('input[placeholder="Ievadiet dzīvokļa numuru"]').value = user.apartment || '';
    document.querySelector('input[placeholder="Ievadiet pasta indeksu"]').value = user.postal_code || '';
    })
    .catch(error => console.error('Error fetching user information:', error));
}

function showOrderSummary() {
    
    document.getElementById('summary-name').textContent = document.querySelector('input[placeholder="Ievadiet savu vārdu"]').value;
    document.getElementById('summary-surname').textContent = document.querySelector('input[placeholder="Ievadiet savu uzvārdu"]').value;
    document.getElementById('summary-email').textContent = document.querySelector('input[placeholder="Ievadiet savu e-pastu"]').value;
    document.getElementById('summary-phone').textContent = document.querySelector('input[placeholder="Ievadiet savu tālruņa numuru"]').value;

    
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    const deliveryMethod = selectedDelivery ? selectedDelivery.value : 'Nav izvēlēts';
    document.getElementById('summary-delivery-method').textContent = deliveryMethod;

    
    const addressSection = document.getElementById('address-summary');
    const parcelLocationSection = document.getElementById('parcel-location-summary');

    if (deliveryMethod === 'omniva pakomāts' || deliveryMethod === 'dpd') {
        
        addressSection.style.display = 'none';

        
        const dropdownId = deliveryMethod === 'omniva pakomāts' ? 'omniva-pakomati' : 'dpd-pakomati';
        const selectedDropdown = document.getElementById(dropdownId).options[document.getElementById(dropdownId).selectedIndex].text;
        parcelLocationSection.style.display = 'block';
        document.getElementById('summary-parcel-location').textContent = selectedDropdown || 'Nav izvēlēts';
    } else {
        
        addressSection.style.display = 'block';

        
        parcelLocationSection.style.display = 'none';

        
        document.getElementById('summary-country').textContent = document.querySelector('input[placeholder="Ievadiet valsti"]').value;
        document.getElementById('summary-city').textContent = document.querySelector('input[placeholder="Ievadiet pilsētu"]').value;
        document.getElementById('summary-street').textContent = document.querySelector('input[placeholder="Ievadiet ielu"]').value;
        document.getElementById('summary-house').textContent = document.querySelector('input[placeholder="Ievadiet mājas numuru"]').value;
        document.getElementById('summary-apartment').textContent = document.querySelector('input[placeholder="Ievadiet dzīvokļa numuru"]').value;
        document.getElementById('summary-postal-code').textContent = document.querySelector('input[placeholder="Ievadiet pasta indeksu"]').value;
    }

    
    fetch('ordering/get_cart_info.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(cartItems => {
            console.log('Cart Items:', cartItems); 
            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = ''; 

            if (cartItems.error) {
                cartItemsContainer.innerHTML = `<p>${cartItems.error}</p>`;
                return;
            }

            let totalProductPrice = 0;

            cartItems.forEach(item => {
                const itemElement = document.createElement('p');
                itemElement.textContent = `${item.product_name} - ${item.quantity} gab. - ${item.product_price} EUR`;
                cartItemsContainer.appendChild(itemElement);

                
                totalProductPrice += item.product_price * item.quantity;
            });

            
            document.getElementById('summary-product-total').textContent = `€${totalProductPrice.toFixed(2)}`;

            
            let shippingPrice = 0;
            if (!freeShipping || deliveryMethod === 'omniva kurjers') {
                shippingPrice = shippingPrices[deliveryMethod] || 0;
            }
            document.getElementById('summary-shipping-price').textContent = `€${shippingPrice.toFixed(2)}`;

            
            const totalCost = totalProductPrice + shippingPrice;
            document.getElementById('summary-total-cost').textContent = `€${totalCost.toFixed(2)}`;
        })
        .catch(error => console.error('Error fetching cart items:', error));
}

function clearAddressFields() {
    document.getElementById('country').value = '';
    document.getElementById('city').value = '';
    document.getElementById('street').value = '';
    document.getElementById('house').value = '';
    document.getElementById('apartment').value = '';
    document.getElementById('postal-code').value = '';
}

function populateAddressFields() {
    document.getElementById('country').value = document.querySelector('input[placeholder="Ievadiet valsti"]').value;
    document.getElementById('city').value = document.querySelector('input[placeholder="Ievadiet pilsētu"]').value;
    document.getElementById('street').value = document.querySelector('input[placeholder="Ievadiet ielu"]').value;
    document.getElementById('house').value = document.querySelector('input[placeholder="Ievadiet mājas numuru"]').value;
    document.getElementById('apartment').value = document.querySelector('input[placeholder="Ievadiet dzīvokļa numuru"]').value;
    document.getElementById('postal-code').value = document.querySelector('input[placeholder="Ievadiet pasta indeksu"]').value;
}

document.addEventListener('DOMContentLoaded', () => {
    
    fetchOmnivaPakomati();
    fetchDPDPakomati();

    
    if (freeShipping) {
        document.querySelectorAll('.shipping-price').forEach(price => {
            price.style.display = 'none'; 
        });
    }

    const form = document.getElementById('final-checkout-form'); 
        if (form) {
            form.addEventListener('submit', collectOrderData);
            form.addEventListener('submit', () => {
                console.log('Form is being submitted...');
            });
        } else {
            console.error('Form with id "final-checkout-form" not found.');
        }
});