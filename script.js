document.addEventListener('DOMContentLoaded', () => {
    const texts = document.querySelectorAll('.banner-text');
    let activeIndex = 0;

    setInterval(() => {
        const currentText = texts[activeIndex];
        const nextIndex = (activeIndex + 1) % texts.length;
        const nextText = texts[nextIndex];

        currentText.classList.remove('active');
        currentText.classList.add('exit');

        setTimeout(() => {
            currentText.classList.remove('exit'); 
            nextText.classList.add('active');    
        }, 500); 

        activeIndex = nextIndex;
    }, 3000);
});

document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        const items = document.querySelectorAll('.carousel-item');
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



document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        const items = document.querySelectorAll('.carousel-item');
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

function toggleFilterPanel() {
    const filterPanel = document.getElementById('filterPanel');
    const overlay = document.getElementById('overlay');
    filterPanel.classList.toggle('active');
    overlay.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const filterPanel = document.getElementById('filterPanel');
    const filterButton = document.querySelector('.filter-button');
    const overlay = document.getElementById('overlay');
    if (filterPanel.classList.contains('active') && !filterPanel.contains(event.target) && !filterButton.contains(event.target) && !overlay.contains(event.target)) {
        filterPanel.classList.remove('active');
        overlay.classList.remove('active');
    }
});

document.getElementById('overlay').addEventListener('click', function() {
    const filterPanel = document.getElementById('filterPanel');
    const overlay = document.getElementById('overlay');
    filterPanel.classList.remove('active');
    overlay.classList.remove('active');
});

function openModal(image) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modal.style.display = 'block';
    modalImage.src = image.src;
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

window.openModal = openModal;
window.closeModal = closeModal;