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

document.addEventListener('DOMContentLoaded', function () {
    var priceRange = document.getElementById('priceRange');

    noUiSlider.create(priceRange, {
        start: [8, 70],
        connect: true,
        range: {
            'min': 8,
            'max': 70
        },
        tooltips: [true, true],
        format: {
            to: function (value) {
                return Math.round(value) + '€';
            },
            from: function (value) {
                return Number(value.replace('€', ''));
            }
        }
    });

    var priceValueMin = document.getElementById('priceValueMin');
    var priceValueMax = document.getElementById('priceValueMax');

    priceRange.noUiSlider.on('update', function (values, handle) {
        if (handle === 0) {
            priceValueMin.innerHTML = values[handle];
        } else {
            priceValueMax.innerHTML = values[handle];
        }
    });
});