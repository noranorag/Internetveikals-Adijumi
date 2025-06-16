if (!window.messages) {
    const messages = [
        "Pirkumiem virs 55 eiro bezmaksas piegāde",
        "Nopērc kvalitatīvus adījumus jau šodien"
    ];
let currentMessageIndex = 0;
const announcementElement = document.getElementById('announcement');
let announcementText = document.createElement('span');
announcementText.textContent = messages[currentMessageIndex];
announcementElement.appendChild(announcementText);

setInterval(() => {
    const newText = document.createElement('span');
    newText.textContent = messages[(currentMessageIndex + 1) % messages.length];
    newText.style.transform = 'translateY(-100%)';
    newText.style.position = 'absolute';
    newText.style.width = '100%';
    announcementElement.appendChild(newText);

    setTimeout(() => {
        announcementText.style.transform = 'translateY(100%)';
        newText.style.transform = 'translateY(0)';
    }, 50);

    setTimeout(() => {
        announcementElement.removeChild(announcementText);
        announcementText = newText;
        currentMessageIndex = (currentMessageIndex + 1) % messages.length;
    }, 1050);
}, 4000);
}


document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        const items = document.querySelectorAll('.carousel div');
        const totalItems = items.length;
        const visibleItems = 5;
        let currentIndex = 0;

        function moveCarousel() {
            currentIndex++;
            if (currentIndex >= totalItems - visibleItems) {
                carousel.style.transition = 'transform 1s ease';
                carousel.style.transform = `translateX(-${currentIndex * (100 / visibleItems)}%)`;
                setTimeout(() => {
                    carousel.style.transition = 'none';
                    carousel.style.transform = `translateX(0)`;
                    currentIndex = 0;
                }, 1000); 
            } else {
                carousel.style.transition = 'transform 1s ease';
                carousel.style.transform = `translateX(-${currentIndex * (100 / visibleItems)}%)`;
            }
        }

        setInterval(moveCarousel, 3000); 
    }
});


function validateRegistrationForm(form) {
    const firstName = form.querySelector('#first_name').value.trim();
    const lastName = form.querySelector('#last_name').value.trim();
    const email = form.querySelector('#email').value.trim();
    const phone = form.querySelector('#phone').value.trim();
    const password = form.querySelector('#password').value.trim();
    const errorMessage = form.querySelector('#errorMessage');

    if (errorMessage) {
        errorMessage.textContent = '';
        errorMessage.style.display = 'none';
    }

    if (firstName.length < 2) {
        showError(errorMessage, 'Vārds nedrīkst būt īsāks par 2 rakstzīmēm.');
        return false;
    }
    if (firstName.length > 50) {
        showError(errorMessage, 'Vārds nedrīkst pārsniegt 50 rakstzīmes.');
        return false;
    }

    if (lastName.length < 2) {
        showError(errorMessage, 'Uzvārds nedrīkst būt īsāks par 2 rakstzīmēm.');
        return false;
    }
    if (lastName.length > 50) {
        showError(errorMessage, 'Uzvārds nedrīkst pārsniegt 50 rakstzīmes.');
        return false;
    }

    if (email.length < 5) {
        showError(errorMessage, 'E-pasta adrese nedrīkst būt īsāka par 5 rakstzīmēm.');
        return false;
    }
    if (email.length > 255) {
        showError(errorMessage, 'E-pasta adrese nedrīkst pārsniegt 255 rakstzīmes.');
        return false;
    }
    if (!email.includes('@')) {
        showError(errorMessage, 'E-pasta adresei jābūt derīgai un jāietver "@" simbols.');
        return false;
    }

    if (phone.length < 8) {
        showError(errorMessage, 'Tālrunis nedrīkst būt īsāks par 8 rakstzīmēm.');
        return false;
    }
    if (phone.length > 12) {
        showError(errorMessage, 'Tālrunis nedrīkst pārsniegt 12 rakstzīmes.');
        return false;
    }

    if (password.length < 8) {
        showError(errorMessage, 'Parolei jābūt vismaz 8 rakstzīmes garai.');
        return false;
    }
    if (password.length > 255) {
        showError(errorMessage, 'Parole nedrīkst pārsniegt 255 rakstzīmes.');
        return false;
    }

    return true; 
}

