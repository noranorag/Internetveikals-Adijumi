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
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    const visibleItems = 5; // Number of visible items
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
            }, 1000); // Adjust this timeout to match the transition duration
        } else {
            carousel.style.transition = 'transform 1s ease';
            carousel.style.transform = `translateX(-${currentIndex * (100 / visibleItems)}%)`;
        }
    }

    setInterval(moveCarousel, 3000); // Change every 3 seconds
});