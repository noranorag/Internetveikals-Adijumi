const messages = [
    "Pirkumiem virs 70 eiro bezmaksas piegāde",
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

function toggleFilterModal() {
    const filterModal = document.getElementById('filterModal');
    filterModal.classList.toggle('show');
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