function showError(errorMessageElement, message) {
    if (errorMessageElement) {
        errorMessageElement.textContent = message;
        errorMessageElement.style.display = 'block';
    }
}



function toggleFilterModal() {
    const filterModal = document.getElementById('filterModal');
    filterModal.classList.toggle('show');
}




window.onclick = function(event) {
    const filterModal = document.getElementById('filterModal');
    if (event.target == filterModal) {
        filterModal.classList.remove('show');
    }
}

function toggleSubcategories(id) {
    const subcategories = document.getElementById(id);
    const arrow = subcategories.previousElementSibling.querySelector('.arrow');
    if (subcategories.style.display === 'none' || subcategories.style.display === '') {
        subcategories.style.display = 'block';
        arrow.style.transform = 'rotate(90deg)';
    } else {
        subcategories.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}


$('#filterButton').click(function () {
    $('#filterModal').modal('show'); 
});

document.addEventListener('DOMContentLoaded', () => {
    const priceRange = document.getElementById('priceRange');
    if (!priceRange) {
        return; 
    }

    const priceMinInput = document.getElementById('priceMinInput');
    const priceMaxInput = document.getElementById('priceMaxInput');
    const priceValueMin = document.getElementById('priceValueMin');
    const priceValueMax = document.getElementById('priceValueMax');

    
    const urlParams = new URLSearchParams(window.location.search);
    const priceMin = urlParams.get('price_min') || 8; 
    const priceMax = urlParams.get('price_max') || 70; 

    
    if (!priceRange.noUiSlider) {
        noUiSlider.create(priceRange, {
            start: [priceMin, priceMax], 
            connect: true, 
            range: {
                min: 8, 
                max: 70 
            },
            step: 1, 
            tooltips: [true, true], 
            format: {
                to: value => Math.round(value), 
                from: value => Number(value) 
            }
        });

        
        priceRange.noUiSlider.on('update', (values) => {
            priceMinInput.value = values[0];
            priceMaxInput.value = values[1];
            priceValueMin.textContent = `${values[0]}€`;
            priceValueMax.textContent = `${values[1]}€`;
        });

        
        priceMinInput.value = priceMin;
        priceMaxInput.value = priceMax;
        priceValueMin.textContent = `${priceMin}€`;
        priceValueMax.textContent = `${priceMax}€`;
    } else {
    }
});

function selectBigCategory(bigCategory, checkbox) {
    const bigCategoryInput = document.getElementById('bigCategoryInput');
    const subcategoryCheckboxes = document.querySelectorAll(`.${bigCategory.toLowerCase()}Subcategories`);

    if (checkbox.checked) {
        bigCategoryInput.value = bigCategory; 
        subcategoryCheckboxes.forEach(subCheckbox => {
            subCheckbox.checked = false; 
        });
    } else {
        bigCategoryInput.value = ''; 
    }
}

function resetFilters() {
    
    window.location.href = 'eshop.php';
}




function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
}

function toggleHeart(button) {
    const icon = button.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far'); 
        icon.classList.add('fas'); 
        button.classList.add('active');
    } else {
        icon.classList.remove('fas'); 
        icon.classList.add('far');
        button.classList.remove('active'); 
    }
}

function showContent(sectionId, index) {
    document.querySelectorAll('.toggle-content').forEach(content => {
        content.style.display = 'none';
    });

    document.getElementById(sectionId).style.display = 'block';

    const highlight = document.querySelector('.line-highlight');
    highlight.style.left = `${index * 33.33}%`;
}

function toggleFavourite(productID, button) {

    fetch('check_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {

        if (!data.loggedIn) {
            const loginModal = document.getElementById('loginModal');
            loginModal.classList.add('show'); 

            const loginButton = loginModal.querySelector('.btn-primary');
            const currentUrl = window.location.href;
            loginButton.href = `login.php?redirect=${encodeURIComponent(currentUrl)}&product_ID=${productID}`;
        } else {
            fetch('user-database/toggle_favourite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_ID: productID }),
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                    const icon = button.querySelector('i');
                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        button.classList.add('active');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        button.classList.remove('active');
                    }
                } else {
                    alert('Kļūda: ' + data.message);
                }
            })
            .catch(error => console.error('Error while toggling favourite:', error));
        }
    })
    .catch(error => console.error('Error during login check:', error));
}




function openModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.classList.add('show');
    } else {
    }
}

function closeModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        modal.classList.remove('show');
    }

    
}





function checkLoginForHeart(event) {
    event.preventDefault();

    // Use an absolute path to ensure the correct file is accessed
    fetch('/check_login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Error during login check: ' + response.statusText);
            }
            return response.json();
        })
        .then((data) => {
            if (data.loggedIn) {
                // Redirect to favourites page if logged in
                window.location.href = '/favourites.php';
            } else {
                // Show login modal if not logged in
                const loginModal = document.getElementById('loginModal');
                if (loginModal) {
                    loginModal.classList.add('show'); // Ensure the modal is displayed
                } else {
                    alert('Lūdzu, ielogojieties, lai piekļūtu favorītiem.');
                }
            }
        })
        .catch((error) => {
            console.error('Error during login check:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    const addToGalleryBtn = document.getElementById("addToGalleryBtn");
    if (addToGalleryBtn) {
        addToGalleryBtn.addEventListener("click", function () {
            fetch('check_login.php')
                .then(response => response.json())
                .then(data => {
                    if (data.loggedIn) {
                        
                        $('#addImageModal').modal('show');
                    } else {
                        
                        $('#loginModal').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error checking login status:', error);
                });
        });
    }
});

function addToCart(productID) {
    const quantity = document.getElementById('quantity').value;

    fetch('user-database/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_ID: productID, quantity: quantity }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const button = document.getElementById('addToCartButton');
            button.innerHTML = '<i class="fas fa-check"></i> Jau grozā';
            button.disabled = true;

            console.log('Calling updateCartCount...');
            updateCartCount(); 

            
            showCartNotification(productID);
        } else {
            alert('Kļūda pievienojot grozam: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function showCartNotification(productID) {
    
    fetch(`user-database/get_product_details.php?product_ID=${productID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById('cartNotification');
                const notificationImage = document.getElementById('notificationImage');
                const notificationName = document.getElementById('notificationName');
                const notificationPrice = document.getElementById('notificationPrice');

                
                notificationImage.src = data.product.image;
                notificationName.textContent = data.product.name;
                notificationPrice.textContent = `Cena: €${data.product.price}`;

                
                notification.style.display = 'flex';

                
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            } else {
                console.error('Failed to fetch product details for notification:', data.error);
            }
        })
        .catch(error => {
            console.error('Error fetching product details for notification:', error);
        });
}

function updateCartCount() {
    console.log('updateCartCount: Fetching cart count...');
    fetch('user-database/get_cart_counts.php') 
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('updateCartCount: Cart count response:', data);
            if (data.success) {
                const cartBadge = document.querySelector('.cart-badge');
                const cartIcon = document.querySelector('.fa-shopping-cart');
                if (data.cartCount > 0) {
                    if (!cartBadge) {
                        
                        const badge = document.createElement('span');
                        badge.className = 'badge badge-danger position-absolute cart-badge';
                        badge.style.top = '-5px';
                        badge.style.right = '-10px';
                        badge.textContent = data.cartCount;
                        cartIcon.parentElement.appendChild(badge);
                    } else {
                        
                        cartBadge.textContent = data.cartCount;
                    }
                } else if (cartBadge) {
                    
                    cartBadge.remove();
                }
            } else {
                console.error('updateCartCount: Failed to fetch cart count:', data.error);
            }
        })
        .catch(error => {
            console.error('updateCartCount: Error fetching cart count:', error);
        });
}

document.querySelector('.close').addEventListener('click', () => {
    $('#loginModal').modal('hide');
});

document.querySelector('.btn-secondary').addEventListener('click', () => {
    $('#loginModal').modal('hide');
});

$(document).on('click', '.btn-danger[data-target="#deleteImageModal"]', function () {
    const imageId = $(this).data('id');
    $('#deleteImageId').val(imageId);
});

$('#confirmDeleteImage').on('click', function () {
    const imageId = $('#deleteImageId').val();

    $.ajax({
        url: 'delete_gallery_image.php',
        type: 'POST',
        data: { gallery_ID: imageId },
        success: function (result) { 
            if (result.success) {
                $('#deleteImageModal').modal('hide');
                location.reload(); 
            } else {
                alert(result.error || 'Neizdevās dzēst bildi.');
            }
        },
        error: function () {
            alert('Neizdevās nosūtīt pieprasījumu.');
        }
    });
});

